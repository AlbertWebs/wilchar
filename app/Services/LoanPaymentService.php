<?php

namespace App\Services;

use App\Models\Instalment;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

class LoanPaymentService
{
    public function applyPaymentToLoan(Loan $loan, float $amount, string $source, ?string $reference = null, ?int $receiverId = null)
    {
        return DB::transaction(function () use ($loan, $amount, $source, $reference, $receiverId) {
            $amount = round($amount, 2);
            if ($amount <= 0) {
                return null;
            }

            $payable = min($amount, max(0, $loan->outstanding_balance ?? $loan->total_amount));
            if ($payable <= 0) {
                return null;
            }

            $repayment = $loan->repayments()->create([
                'amount' => $payable,
                'payment_method' => $source,
                'paid_at' => now(),
                'reference' => $reference,
                'received_by' => $receiverId,
            ]);

            $loan->outstanding_balance = max(0, ($loan->outstanding_balance ?? $loan->total_amount) - $payable);
            if ($loan->outstanding_balance == 0) {
                $loan->status = 'closed';
            }
            $loan->save();

            $remaining = $payable;
            $loan->loadMissing('instalments');
            /** @var Instalment $instalment */
            foreach ($loan->instalments()->orderBy('due_date')->get() as $instalment) {
                $due = $instalment->total_amount - $instalment->amount_paid;
                if ($due <= 0) {
                    continue;
                }

                $applied = min($due, $remaining);
                if ($applied <= 0) {
                    break;
                }

                $instalment->amount_paid += $applied;
                if ($instalment->amount_paid >= $instalment->total_amount) {
                    $instalment->status = 'paid';
                    $instalment->paid_at = now();
                }
                $instalment->save();

                $remaining -= $applied;
                if ($remaining <= 0) {
                    break;
                }
            }

            return $repayment;
        });
    }
}

