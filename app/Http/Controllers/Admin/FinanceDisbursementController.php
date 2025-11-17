<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Disbursement;
use App\Models\LoanApplication;
use App\Models\MpesaAccountBalance;
use App\Models\User;
use App\Notifications\DisbursementOtpNotification;
use App\Services\B2cPaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class FinanceDisbursementController extends Controller
{
    public function __construct(private B2cPaymentService $b2cPaymentService)
    {
    }

    public function index(): View
    {
        $balances = MpesaAccountBalance::latest()->first();
        $cashAccount = Account::where('type', 'cash')->first();

        $pendingApplications = LoanApplication::with('client')
            ->where('approval_stage', 'finance_officer')
            ->where('status', 'under_review')
            ->latest()
            ->get();

        $awaitingDirector = Disbursement::with(['loanApplication.client', 'preparedBy'])
            ->where('status', 'awaiting_director')
            ->latest()
            ->get();

        return view('admin.finance.disbursements.index', [
            'balances' => $balances,
            'cashAccount' => $cashAccount,
            'pendingApplications' => $pendingApplications,
            'awaitingDirector' => $awaitingDirector,
        ]);
    }

    public function prepare(Request $request): RedirectResponse
    {
        \App\Helpers\PermissionHelper::requireRole('Finance', 'Only Finance users can prepare disbursements.');

        $validated = $request->validate([
            'loan_application_id' => 'required|exists:loan_applications,id',
            'amount' => 'required|numeric|min:1',
            'recipient_phone' => 'required|string|regex:/^254[0-9]{9}$/',
            'remarks' => 'nullable|string|max:255',
        ]);

        $loanApplication = LoanApplication::with('client')->findOrFail($validated['loan_application_id']);

        if ($loanApplication->approval_stage !== 'finance_officer') {
            return back()->with('error', 'Application is not at the finance stage.');
        }

        if ($loanApplication->disbursements()->whereIn('status', ['pending', 'awaiting_director', 'success'])->exists()) {
            return back()->with('error', 'A disbursement already exists for this application.');
        }

        $otp = (string) random_int(100000, 999999);

        DB::transaction(function () use ($validated, $loanApplication, $otp) {
            $disbursement = Disbursement::create([
                'loan_application_id' => $loanApplication->id,
                'prepared_by' => auth()->id(),
                'amount' => $validated['amount'],
                'method' => 'mpesa_b2c',
                'recipient_phone' => $validated['recipient_phone'],
                'processing_notes' => $validated['remarks'] ?? null,
                'status' => 'awaiting_director',
                'otp_code_hash' => Hash::make($otp),
                'otp_expires_at' => now()->addMinutes(10),
                'otp_attempts' => 0,
                'otp_sent_at' => now(),
            ]);

            $directors = User::role('Director')->get();
            if ($directors->isNotEmpty()) {
                $disbursement->load('loanApplication');
                Notification::send($directors, new DisbursementOtpNotification($disbursement, $otp));
                foreach ($directors as $director) {
                    if (!empty($director->phone)) {
                        Log::info('Queued SMS OTP for director', [
                            'director_id' => $director->id,
                            'phone' => $director->phone,
                            'disbursement_id' => $disbursement->id,
                        ]);
                    }
                }
            } else {
                Log::warning('No directors found to send disbursement OTP.');
            }
        });

        return back()->with('success', 'Disbursement prepared and OTP sent to directors.');
    }

    public function showConfirm(Disbursement $disbursement): View|RedirectResponse
    {
        \App\Helpers\PermissionHelper::requireRole('Director', 'Only Directors can approve disbursements.');

        if ($disbursement->status !== 'awaiting_director') {
            return redirect()->route('finance-disbursements.index')->with('error', 'This disbursement is not pending director approval.');
        }

        $disbursement->load(['loanApplication.client', 'preparedBy']);

        return view('admin.finance.disbursements.confirm', compact('disbursement'));
    }

    public function confirm(Request $request, Disbursement $disbursement): RedirectResponse
    {
        \App\Helpers\PermissionHelper::requireRole('Director', 'Only Directors can approve disbursements.');

        $request->validate([
            'otp' => 'required|string',
        ]);

        if ($disbursement->status !== 'awaiting_director') {
            return back()->with('error', 'This disbursement is not awaiting director approval.');
        }

        if ($disbursement->otp_expires_at && now()->greaterThan($disbursement->otp_expires_at)) {
            return back()->with('error', 'OTP has expired. Please ask finance to generate a new one.');
        }

        if (!Hash::check($request->otp, $disbursement->otp_code_hash ?? '')) {
            $disbursement->increment('otp_attempts');
            return back()->with('error', 'Invalid OTP. Please try again.');
        }

        DB::beginTransaction();
        try {
            $disbursement->update([
                'otp_verified_at' => now(),
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'status' => 'pending',
                'disbursement_date' => now(),
            ]);

            $result = $this->b2cPaymentService->initiate($disbursement, $disbursement->processing_notes ?? '');

            if ($result['success']) {
                $disbursement->update([
                    'mpesa_request_id' => $result['request_id'] ?? null,
                    'mpesa_response_code' => $result['response_code'] ?? null,
                    'mpesa_response_description' => $result['response_description'] ?? null,
                    'mpesa_originator_conversation_id' => $result['originator_conversation_id'] ?? null,
                ]);

                DB::commit();

                return redirect()->route('finance-disbursements.index')
                    ->with('success', 'OTP verified. M-Pesa disbursement initiated.');
            }

            $disbursement->update([
                'status' => 'failed',
                'mpesa_response_description' => $result['error'] ?? 'Failed to initiate payment',
            ]);

            DB::rollBack();

            return back()->with('error', 'Failed to initiate M-Pesa B2C: ' . ($result['error'] ?? 'Unknown error'));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Finance Disbursement OTP Confirm Error: ' . $e->getMessage());

            return back()->with('error', 'Failed to process disbursement: ' . $e->getMessage());
        }
    }
}

