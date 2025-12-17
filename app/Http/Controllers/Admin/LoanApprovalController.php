<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApprovalEmailLog;
use App\Models\AuditLog;
use App\Models\Disbursement;
use App\Models\Instalment;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Models\LoanApproval;
use App\Models\User;
use App\Notifications\LoanApprovalStageNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class LoanApprovalController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $user = auth()->user();
        $query = LoanApplication::with([
            'client',
            'loanOfficer',
            'creditOfficer',
            'collectionOfficer',
            'financeOfficer',
            'loanProduct',
            'team',
        ])->whereIn('status', ['submitted', 'under_review']);

        if ($user->hasRole('Admin') || $user->can('approvals.view')) {
            if ($request->filled('stage')) {
                $query->where('approval_stage', $request->stage);
            }
        } elseif ($user->hasRole('Loan Officer') || $user->hasRole('Marketer')) {
            $query->where('approval_stage', 'loan_officer');
        } elseif ($user->hasRole('Credit Officer')) {
            $query->where('approval_stage', 'credit_officer');
        } elseif ($user->hasRole('Finance')) {
            $query->where('approval_stage', 'finance_officer');
        } elseif ($user->hasRole('Director')) {
            $query->whereIn('approval_stage', ['finance_officer', 'director']);
        } else {
            // fallback: restrict to none by forcing impossible where
            $query->whereRaw('1 = 0');
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

        // Load email status for each application
        $applications->getCollection()->transform(function ($application) {
            $application->email_status = $this->getEmailStatus($application);
            return $application;
        });

        return view('admin.approvals.index', [
            'applications' => $applications,
        ]);
    }

    /**
     * Manually send email notification to approvers
     */
    public function sendEmail(Request $request, LoanApplication $loanApplication): RedirectResponse|JsonResponse
    {
        try {
            $loanApplication->loadMissing('client', 'team');
            
            // Get recipients for the current stage
            $recipients = $this->getStageRecipients($loanApplication);
            
            if ($recipients->isEmpty()) {
                $message = 'No approvers found for this stage.';
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return back()->with('error', $message);
            }

            // Send notifications synchronously (not queued) so we can catch errors immediately
            $sentCount = 0;
            $errors = [];
            $recipientEmails = [];
            
            foreach ($recipients as $recipient) {
                $recipientEmails[] = $recipient->email;
                try {
                    // Send immediately without queueing
                    $recipient->notifyNow(new LoanApprovalStageNotification($loanApplication, 'approval'));
                    $sentCount++;
                } catch (\Exception $e) {
                    $errorMessage = "Failed to send to {$recipient->email}: " . $e->getMessage();
                    $errors[] = $errorMessage;
                    \Log::error('Failed to send approval email', [
                        'recipient' => $recipient->email,
                        'application_id' => $loanApplication->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            // Store email status in database for persistence
            ApprovalEmailLog::create([
                'loan_application_id' => $loanApplication->id,
                'sent_by' => auth()->id(),
                'sent_count' => $sentCount,
                'total_recipients' => $recipients->count(),
                'recipients' => $recipientEmails,
                'errors' => $errors,
                'sent_at' => now(),
            ]);

            // Also store in session for immediate display
            session()->put("email_sent_{$loanApplication->id}", [
                'sent_at' => now(),
                'sent_count' => $sentCount,
                'total_recipients' => $recipients->count(),
                'errors' => $errors,
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Email sent to {$sentCount} approver(s).",
                    'sent_count' => $sentCount,
                    'total_recipients' => $recipients->count(),
                    'errors' => $errors,
                ]);
            }

            $message = "Email sent successfully to {$sentCount} approver(s).";
            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', $errors);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send email: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Get recipients for the current approval stage
     */
    private function getStageRecipients(LoanApplication $loanApplication): \Illuminate\Support\Collection
    {
        $stage = $loanApplication->approval_stage;

        $roleMap = [
            'loan_officer' => ['Loan Officer', 'Marketer'],
            'credit_officer' => ['Credit Officer'],
            'finance_officer' => ['Finance', 'Director'],
            'director' => ['Director'],
        ];

        $roles = $roleMap[$stage] ?? [];

        if (empty($roles)) {
            return collect([]);
        }

        return User::role($roles)
            ->get()
            ->filter(function ($user) use ($loanApplication) {
                return $user->hasRole('Admin') || $user->can('approvals.view') || $this->canApproveAtStage($user, $loanApplication);
            })
            ->filter(function ($user) {
                return !empty($user->email);
            });
    }

    /**
     * Get email status for an application
     */
    private function getEmailStatus(LoanApplication $loanApplication): array
    {
        // First check session for immediate status
        $statusKey = "email_sent_{$loanApplication->id}";
        $sessionStatus = session()->get($statusKey);

        if ($sessionStatus) {
            return [
                'sent' => true,
                'sent_at' => $sessionStatus['sent_at'],
                'sent_count' => $sessionStatus['sent_count'],
                'total_recipients' => $sessionStatus['total_recipients'],
                'errors' => $sessionStatus['errors'] ?? [],
            ];
        }

        // Check database for persisted status
        $latestLog = ApprovalEmailLog::where('loan_application_id', $loanApplication->id)
            ->latest('sent_at')
            ->first();

        if ($latestLog) {
            return [
                'sent' => true,
                'sent_at' => $latestLog->sent_at,
                'sent_count' => $latestLog->sent_count,
                'total_recipients' => $latestLog->total_recipients,
                'errors' => $latestLog->errors ?? [],
            ];
        }

        return [
            'sent' => false,
            'sent_at' => null,
            'sent_count' => 0,
            'total_recipients' => 0,
            'errors' => [],
        ];
    }

    public function show(Request $request, LoanApplication $loanApplication): View|JsonResponse|RedirectResponse
    {
        $user = auth()->user();

        if (!$this->canViewApplication($user, $loanApplication)) {
            return redirect()->route('approvals.index')
                ->with('error', 'You do not have permission to manage this application at this stage.');
        }

        $loanApplication->load([
            'client',
            'loanOfficer',
            'creditOfficer',
            'collectionOfficer',
            'financeOfficer',
            'loanProduct',
            'team',
            'kycDocuments',
            'approvals.approver',
            'loan.instalments',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'application' => $loanApplication,
            ]);
        }

        return view('admin.approvals.show', [
            'loanApplication' => $loanApplication,
            'canApprove' => $this->canApproveAtStage($user, $loanApplication),
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

        if ($loanApplication->approval_stage === 'credit_officer') {
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
                $loanApplication->refresh();
                $this->notifyStageUsers($loanApplication);

                AuditLog::log(
                    LoanApplication::class,
                    $loanApplication->id,
                    'loan_officer_approved',
                    "Loan Officer {$user->name} approved onboarding & KYC."
                );
            } elseif ($currentStage === 'credit_officer') {
                $loanApplication->update([
                    'credit_officer_id' => $user->id,
                    'amount_approved' => $validated['amount_approved'],
                    'interest_rate' => $validated['interest_rate'],
                    'duration_months' => $validated['duration_months'],
                    'interest_amount' => round(($validated['interest_rate'] / 100) * $validated['amount_approved'] * ($validated['duration_months'] / 12), 2),
                    'total_repayment_amount' => round($validated['amount_approved'] + $loanApplication->registration_fee + round(($validated['interest_rate'] / 100) * $validated['amount_approved'] * ($validated['duration_months'] / 12), 2), 2),
                ]);

                $loanApplication->moveToNextStage();
                $loanApplication->refresh();
                $this->notifyStageUsers($loanApplication);

                AuditLog::log(
                    LoanApplication::class,
                    $loanApplication->id,
                    'credit_officer_approved',
                    "Credit Officer {$user->name} approved and calculated totals."
                );
            } elseif ($currentStage === 'finance_officer') {
                $amountApproved = $validated['amount_approved'];
                $processingFee = $validated['processing_fee'] ?? 0;
                $disbursementMethod = $validated['disbursement_method'];

                $loanApplication->update([
                    'finance_officer_id' => $loanApplication->finance_officer_id ?? $user->id,
                    'amount_approved' => $amountApproved,
                    'status' => 'under_review',
                    'onboarding_data' => array_merge($loanApplication->onboarding_data ?? [], [
                        'pending_disbursement' => [
                            'amount_approved' => $amountApproved,
                            'processing_fee' => $processingFee,
                            'disbursement_method' => $disbursementMethod,
                        ],
                    ]),
                ]);

                $loanApplication->moveToNextStage();
                $loanApplication->refresh();
                $this->notifyStageUsers($loanApplication);

                AuditLog::log(
                    LoanApplication::class,
                    $loanApplication->id,
                    'finance_officer_approved',
                    "Finance Officer {$user->name} prepared disbursement for KES {$amountApproved}."
                );
            } elseif ($currentStage === 'director') {
                $pending = $loanApplication->onboarding_data['pending_disbursement'] ?? [];
                $amountApproved = $pending['amount_approved'] ?? $loanApplication->amount_approved ?? $validated['amount_approved'] ?? 0;
                $processingFee = $pending['processing_fee'] ?? $validated['processing_fee'] ?? 0;
                $disbursementMethod = $pending['disbursement_method'] ?? $validated['disbursement_method'] ?? 'M-PESA B2C';

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
                    'collection_officer_id' => $loanApplication->collection_officer_id ?? $loanApplication->credit_officer_id,
                    'recovery_officer_id' => null,
                    'finance_officer_id' => $user->id,
                    'processing_fee' => $processingFee,
                    'late_fee_accrued' => 0,
                    'next_due_date' => now()->addMonth(),
                ]);

                $loanApplication->update(['loan_id' => $loan->id]);
                $loanApplication->approval_stage = 'completed';
                $loanApplication->status = 'approved';
                $loanApplication->approved_at = now();
                $meta = $loanApplication->onboarding_data ?? [];
                unset($meta['pending_disbursement']);
                $loanApplication->onboarding_data = $meta;
                $loanApplication->save();

                $this->generateInstalments($loan);

                Disbursement::create([
                    'loan_application_id' => $loanApplication->id,
                    'disbursed_by' => $user->id,
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                    'amount' => $amountApproved,
                    'transaction_amount' => $amountApproved - $processingFee,
                    'processing_fee' => $processingFee,
                    'method' => $disbursementMethod,
                    'disbursement_date' => now(),
                    'status' => 'pending',
                    'processing_notes' => $validated['comment'] ?? null,
                ]);

                AuditLog::log(
                    LoanApplication::class,
                    $loanApplication->id,
                    'director_approved',
                    "Director {$user->name} approved and released disbursement of KES {$amountApproved}."
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

            // Notify relevant users about the rejection
            $this->notifyRejection($loanApplication, $stage);

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

    private function notifyStageUsers(LoanApplication $loanApplication): void
    {
        $stage = $loanApplication->approval_stage;

        $roleMap = [
            'loan_officer' => ['Loan Officer', 'Marketer'],
            'credit_officer' => ['Credit Officer'],
            'finance_officer' => ['Finance', 'Director'],
            'director' => ['Director'],
        ];

        $roles = $roleMap[$stage] ?? [];

        if (empty($roles)) {
            return;
        }

        // Get users with the required roles who also have permission to view approvals
        $recipients = User::role($roles)
            ->get()
            ->filter(function ($user) use ($loanApplication) {
                // Check if user has Admin role, approvals.view permission, or can approve at this stage
                return $user->hasRole('Admin') || $user->can('approvals.view') || $this->canApproveAtStage($user, $loanApplication);
            });

        if ($recipients->isEmpty()) {
            return;
        }

        $loanApplication->loadMissing('client', 'team');

        Notification::send($recipients, new LoanApprovalStageNotification($loanApplication, 'approval'));
    }

    private function notifyRejection(LoanApplication $loanApplication, string $rejectedAtStage): void
    {
        // Notify Admin users and users with approvals.view permission
        $recipients = User::where(function ($query) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'Admin');
            })->orWhereHas('permissions', function ($q) {
                $q->where('name', 'approvals.view');
            });
        })->get();

        // Also notify the next approver in the workflow if application wasn't completed
        if ($rejectedAtStage !== 'completed') {
            $nextStage = $this->getNextStage($rejectedAtStage);
            if ($nextStage) {
                $roleMap = [
                    'loan_officer' => ['Loan Officer', 'Marketer'],
                    'credit_officer' => ['Credit Officer'],
                    'finance_officer' => ['Finance', 'Director'],
                    'director' => ['Director'],
                ];

                $nextRoles = $roleMap[$nextStage] ?? [];
                if (!empty($nextRoles)) {
                    $nextApprovers = User::role($nextRoles)
                        ->where(function ($query) {
                            $query->whereHas('roles', function ($q) {
                                $q->where('name', 'Admin');
                            })->orWhereHas('permissions', function ($q) {
                                $q->where('name', 'approvals.view');
                            });
                        })
                        ->get();
                    
                    $recipients = $recipients->merge($nextApprovers)->unique('id');
                }
            }
        }

        if ($recipients->isEmpty()) {
            return;
        }

        $loanApplication->loadMissing('client', 'team');

        Notification::send($recipients, new LoanApprovalStageNotification($loanApplication, 'rejection'));
    }

    private function getNextStage(string $currentStage): ?string
    {
        $stages = ['loan_officer', 'credit_officer', 'finance_officer', 'director', 'completed'];
        $currentIndex = array_search($currentStage, $stages);
        
        if ($currentIndex !== false && $currentIndex < count($stages) - 1) {
            return $stages[$currentIndex + 1];
        }
        
        return null;
    }

    private function canApproveAtStage($user, LoanApplication $loanApplication): bool
    {
        // Admin has all rights and permissions to approve at any stage
        if ($user->hasRole('Admin')) {
            return true;
        }

        return match ($loanApplication->approval_stage) {
            'loan_officer' => $user->hasRole('Loan Officer') || $user->hasRole('Marketer'),
            'credit_officer' => $user->hasRole('Credit Officer'),
            'finance_officer' => $user->hasRole('Finance') || $user->hasRole('Director'),
            'director' => $user->hasRole('Director'),
            default => false,
        };
    }

    private function canViewApplication($user, LoanApplication $loanApplication): bool
    {
        if ($user->hasRole('Admin') || $user->can('approvals.view')) {
            return true;
        }

        return $this->canApproveAtStage($user, $loanApplication);
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
