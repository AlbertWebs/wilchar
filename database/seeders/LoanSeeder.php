<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Loan;
use App\Models\Client;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $clients = Client::all();

        foreach ($clients as $client) {
            $loan = Loan::create([
                'client_id' => $client->id,
                'loan_type' => 'Business',
                'amount_requested' => rand(50000, 150000),
                'amount_approved' => rand(40000, 120000),
                'term_months' => 12,
                'interest_rate' => 12.5,
                'repayment_frequency' => 'monthly',
                'status' => 'approved'
            ]);

            // Add dummy approval
            \App\Models\LoanApproval::create([
                'loan_id' => $loan->id,
                'approved_by' => 3, // assuming user id 3 is Approver
                'level' => 1,
                'status' => 'approved',
                'approved_at' => now()
            ]);

            // Add disbursement
            \App\Models\Disbursement::create([
                'loan_id' => $loan->id,
                'disbursed_by' => 4, // assuming user id 4 is Accountant
                'amount' => $loan->amount_approved,
                'method' => 'Mpesa',
                'disbursement_date' => now(),
                'status' => 'success'
            ]);

            // Add repayment
            \App\Models\Repayment::create([
                'loan_id' => $loan->id,
                'amount' => $loan->amount_approved / 4,
                'payment_method' => 'Mpesa',
                'paid_at' => now()->subDays(5),
                'reference' => 'TX' . rand(1000, 9999),
                'received_by' => 4
            ]);
        }
    }
}
