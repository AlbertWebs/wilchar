<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoanCalculatorController extends Controller
{
    public function calculate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'year' => 'nullable|numeric|min:1',
            'interest' => 'nullable|numeric|min:0',
        ]);

        $amount = $request->amount;
        $months = $request->year ?? 12; // default 12 months if not entered
        $interestRate = $request->interest ?? 0;

        // Convert annual interest rate (%) to monthly rate
        $monthlyRate = ($interestRate / 100) / 12;

        if ($monthlyRate > 0) {
            // Loan EMI formula: [P * r(1+r)^n] / [(1+r)^n – 1]
            $monthlyPayment = ($amount * $monthlyRate * pow(1 + $monthlyRate, $months)) 
                              / (pow(1 + $monthlyRate, $months) - 1);
        } else {
            // No interest case
            $monthlyPayment = $amount / $months;
        }

        return response()->json([
            'monthly_payment' => round($monthlyPayment, 2),
            'amount' => $amount,
            'months' => $months,
            'interest_rate' => $interestRate
        ]);
    }
}
