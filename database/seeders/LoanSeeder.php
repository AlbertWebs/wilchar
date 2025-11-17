<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LoanApplication;
use App\Models\Loan;
use App\Models\LoanApproval;
use App\Models\Disbursement;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Arr;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::take(3)->get();

        if ($clients->isEmpty()) {
            $this->command->warn('No clients found. Please seed clients first.');
            return;
        }

        $loanOfficer = User::role('Loan Officer')->first();
        $collectionOfficer = User::role('Collection Officer')->first();
        $creditOfficer = User::role('Credit Officer')->first();
        $financeOfficer = User::role('Finance')->first();
        $director = User::role('Director')->first();

        // If any required users don't exist, warn and continue with null values
        if (!$loanOfficer) {
            $this->command->warn('No Loan Officer found. Some loan applications may not have assigned officers.');
        }
        if (!$creditOfficer) {
            $this->command->warn('No Credit Officer found. Some loan applications may not have assigned officers.');
        }
        if (!$financeOfficer) {
            $this->command->warn('No Finance Officer found. Some loan applications may not have assigned officers.');
        }

        foreach ($clients as $index => $client) {
            $amount = Arr::random([75000, 125000, 200000]);
            $duration = Arr::random([6, 12, 18]);
            $interestRate = 12.5;
            $interestAmount = round(($interestRate / 100) * $amount * ($duration / 12), 2);
            $totalRepayment = $amount + $interestAmount + 2500;

            $application = LoanApplication::create([
                'client_id' => $client->id,
                'loan_type' => 'SME Working Capital',
                'business_type' => 'Retail',
                'business_location' => 'Nairobi',
                'amount' => $amount,
                'interest_rate' => $interestRate,
                'duration_months' => $duration,
                'registration_fee' => 2500,
                'status' => $index === 2 ? 'approved' : 'submitted',
                'approval_stage' => match ($index) {
                    0 => 'loan_officer',
                    1 => 'credit_officer',
                    default => 'completed',
                },
                'background_check_status' => $index > 0 ? 'passed' : 'pending',
                'created_by' => $loanOfficer?->id,
                'loan_officer_id' => $loanOfficer?->id,
                'credit_officer_id' => $creditOfficer?->id,
                'collection_officer_id' => $collectionOfficer?->id,
                'finance_officer_id' => $financeOfficer?->id,
                'interest_amount' => $interestAmount,
                'total_repayment_amount' => $totalRepayment,
                'amount_approved' => $index === 2 ? $amount : null,
            ]);

            if ($index === 2) {
                $loan = Loan::create([
                    'client_id' => $client->id,
                    'loan_application_id' => $application->id,
                    'loan_type' => $application->loan_type,
                    'amount_requested' => $amount,
                    'amount_approved' => $amount,
                    'interest_amount' => $interestAmount,
                    'total_amount' => $totalRepayment,
                    'outstanding_balance' => $totalRepayment,
                    'term_months' => $duration,
                    'interest_rate' => $interestRate,
                    'repayment_frequency' => 'monthly',
                    'status' => 'approved',
                    'collection_officer_id' => $collectionOfficer?->id,
                    'finance_officer_id' => $financeOfficer?->id,
                    'processing_fee' => 2500,
                    'next_due_date' => now()->addMonth(),
                ]);

                LoanApproval::create([
                    'loan_application_id' => $application->id,
                    'approved_by' => $loanOfficer?->id,
                    'approval_level' => 'loan_officer',
                    'status' => 'approved',
                    'is_current_level' => false,
                    'approved_at' => now()->subDays(5),
                ]);

                LoanApproval::create([
                    'loan_application_id' => $application->id,
                    'approved_by' => $creditOfficer?->id,
                    'approval_level' => 'credit_officer',
                    'previous_level' => 'loan_officer',
                    'status' => 'approved',
                    'is_current_level' => false,
                    'approved_at' => now()->subDays(3),
                ]);

                LoanApproval::create([
                    'loan_application_id' => $application->id,
                    'approved_by' => $financeOfficer?->id,
                    'approval_level' => 'finance_officer',
                    'previous_level' => 'credit_officer',
                    'status' => 'approved',
                    'is_current_level' => false,
                    'approved_at' => now()->subDay(),
                ]);

                LoanApproval::create([
                    'loan_application_id' => $application->id,
                    'approved_by' => $director?->id ?? $financeOfficer?->id,
                    'approval_level' => 'director',
                    'previous_level' => 'finance_officer',
                    'status' => 'approved',
                    'is_current_level' => false,
                    'approved_at' => now(),
                ]);

                Disbursement::create([
                    'loan_application_id' => $application->id,
                    'disbursed_by' => $financeOfficer?->id,
                    'approved_by' => $director?->id ?? $financeOfficer?->id,
                    'approved_at' => now()->subDay(),
                    'amount' => $amount,
                    'transaction_amount' => $amount - 2500,
                    'processing_fee' => 2500,
                    'method' => 'mpesa_b2c',
                    'disbursement_date' => now()->subDay(),
                    'status' => 'pending',
                    'recipient_phone' => $client->mpesa_phone ?? $client->phone,
                ]);
            }
        }
    }
}
