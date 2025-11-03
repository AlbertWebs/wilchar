<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\LoanApproval;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = LoanApplication::with(['client', 'loanOfficer', 'creditOfficer', 'director']);

        // Role-based filtering
        if ($user->hasRole('Loan Officer')) {
            $query->where(function($q) use ($user) {
                $q->where('approval_stage', 'loan_officer')
                  ->where(function($q2) use ($user) {
                      $q2->where('loan_officer_id', $user->id)
                         ->orWhereNull('loan_officer_id');
                  });
            })->whereIn('status', ['submitted', 'under_review']);
        } elseif ($user->hasRole('Credit Officer')) {
            $query->where('approval_stage', 'credit_officer')
                  ->where('background_check_status', 'passed')
                  ->whereIn('status', ['submitted', 'under_review']);
        } elseif ($user->hasRole('Director') || $user->hasRole('Admin')) {
            $query->where('approval_stage', 'director')
                  ->whereIn('status', ['submitted', 'under_review']);
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(15);

        return view('admin.approvals.index', compact('applications'));
    }

    /**
     * Show approval form for specific application
     */
    public function show(LoanApplication $loanApplication)
    {
        $user = auth()->user();
        
        // Check if user can approve at current stage
        if (!$this->canApproveAtStage($user, $loanApplication)) {
            return redirect()->route('approvals.index')
                ->with('error', 'You do not have permission to approve this application at this stage.');
        }

        $loanApplication->load([
            'client',
            'loanOfficer',
            'creditOfficer',
            'director',
            'kycDocuments',
            'approvals.approver'
        ]);

        return view('admin.approvals.show', compact('loanApplication'));
    }

    /**
     * Approve application at current stage
     */
    public function approve(Request $request, LoanApplication $loanApplication)
    {
        $user = auth()->user();
        
        if (!$this->canApproveAtStage($user, $loanApplication)) {
            return back()->with('error', 'You do not have permission to approve this application.');
        }

        $validated = $request->validate([
            'comment' => 'nullable|string|max:1000',
            'amount_approved' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $approvalLevel = $loanApplication->approval_stage;
            $previousApprovals = $loanApplication->approvals()->where('is_current_level', true)->get();
            
            // Mark previous approvals as not current
            foreach ($previousApprovals as $prev) {
                $prev->update(['is_current_level' => false]);
            }

            // Create approval record
            $approval = LoanApproval::create([
                'loan_application_id' => $loanApplication->id,
                'approved_by' => $user->id,
                'approval_level' => $approvalLevel,
                'previous_level' => $previousApprovals->last()?->approval_level,
                'is_current_level' => true,
                'comment' => $validated['comment'] ?? null,
                'status' => 'approved',
                'approved_at' => now(),
            ]);

            // Update application based on stage
            if ($approvalLevel === 'loan_officer') {
                // Loan Officer: Do background check
                $loanApplication->update([
                    'loan_officer_id' => $user->id,
                    'background_check_status' => 'passed',
                    'status' => 'under_review',
                ]);

                // Verify KYC documents
                $loanApplication->kycDocuments()->update([
                    'verification_status' => 'verified',
                    'verified_by' => $user->id,
                    'verified_at' => now(),
                ]);

                // Move to next stage
                $loanApplication->moveToNextStage();
                
                AuditLog::log(
                    LoanApplication::class,
                    $loanApplication->id,
                    'loan_officer_approved',
                    "Application {$loanApplication->application_number} approved by Loan Officer. Background check passed."
                );

            } elseif ($approvalLevel === 'credit_officer') {
                // Credit Officer: Set approved amount
                $loanApplication->update([
                    'credit_officer_id' => $user->id,
                    'amount_approved' => $validated['amount_approved'] ?? $loanApplication->amount,
                    'status' => 'under_review',
                ]);

                // Move to next stage
                $loanApplication->moveToNextStage();
                
                AuditLog::log(
                    LoanApplication::class,
                    $loanApplication->id,
                    'credit_officer_approved',
                    "Application {$loanApplication->application_number} approved by Credit Officer. Amount: {$loanApplication->amount_approved}"
                );

            } elseif ($approvalLevel === 'director') {
                // Director: Final approval
                $loanApplication->update([
                    'director_id' => $user->id,
                    'status' => 'approved',
                    'approval_stage' => 'completed',
                    'approved_at' => now(),
                    'amount_approved' => $validated['amount_approved'] ?? $loanApplication->amount_approved ?? $loanApplication->amount,
                ]);

                // Create Loan from approved application
                $loan = \App\Models\Loan::create([
                    'client_id' => $loanApplication->client_id,
                    'loan_type' => $loanApplication->loan_type,
                    'amount_requested' => $loanApplication->amount,
                    'amount_approved' => $loanApplication->amount_approved,
                    'term_months' => $loanApplication->duration_months,
                    'interest_rate' => $loanApplication->interest_rate,
                    'repayment_frequency' => 'monthly', // Default
                    'status' => 'approved',
                ]);

                $loanApplication->update(['loan_id' => $loan->id]);
                
                AuditLog::log(
                    LoanApplication::class,
                    $loanApplication->id,
                    'director_approved',
                    "Application {$loanApplication->application_number} approved by Director. Loan created."
                );
            }

            DB::commit();

            $stageName = ucfirst(str_replace('_', ' ', $approvalLevel));
            return redirect()->route('approvals.index')
                ->with('success', "Application approved by {$stageName}. Moved to next stage.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve application: ' . $e->getMessage());
        }
    }

    /**
     * Reject application at current stage
     */
    public function reject(Request $request, LoanApplication $loanApplication)
    {
        $user = auth()->user();
        
        if (!$this->canApproveAtStage($user, $loanApplication)) {
            return back()->with('error', 'You do not have permission to reject this application.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $approvalLevel = $loanApplication->approval_stage;

            // Create rejection record
            LoanApproval::create([
                'loan_application_id' => $loanApplication->id,
                'approved_by' => $user->id,
                'approval_level' => $approvalLevel,
                'is_current_level' => true,
                'rejection_reason' => $validated['rejection_reason'],
                'status' => 'rejected',
                'approved_at' => now(),
            ]);

            // Update application
            $loanApplication->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'rejected_at' => now(),
            ]);

            if ($approvalLevel === 'loan_officer') {
                $loanApplication->update(['loan_officer_id' => $user->id]);
            } elseif ($approvalLevel === 'credit_officer') {
                $loanApplication->update(['credit_officer_id' => $user->id]);
            } elseif ($approvalLevel === 'director') {
                $loanApplication->update(['director_id' => $user->id]);
            }

            AuditLog::log(
                LoanApplication::class,
                $loanApplication->id,
                'rejected',
                "Application {$loanApplication->application_number} rejected at {$approvalLevel} stage"
            );

            DB::commit();

            return redirect()->route('approvals.index')
                ->with('success', 'Application rejected successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject application: ' . $e->getMessage());
        }
    }

    /**
     * Check if user can approve at current stage
     */
    private function canApproveAtStage($user, LoanApplication $loanApplication): bool
    {
        $stage = $loanApplication->approval_stage;

        if ($stage === 'loan_officer' && $user->hasRole('Loan Officer')) {
            return true;
        }
        if ($stage === 'credit_officer' && $user->hasRole('Credit Officer')) {
            return $loanApplication->background_check_status === 'passed';
        }
        if ($stage === 'director' && ($user->hasRole('Director') || $user->hasRole('Admin'))) {
            return true;
        }

        return false;
    }
}
