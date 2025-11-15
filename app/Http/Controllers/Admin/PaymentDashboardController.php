<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\StkPush;
use App\Models\C2bTransaction;
use App\Services\LoanPaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentDashboardController extends Controller
{
    public function __construct(private LoanPaymentService $loanPaymentService)
    {
    }

    public function index(Request $request): View
    {
        $stkPushes = StkPush::with('loan')->latest()->paginate(10, ['*'], 'stk_page');
        $c2bTransactions = C2bTransaction::with('loan')->latest()->paginate(10, ['*'], 'c2b_page');

        $recentLoans = Loan::latest()->take(25)->get(['id', 'client_id', 'amount_approved', 'outstanding_balance']);

        return view('admin.payments.index', [
            'stkPushes' => $stkPushes,
            'c2bTransactions' => $c2bTransactions,
            'recentLoans' => $recentLoans,
        ]);
    }

    public function attach(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:stk,c2b',
            'payment_id' => 'required|integer',
            'loan_id' => 'required|exists:loans,id',
        ]);

        $loan = Loan::findOrFail($validated['loan_id']);

        if ($validated['type'] === 'stk') {
            $payment = StkPush::findOrFail($validated['payment_id']);

            $payment->loan()->associate($loan);
            $payment->save();

            if (!$payment->applied_at && $payment->status === 'success') {
                $this->loanPaymentService->applyPaymentToLoan($loan, (float) $payment->amount, 'stk', $payment->mpesa_receipt_number);
                $payment->update(['applied_at' => now()]);
            }
        } else {
            $payment = C2bTransaction::findOrFail($validated['payment_id']);

            $payment->loan()->associate($loan);
            $payment->save();

            if (!$payment->applied_at && $payment->status === 'completed') {
                $this->loanPaymentService->applyPaymentToLoan($loan, (float) $payment->trans_amount, 'c2b', $payment->trans_id);
                $payment->update(['applied_at' => now()]);
            }
        }

        return back()->with('success', 'Payment linked to loan and balance updated.');
    }
}

