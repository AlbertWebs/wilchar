<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PublicLoanApplicationController extends Controller
{
    public function create()
    {
        $loanProducts = \App\Models\LoanProduct::where('is_active', true)->orderBy('name')->get();
        $repaymentFrequencies = $this->repaymentFrequencyOptions();

        return view('front.loan-application-form', [
            'loanProducts' => $loanProducts,
            'repaymentFrequencies' => $repaymentFrequencies,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Client fields
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'mpesa_phone' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'id_number' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'occupation' => 'nullable|string|max:255',
            
            // Loan application fields
            'loan_product_id' => 'nullable|exists:loan_products,id',
            'business_type' => 'required|string|max:255',
            'business_location' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000|max:10000000',
            'interest_rate' => 'nullable|numeric|min:0|max:100',
            'interest_rate_type' => 'required|in:monthly,annual,frequency',
            'repayment_frequency' => ['required', Rule::in(array_keys($this->repaymentFrequencyOptions()))],
            'repayment_interval_weeks' => 'required|integer|min:1',
            'duration_months' => 'required|integer|min:1|max:120',
            'registration_fee' => 'nullable|numeric|min:0|max:1000000',
            'purpose' => 'nullable|string|max:1000',
            
            // File uploads
            'loan_form' => 'nullable|array',
            'loan_form.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            'mpesa_statement' => 'nullable|array',
            'mpesa_statement.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            'business_photo' => 'nullable|array',
            'business_photo.*' => 'image|max:5120',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();

        try {
            // Check if client exists by phone or id_number
            $client = Client::where('phone', $validated['phone'])
                ->orWhere('id_number', $validated['id_number'])
                ->first();

            if (!$client) {
                // Create new client
                $client = Client::create([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'middle_name' => $validated['middle_name'] ?? null,
                    'phone' => $validated['phone'],
                    'mpesa_phone' => $validated['mpesa_phone'] ?? $validated['phone'],
                    'alternate_phone' => $validated['alternate_phone'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'id_number' => $validated['id_number'],
                    'date_of_birth' => $validated['date_of_birth'] ?? null,
                    'gender' => $validated['gender'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'occupation' => $validated['occupation'] ?? null,
                    'business_type' => $validated['business_type'],
                    'location' => $validated['business_location'],
                    'status' => 'active',
                ]);
            } else {
                // Update existing client information if needed
                $client->update([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'middle_name' => $validated['middle_name'] ?? $client->middle_name,
                    'mpesa_phone' => $validated['mpesa_phone'] ?? $client->mpesa_phone,
                    'alternate_phone' => $validated['alternate_phone'] ?? $client->alternate_phone,
                    'email' => $validated['email'] ?? $client->email,
                    'address' => $validated['address'] ?? $client->address,
                    'occupation' => $validated['occupation'] ?? $client->occupation,
                    'business_type' => $validated['business_type'],
                    'location' => $validated['business_location'],
                ]);
            }

            // Calculate loan details (similar to admin controller)
            $loanProduct = null;
            if (!empty($validated['loan_product_id'])) {
                $loanProduct = LoanProduct::find($validated['loan_product_id']);
            }

            $frequencyOptions = $this->repaymentFrequencyOptions();
            $selectedFrequency = $frequencyOptions[$validated['repayment_frequency']] ?? null;
            if (!$selectedFrequency) {
                throw new \RuntimeException('Invalid repayment frequency selected.');
            }

            $interestRate = $selectedFrequency['rate'];
            $interestRateType = 'frequency';

            $totalWeeks = $this->calculateTotalWeeks($validated['duration_months']);
            $intervalCount = $this->calculateIntervalCount($totalWeeks, $selectedFrequency['weeks']);

            $interestAmount = round(($interestRate / 100) * $validated['amount'] * $intervalCount, 2);
            $registrationFee = $validated['registration_fee'] ?? 0;
            $totalRepayment = round($validated['amount'] + $interestAmount + $registrationFee, 2);
            $weeklyPayment = $this->calculateWeeklyPayment($totalRepayment, $totalWeeks);
            $cyclePayment = $this->calculateCyclePayment($totalRepayment, $intervalCount);

            // Handle file uploads
            $filePaths = $this->handleFileUploads($request);

            // Create loan application
            $application = LoanApplication::create([
                'client_id' => $client->id,
                'loan_product_id' => $validated['loan_product_id'] ?? null,
                'loan_type' => $validated['loan_type'] ?? ($loanProduct?->name ?? 'Standard Loan'),
                'business_type' => $validated['business_type'],
                'business_location' => $validated['business_location'],
                'amount' => $validated['amount'],
                'interest_rate' => $interestRate,
                'interest_rate_type' => $interestRateType,
                'interest_amount' => $interestAmount,
                'total_repayment_amount' => $totalRepayment,
                'weekly_payment_amount' => $weeklyPayment,
                'repayment_cycle_amount' => $cyclePayment,
                'repayment_frequency' => $validated['repayment_frequency'],
                'repayment_interval_weeks' => $selectedFrequency['weeks'],
                'duration_months' => $validated['duration_months'],
                'registration_fee' => $registrationFee,
                'purpose' => $validated['purpose'] ?? null,
                'status' => 'submitted',
                'approval_stage' => 'pending',
                'loan_form_path' => $filePaths['loan_form_path'] ?? null,
                'mpesa_statement_path' => $filePaths['mpesa_statement_path'] ?? null,
                'business_photo_path' => $filePaths['business_photo_path'] ?? null,
            ]);

            // Store additional files as KYC documents
            if (!empty($filePaths['loan_form_files'])) {
                $this->storeKycDocuments($application, $filePaths['loan_form_files'], 'loan_form');
            }
            if (!empty($filePaths['mpesa_statement_files'])) {
                $this->storeKycDocuments($application, $filePaths['mpesa_statement_files'], 'mpesa_statement');
            }
            if (!empty($filePaths['business_photo_files'])) {
                $this->storeKycDocuments($application, $filePaths['business_photo_files'], 'business_photo');
            }
            if (!empty($filePaths['documents'])) {
                $this->storeKycDocuments($application, $filePaths['documents'], 'document');
            }

            DB::commit();

            return redirect()->route('loan-application.thank-you')
                ->with('success', 'Your loan application has been submitted successfully! Application Number: ' . $application->application_number);
        } catch (\Throwable $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Failed to submit application: ' . $e->getMessage());
        }
    }

    private function repaymentFrequencyOptions(): array
    {
        return [
            'weekly' => ['label' => 'Weekly', 'rate' => 2.5, 'weeks' => 1],
            'biweekly' => ['label' => 'Bi-Weekly (Every 2 weeks)', 'rate' => 5, 'weeks' => 2],
            'monthly' => ['label' => 'Monthly (Every 4 weeks)', 'rate' => 10, 'weeks' => 4],
            'quarterly' => ['label' => 'Quarterly (Every 12 weeks)', 'rate' => 30, 'weeks' => 12],
        ];
    }

    private function calculateTotalWeeks(int $durationMonths): int
    {
        return max(1, (int) ceil($durationMonths * 4));
    }

    private function calculateIntervalCount(int $totalWeeks, int $intervalWeeks): int
    {
        return max(1, (int) ceil($totalWeeks / $intervalWeeks));
    }

    private function calculateWeeklyPayment(float $totalRepayment, int $totalWeeks): float
    {
        return round($totalRepayment / $totalWeeks, 2);
    }

    private function calculateCyclePayment(float $totalRepayment, int $intervalCount): float
    {
        return round($totalRepayment / $intervalCount, 2);
    }

    private function handleFileUploads(Request $request): array
    {
        $paths = [];

        if ($request->hasFile('loan_form')) {
            [$primary, $files] = $this->storeUploadedFileGroup($request->file('loan_form'), 'loan-applications/forms');
            if ($primary) {
                $paths['loan_form_path'] = $primary;
            }
            $paths['loan_form_files'] = $files;
        }

        if ($request->hasFile('mpesa_statement')) {
            [$primary, $files] = $this->storeUploadedFileGroup($request->file('mpesa_statement'), 'loan-applications/statements');
            if ($primary) {
                $paths['mpesa_statement_path'] = $primary;
            }
            $paths['mpesa_statement_files'] = $files;
        }

        if ($request->hasFile('business_photo')) {
            [$primary, $files] = $this->storeUploadedFileGroup($request->file('business_photo'), 'loan-applications/business-photos');
            if ($primary) {
                $paths['business_photo_path'] = $primary;
            }
            $paths['business_photo_files'] = $files;
        }

        if ($request->hasFile('documents')) {
            $files = [];
            foreach ($request->file('documents') as $file) {
                $path = $file->store('loan-applications/documents', 'public');
                $files[] = $path;
            }
            $paths['documents'] = $files;
        }

        return $paths;
    }

    private function storeUploadedFileGroup(array $files, string $directory): array
    {
        $primary = null;
        $allFiles = [];

        foreach ($files as $index => $file) {
            $path = $file->store($directory, 'public');
            $allFiles[] = $path;
            if ($index === 0) {
                $primary = $path;
            }
        }

        return [$primary, $allFiles];
    }

    private function storeKycDocuments(LoanApplication $application, array $filePaths, string $documentType): void
    {
        foreach ($filePaths as $filePath) {
            $fileInfo = Storage::disk('public')->exists($filePath) 
                ? Storage::disk('public')->getMetadata($filePath)
                : null;

            \App\Models\KycDocument::create([
                'loan_application_id' => $application->id,
                'document_type' => $documentType,
                'file_path' => $filePath,
                'file_name' => basename($filePath),
                'file_type' => $fileInfo['type'] ?? 'application/octet-stream',
                'file_size' => $fileInfo['size'] ?? 0,
            ]);
        }
    }

    public function thankYou()
    {
        return view('front.loan-application-thank-you');
    }
}
