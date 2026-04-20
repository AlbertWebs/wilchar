<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\C2bTransaction;
use App\Models\Loan;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use App\Models\StkPush;
use App\Models\Team;
use App\Models\User;
use App\Notifications\LoanDeletionOtpNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = Loan::with(['client', 'loanProduct', 'team', 'collectionOfficer'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('team_id'), fn($q) => $q->where('team_id', $request->team_id))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($inner) use ($search) {
                    $inner->where('loan_type', 'like', "%{$search}%")
                        ->orWhereHas('client', function ($clientQuery) use ($search) {
                            $clientQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            });

        $loans = $query->orderByDesc('created_at')->paginate(15);

        if ($request->wantsJson()) {
            return response()->json([
                'loans' => $loans,
            ]);
        }

        $portfolioSummary = [
            'portfolio_value' => Loan::sum('total_amount'),
            'outstanding_balance' => Loan::sum('outstanding_balance'),
            'active_loans' => Loan::whereIn('status', ['approved', 'disbursed'])->count(),
            'overdue_balance' => Loan::where('outstanding_balance', '>', 0)
                ->whereNotNull('next_due_date')
                ->whereDate('next_due_date', '<', now())
                ->sum('outstanding_balance'),
        ];

        return view('admin.loans.index', [
            'loans' => $loans,
            'teams' => Team::orderBy('name')->get(),
            'loanProducts' => LoanProduct::orderBy('name')->get(),
            'portfolioSummary' => $portfolioSummary,
        ]);
    }

    public function show(Loan $loan): View
    {
        $loan->load([
            'client',
            'loanProduct',
            'team',
            'collectionOfficer',
            'recoveryOfficer',
            'financeOfficer',
            'application',
            'disbursements.disburser',
            'disbursements.preparedBy',
            'repayments' => fn($q) => $q->orderBy('paid_at', 'desc'),
            'repayments.receiver',
            'instalments' => fn($q) => $q->orderBy('due_date'),
            'approvals.approver',
        ]);

        // Calculate loan statistics
        $totalPaid = $loan->repayments->sum('amount');
        $totalDisbursed = $loan->disbursements->where('status', 'success')->sum('amount');
        $paidInstalments = $loan->instalments->where('status', 'paid')->count();
        $totalInstalments = $loan->instalments->count();
        $overdueInstalments = $loan->instalments->where('status', 'overdue')->count();
        $nextInstalment = $loan->instalments->where('status', 'pending')->sortBy('due_date')->first();

        return view('admin.loans.show', [
            'loan' => $loan,
            'totalPaid' => $totalPaid,
            'totalDisbursed' => $totalDisbursed,
            'paidInstalments' => $paidInstalments,
            'totalInstalments' => $totalInstalments,
            'overdueInstalments' => $overdueInstalments,
            'nextInstalment' => $nextInstalment,
        ]);
    }

    public function update(Request $request, Loan $loan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,disbursed,closed',
            'collection_officer_id' => 'nullable|exists:users,id',
            'recovery_officer_id' => 'nullable|exists:users,id',
            'finance_officer_id' => 'nullable|exists:users,id',
            'next_due_date' => 'nullable|date',
        ]);

        $loan->update($validated);

        return redirect()->route('loans.show', $loan)->with('success', 'Loan updated successfully.');
    }

    /**
     * Email a one-time code to the authenticated user to confirm loan deletion.
     */
    public function sendDeleteOtp(Request $request, Loan $loan): RedirectResponse
    {
        $user = $request->user();
        $otp = (string) random_int(100000, 999999);
        $cacheKey = $this->loanDeleteOtpCacheKey($user, $loan);

        Cache::put($cacheKey, Hash::make($otp), now()->addMinutes(10));

        $user->notify(new LoanDeletionOtpNotification($loan->loadMissing('client'), $otp));

        return back()->with('success', 'A 6-digit verification code was sent to your email. It expires in 10 minutes.');
    }

    /**
     * Permanently delete a loan after OTP verification. Related rows (repayments, instalments, etc.) are removed.
     */
    public function destroy(Request $request, Loan $loan): RedirectResponse
    {
        $validated = $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = $request->user();
        $cacheKey = $this->loanDeleteOtpCacheKey($user, $loan);
        $hash = Cache::get($cacheKey);

        if (! $hash || ! Hash::check($validated['otp'], $hash)) {
            return back()->withErrors(['otp' => 'Invalid or expired code. Request a new verification email and try again.']);
        }

        Cache::forget($cacheKey);

        $deletedId = $loan->id;
        $this->deleteLoanAndRelatedRecords($loan, $user);

        return redirect()->route('loans.index')->with('success', 'Loan #' . $deletedId . ' was permanently deleted.');
    }

    private function loanDeleteOtpCacheKey(User $user, Loan $loan): string
    {
        return 'loan_delete_otp:' . $user->id . ':' . $loan->id;
    }

    private function deleteLoanAndRelatedRecords(Loan $loan, User $actor): void
    {
        DB::transaction(function () use ($loan, $actor) {
            $loan->loadMissing('client');
            $loanId = $loan->id;

            $oldValues = [
                'loan_id' => $loanId,
                'loan_application_id' => $loan->loan_application_id,
                'client_id' => $loan->client_id,
                'client_name' => $loan->client?->full_name,
                'status' => $loan->status,
                'total_amount' => (string) $loan->total_amount,
                'outstanding_balance' => (string) $loan->outstanding_balance,
            ];

            $description = sprintf(
                'Loan ID %d deleted by %s (%s) at %s. Client: %s. Application ID: %s.',
                $loanId,
                $actor->name,
                $actor->email,
                now()->toDateTimeString(),
                $loan->client?->full_name ?? '—',
                $loan->loan_application_id ?? '—'
            );

            AuditLog::log(Loan::class, $loanId, 'loan_deleted', $description, $oldValues, null);

            if (Schema::hasColumn('loan_applications', 'loan_id')) {
                LoanApplication::where('loan_id', $loanId)->update(['loan_id' => null]);
            }

            $loan->repayments()->delete();
            $loan->instalments()->delete();
            $loan->collections()->delete();
            $loan->performanceLogs()->delete();
            $loan->transactions()->delete();

            if (Schema::hasColumn((new StkPush)->getTable(), 'loan_id')) {
                StkPush::where('loan_id', $loanId)->update(['loan_id' => null]);
            }

            if (Schema::hasColumn((new C2bTransaction)->getTable(), 'loan_id')) {
                C2bTransaction::where('loan_id', $loanId)->update(['loan_id' => null]);
            }

            $loan->delete();
        });
    }
}
