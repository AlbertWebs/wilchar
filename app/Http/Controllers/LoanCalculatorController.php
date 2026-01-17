<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoanCalculatorController extends Controller
{
    public function calculate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'duration' => 'required|numeric|in:1,2,3,4',
            'loan_cycle' => 'required|in:first,repeat',
        ]);

        $loanAmount = $request->amount;
        $duration = (int) $request->duration; // 1-4 weeks
        $loanCycle = $request->loan_cycle; // first or repeat

        // Application Fee Logic
        $applicationFee = $loanCycle === 'first' ? 500 : 350;
        $disbursedAmount = $loanAmount - $applicationFee;

        // Interest Calculation
        if ($duration == 1) {
            // 1 Week: 10% flat
            $interest = $loanAmount * 0.10;
            $interestRate = 10;
        } else {
            // 2-4 Weeks: 7.8% per week
            $interest = $loanAmount * 0.078 * $duration;
            $interestRate = 7.8;
        }

        // Total Payable (based on FULL loan amount, not reduced amount)
        $totalPayable = $loanAmount + $interest;

        // Weekly Installment
        $weeklyInstallment = $totalPayable / $duration;

        // Format currency
        $formatCurrency = function($amount) {
            return number_format($amount, 2);
        };

        return response()->json([
            'loan_amount' => $formatCurrency($loanAmount),
            'application_fee' => $formatCurrency($applicationFee),
            'disbursed_amount' => $formatCurrency($disbursedAmount),
            'interest' => $formatCurrency($interest),
            'interest_rate' => $interestRate,
            'total_payable' => $formatCurrency($totalPayable),
            'weekly_installment' => $formatCurrency($weeklyInstallment),
            'duration' => $duration,
            'loan_cycle' => $loanCycle,
            'loan_cycle_label' => $loanCycle === 'first' ? 'First Loan' : 'Repeat Loan',
        ]);
    }
}
