<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LoanApplication;
use App\Models\Loan;
use App\Models\LoanApproval;
use App\Models\Disbursement;
use App\Models\Repayment;
use App\Models\KycDocument;
use App\Models\Client;
use App\Models\User;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $clients = Client::all();
        
        if ($clients->isEmpty()) {
            $this->command->warn('No clients found. Please run ClientSeeder first.');
            return;
        }

        $loanOfficer = User::whereHas('roles', function($q) {
            $q->where('name', 'Loan Officer');
        })->first();

        $creditOfficer = User::whereHas('roles', function($q) {
            $q->where('name', 'Credit Officer');
        })->first();

        $director = User::whereHas('roles', function($q) {
            $q->where('name', 'Director');
        })->first();

        $accountant = User::whereHas('roles', function($q) {
            $q->where('name', 'Accountant');
        })->first();

        $loanTypes = ['business', 'personal', 'agriculture', 'emergency', 'education'];
        $statuses = ['submitted', 'under_review', 'approved', 'disbursed'];

        foreach ($clients->take(3) as $index => $client) {
            // Create Loan Application
            $loanApplication = LoanApplication::create([
                'client_id' => $client->id,
                'loan_type' => $loanTypes[array_rand($loanTypes)],
                'amount' => rand(50000, 300000),
                'interest_rate' => 12.0 + (rand(0, 5) / 10), // 12.0 to 12.5
                'duration_months' => [6, 12, 18, 24][array_rand([6, 12, 18, 24])],
                'purpose' => 'Business expansion and working capital',
                'status' => $statuses[$index] ?? 'submitted',
                'approval_stage' => $index === 0 ? 'loan_officer' : ($index === 1 ? 'credit_officer' : ($index === 2 ? 'director' : 'completed')),
                'background_check_status' => $index > 0 ? 'passed' : 'pending',
                'created_by' => $loanOfficer->id ?? 1,
                'loan_officer_id' => $loanOfficer->id ?? null,
                'credit_officer_id' => $index > 1 ? ($creditOfficer->id ?? null) : null,
                'director_id' => $index > 2 ? ($director->id ?? null) : null,
                'amount_approved' => $index > 1 ? rand(40000, 250000) : null,
            ]);

            // Add KYC Documents for approved applications
            if ($index > 0) {
                KycDocument::create([
                    'loan_application_id' => $loanApplication->id,
                    'document_type' => 'id',
                    'document_name' => 'National ID',
                    'file_path' => 'kyc-documents/sample-id.pdf',
                    'file_type' => 'application/pdf',
                    'file_size' => 102400,
                    'verification_status' => 'verified',
                    'verified_by' => $loanOfficer->id ?? 1,
                    'verified_at' => now(),
                ]);

                KycDocument::create([
                    'loan_application_id' => $loanApplication->id,
                    'document_type' => 'selfie',
                    'document_name' => 'Selfie Photo',
                    'file_path' => 'kyc-documents/sample-selfie.jpg',
                    'file_type' => 'image/jpeg',
                    'file_size' => 51200,
                    'verification_status' => 'verified',
                    'verified_by' => $loanOfficer->id ?? 1,
                    'verified_at' => now(),
                ]);
            }

            // Create Loan if application is approved
            if ($loanApplication->isApproved() || $index > 2) {
                $loan = Loan::create([
                    'client_id' => $client->id,
                    'loan_type' => $loanApplication->loan_type,
                    'amount_requested' => $loanApplication->amount,
                    'amount_approved' => $loanApplication->amount_approved ?? $loanApplication->amount,
                    'term_months' => $loanApplication->duration_months,
                    'interest_rate' => $loanApplication->interest_rate,
                    'repayment_frequency' => 'monthly',
                    'status' => $index > 2 ? 'disbursed' : 'approved',
                ]);

                $loanApplication->update(['loan_id' => $loan->id]);

                // Create approval records
                LoanApproval::create([
                    'loan_application_id' => $loanApplication->id,
                    'approved_by' => $loanOfficer->id ?? 1,
                    'approval_level' => 'loan_officer',
                    'is_current_level' => false,
                    'status' => 'approved',
                    'comment' => 'Background check passed. KYC documents verified.',
                    'approved_at' => now()->subDays(5),
                ]);

                LoanApproval::create([
                    'loan_application_id' => $loanApplication->id,
                    'approved_by' => $creditOfficer->id ?? 2,
                    'approval_level' => 'credit_officer',
                    'previous_level' => 'loan_officer',
                    'is_current_level' => false,
                    'status' => 'approved',
                    'comment' => 'Credit assessment completed. Amount approved.',
                    'approved_at' => now()->subDays(3),
                ]);

                LoanApproval::create([
                    'loan_application_id' => $loanApplication->id,
                    'approved_by' => $director->id ?? 3,
                    'approval_level' => 'director',
                    'previous_level' => 'credit_officer',
                    'is_current_level' => false,
                    'status' => 'approved',
                    'comment' => 'Final approval granted.',
                    'approved_at' => now()->subDays(1),
                ]);

                // Create disbursement for disbursed loans
                if ($index > 2) {
                    Disbursement::create([
                        'loan_application_id' => $loanApplication->id,
                        'disbursed_by' => $accountant->id ?? 4,
                        'amount' => $loan->amount_approved,
                        'method' => 'mpesa_b2c',
                        'disbursement_date' => now()->subDays(1),
                        'recipient_phone' => $client->mpesa_phone ?? $client->phone,
                        'status' => 'success',
                        'transaction_receipt' => 'TX' . strtoupper(uniqid()),
                        'transaction_amount' => $loan->amount_approved,
                    ]);

                    // Create sample repayment
                    Repayment::create([
                        'loan_id' => $loan->id,
                        'amount' => $loan->amount_approved / $loan->term_months,
                        'payment_method' => 'mpesa',
                        'paid_at' => now()->subDays(1),
                        'reference' => 'RX' . strtoupper(uniqid()),
                        'received_by' => $accountant->id ?? 4,
                    ]);
                }
            }
        }
    }
}
