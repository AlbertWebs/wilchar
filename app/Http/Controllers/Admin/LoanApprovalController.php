<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Disbursement;
use App\Models\Instalment;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Models\LoanApproval;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoanApprovalController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $user = auth()->user();
        $query = LoanApplication::with([
            'client',
            'loanOfficer',
            'collectionOfficer',
            'financeOfficer',
            'loanProduct',
            'team',
        ])->whereIn('status', ['submitted', 'under_review']);

        if ($user->hasRole('Loan Officer') || $user->hasRole('Marketer')) {
            $query->where('approval_stage', 'loan_officer');
        } elseif ($user->hasRole('Collection Officer')) {
            $query->where('approval_stage', 'collection_officer');
        } elseif ($user->hasRole('Finance')) {
            $query->where('approval_stage', 'finance_officer');
        } else {
            // Admins see everything
            if ($request->filled('stage')) {
                $query->where('approval_stage', $request->stage);
            }
        }

        if ($request->filled('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        $applications = $query->orderByDesc('created_at')->paginate(15);

        if ($request->wantsJson()) {
            return response()->json([
                'applications' => $applications,
            ]);
        }

        return view('admin.approvals.index', [
            'applications' => $applications,
        ]);
    }

    public function show(Request $request, LoanApplication $loanApplication): View|JsonResponse|RedirectResponse
    {
        $user = auth()->user();

        if (!$this->canApproveAtStage($user, $loanApplication)) {
            return redirect()->route('approvals.index')
                ->with('error', 'You do not have permission to manage this application at this stage.');
        }

        $loanApplication->load([
            'client',
            'loanOfficer',
            'collectionOfficer',
            'financeOfficer',
            'loanProduct',
            'team',
            'kycDocuments',
            'approvals.approver',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'application' => $loanApplication,
            ]);
        }

        return view('admin.approvals.show', [
            'loanApplication' => $loanApplication,
        ]);
    }

    public function approve(Request $request, LoanApplication $loanApplication): RedirectResponse|JsonResponse
    {
        $user = auth()->user();

        if (!$this->canApproveAtStage($user, $loanApplication)) {
            return back()->with('error', 'You do not have permission to approve this application.');
        }

        $rules = [
            'comment' => 'nullable|string|max:1000',
        ];

        if ($loanApplication->approval_stage === 'collection_officer') {
            $rules['amount_approved'] = 'required|numeric|min:1000';
            $rules['interest_rate'] = 'required|numeric|min:0|max:100';
            $rules['duration_months'] = 'required|integer|min:1';
        }

        if ($loanApplication->approval_stage === 'finance_officer') {
            $rules['amount_approved'] = 'required|numeric|min:1000';
            $rules['processing_fee'] = 'nullable|numeric|min:0';
            $rules['disbursement_method'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            $currentStage = $loanApplication->approval_stage;

            $loanApplication->approvals()->update(['is_current_level' => false]);

            LoanApproval::create([
                'loan_application_id' => $loanApplication->id,
                'approved_by' => $user->id,
                'approval_level' => $currentStage,
                'previous_level' => $loanApplication->approvals()->latest()->value('approval_level'),
                'is_current_level' => true,
                'comment' => $validated['comment'] ?? null,
                'status' => 'approved',
                'approved_at' => now(),
            ]);

            if ($currentStage === 'loan_officer') {
                $loanApplication->update([
                    'loan_officer_id' => $user->id,
                    'background_check_status' => 'passed',
                    'status' => 'under_review',
                ]);

                $loanApplication->kycDocuments()->update([
                    'verification_status' => 'verified',
                    'verified_by' => $user->id,
                    'verified_at' => now(),
                ]);

                $loanApplication->moveToNextStage();

                AuditLog::log(
                    LoanApplication::class,
                    $loanApplication->id,
                    'loan_officer_approved',
                    "Loan Officer {$user->name} approved onboarding & KYC."
                );
            } elseif ($currentStage === 'collection_officer') {
                $loanApplication->update([
                    'collection_officer_id' => $user->id,
                    'amount_approved' => $validated['amount_approved'],
                    'interest_rate' => $validated['interest_rate'],
                    'duration_months' => $validated['duration_months'],
                    'interest_amount' => round(($validated['interest_rate'] / 100) * $validated['amount_approved'] * ($validated['duration_months'] / 12), 2),
                    'total_repayment_amount' => round($validated['amount_approved'] + $loanApplication->registration_fee + round(($validated['interest_rate'] / 100) * $validated['amount_approved'] * ($validated['duration_months'] / 12), 2), 2),
                ]);

                $loanApplication->moveToNextStage();

                AuditLog::log(
                    LoanApplication::class,
                    $loanApplication->id,
                    'collection_officer_approved',
                    "Collection Officer {$user->name} approved and calculated totals."
                );
            } elseif ($currentStage === 'finance_officer') {
                $amountApproved = $validated['amount_approved'];
                $loanApplication->update([
                    'finance_officer_id' => $user->id,
                    'amount_approved' => $amountApproved,
                    'status' => 'approved',
                    'approval_stage' => 'completed',
                    'approved_at' => now(),
                ]);

                $loan = Loan::create([
                    'client_id' => $loanApplication->client_id,
                    'loan_application_id' => $loanApplication->id,
                    'loan_product_id' => $loanApplication->loan_product_id,
                    'team_id' => $loanApplication->team_id,
                    'loan_type' => $loanApplication->loan_type,
                    'amount_requested' => $loanApplication->amount,
                    'amount_approved' => $amountApproved,
                    'interest_amount' => $loanApplication->interest_amount,
                    'total_amount' => $loanApplication->total_repayment_amount,
                    'outstanding_balance' => $loanApplication->total_repayment_amount,
                    'term_months' => $loanApplication->duration_months,
                    'interest_rate' => $loanApplication->interest_rate,
                    'repayment_frequency' => 'monthly',
                    'status' => 'approved',
                    'collection_officer_id' => $loanApplication->collection_officer_id,
                    'recovery_officer_id' => null,
                    'finance_officer_id' => $user->id,
                    'processing_fee' => $validated['processing_fee'] ?? 0,
                    'late_fee_accrued' => 0,
                    'next_due_date' => now()->addMonth(),
                ]);

                $loanApplication->update(['loan_id' => $loan->id]);

                $this->generateInstalments($loan);

                Disbursement::create([
                    'loan_application_id' => $loanApplication->id,
                    'disbursed_by' => $user->id,
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                    'amount' => $amountApproved,
                    'transaction_amount' => $amountApproved - ($validated['processing_fee'] ?? 0),
                    'processing_fee' => $validated['processing_fee'] ?? 0,
                    'method' => $validated['disbursement_method'],
                    'disbursement_date' => now(),
                    'status' => 'pending',
                    'processing_notes' => $validated['comment'] ?? null,
                ]);

                AuditLog::log(
                    LoanApplication::class,
                    $loanApplication->id,
                    'finance_officer_approved',
                    "Finance approved and prepared disbursement of KES {$amountApproved}."
                );
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Application approved successfully.',
                ]);
            }

            return redirect()->route('approvals.index')->with('success', 'Application approved successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to approve application.',
                    'error' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', 'Failed to approve application: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, LoanApplication $loanApplication): RedirectResponse|JsonResponse
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
            $stage = $loanApplication->approval_stage;

            LoanApproval::create([
                'loan_application_id' => $loanApplication->id,
                'approved_by' => $user->id,
                'approval_level' => $stage,
                'is_current_level' => true,
                'rejection_reason' => $validated['rejection_reason'],
                'status' => 'rejected',
                'approved_at' => now(),
            ]);

            $loanApplication->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'rejected_at' => now(),
            ]);

            AuditLog::log(
                LoanApplication::class,
                $loanApplication->id,
                'rejected',
                "Application {$loanApplication->application_number} rejected at {$stage} stage"
            );

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Application rejected successfully.',
                ]);
            }

            return redirect()->route('approvals.index')->with('success', 'Application rejected successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to reject application.',
                    'error' => $e->getMessage(),
                ], 422);
            }

            return back()->with('error', 'Failed to reject application: ' . $e->getMessage());
        }
    }

    private function canApproveAtStage($user, LoanApplication $loanApplication): bool
    {
        return match ($loanApplication->approval_stage) {
            'loan_officer' => $user->hasRole('Loan Officer') || $user->hasRole('Marketer') || $user->hasRole('Admin'),
            'collection_officer' => $user->hasRole('Collection Officer') || $user->hasRole('Admin'),
            'finance_officer' => $user->hasRole('Finance') || $user->hasRole('Admin'),
            default => false,
        };
    }

    /**
     * Generate equal instalments for a loan.
     */
    private function generateInstalments(Loan $loan): void
    {
        $monthlyPrincipal = round($loan->amount_approved / $loan->term_months, 2);
        $monthlyInterest = round(($loan->interest_amount ?? 0) / $loan->term_months, 2);
        $totalMonthly = round($loan->total_amount / $loan->term_months, 2);

        $schedule = [];
        $nextDue = now()->addMonth();

        for ($i = 0; $i < $loan->term_months; $i++) {
            $schedule[] = [
                'loan_id' => $loan->id,
                'due_date' => $nextDue->copy()->addMonths($i),
                'principal_amount' => $monthlyPrincipal,
                'interest_amount' => $monthlyInterest,
                'total_amount' => $totalMonthly,
                'amount_paid' => 0,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Instalment::insert($schedule);
    }
}
