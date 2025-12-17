<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Client;
use App\Models\KycDocument;
use App\Models\LoanApplication;
use App\Models\LoanProduct;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class LoanApplicationController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = LoanApplication::with([
            'client',
            'loanOfficer',
            'creditOfficer',
            'collectionOfficer',
            'financeOfficer',
            'loanProduct',
            'team',
            'loan.disbursements',
            'disbursements',
        ])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('stage')) {
            $query->where('approval_stage', $request->stage);
        }

        if ($request->filled('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        if ($request->filled('loan_officer_id')) {
            $query->where('loan_officer_id', $request->loan_officer_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('application_number', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($clientQuery) use ($search) {
                        $clientQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('id_number', 'like', "%{$search}%");
                    });
            });
        }

        $applications = $query->paginate(15);

        if ($request->wantsJson()) {
            return response()->json([
                'applications' => $applications,
            ]);
        }

        $dependencies = $this->formDependencies();

        return view('admin.loan-applications.index', array_merge($dependencies, [
            'applications' => $applications,
        ]));
    }

    public function create(Request $request): JsonResponse|View
    {
        $data = array_merge($this->formDependencies(), [
            'application' => new LoanApplication(),
            'mode' => 'create',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'html' => view('admin.loan-applications.partials.form', $data)->render(),
            ]);
        }

        return view('admin.loan-applications.create', $data);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'team_id' => 'nullable|exists:teams,id',
            'loan_product_id' => 'nullable|exists:loan_products,id',
            'loan_type' => 'nullable|string|max:255',
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
            'loan_officer_id' => 'nullable|exists:users,id',
            'credit_officer_id' => 'nullable|exists:users,id',
            'collection_officer_id' => 'nullable|exists:users,id',
            'finance_officer_id' => 'nullable|exists:users,id',
            'onboarding_data' => 'nullable|array',
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
            $loanProduct = null;
            if (!empty($validated['loan_product_id'])) {
                $loanProduct = LoanProduct::find($validated['loan_product_id']);
                $validated['loan_type'] = $loanProduct?->name;
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

            $filePaths = $this->handleFileUploads($request);

            $application = LoanApplication::create([
                'client_id' => $validated['client_id'],
                'team_id' => $validated['team_id'] ?? null,
                'loan_product_id' => $validated['loan_product_id'] ?? null,
                'loan_type' => $validated['loan_type'] ?? ($loanProduct?->name ?? 'Standard Loan'),
                'business_type' => $validated['business_type'],
                'business_location' => $validated['business_location'],
                'amount' => $validated['amount'],
                'interest_rate' => $interestRate,
                'interest_rate_type' => $interestRateType,
                'amount_approved' => null,
                'interest_amount' => $interestAmount,
                'total_repayment_amount' => $totalRepayment,
                'weekly_payment_amount' => $weeklyPayment,
                'repayment_cycle_amount' => $cyclePayment,
                'repayment_frequency' => $validated['repayment_frequency'],
                'repayment_interval_weeks' => $selectedFrequency['weeks'],
                'duration_months' => $validated['duration_months'],
                'registration_fee' => $registrationFee,
                'status' => 'submitted',
                'approval_stage' => 'loan_officer',
                'purpose' => $validated['purpose'] ?? null,
                'supporting_documents' => null,
                'loan_form_path' => $filePaths['loan_form_path'] ?? null,
                'mpesa_statement_path' => $filePaths['mpesa_statement_path'] ?? null,
                'business_photo_path' => $filePaths['business_photo_path'] ?? null,
                'onboarding_data' => $validated['onboarding_data'] ?? null,
                'created_by' => auth()->id(),
                'loan_officer_id' => $validated['loan_officer_id'] ?? (auth()->user()?->hasRole('Loan Officer') ? auth()->id() : null),
                'credit_officer_id' => $validated['credit_officer_id'] ?? null,
                'collection_officer_id' => $validated['collection_officer_id'] ?? null,
                'finance_officer_id' => $validated['finance_officer_id'] ?? null,
            ]);

            $this->syncSpecialDocumentUploads($application, $filePaths);

            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $index => $file) {
                    $documentType = $request->input("document_types.{$index}", 'other');
                    $path = $file->store('loan-applications/kyc', 'public');

                    KycDocument::create([
                        'loan_application_id' => $application->id,
                        'document_type' => $documentType,
                        'document_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            AuditLog::log(
                LoanApplication::class,
                $application->id,
                'created',
                "Loan application {$application->application_number} created"
            );

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Loan application created successfully.',
                    'application' => $application->load(['client', 'loanProduct', 'loanOfficer']),
                ]);
            }

            return redirect()->route('loan-applications.show', $application)
                ->with('success', 'Loan application created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Failed to create loan application: ' . $e->getMessage());
        }
    }

    public function show(Request $request, LoanApplication $loanApplication): View|JsonResponse
    {
        $loanApplication->load([
            'client',
            'creator',
            'loanOfficer',
            'collectionOfficer',
            'financeOfficer',
            'kycDocuments',
            'approvals.approver',
            'loanProduct',
            'team',
        ]);

        $auditLogs = AuditLog::where('model_type', LoanApplication::class)
            ->where('model_id', $loanApplication->id)
            ->with('user')
            ->latest()
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'application' => $loanApplication,
                'logs' => $auditLogs,
            ]);
        }

        return view('admin.loan-applications.show', [
            'loanApplication' => $loanApplication,
            'auditLogs' => $auditLogs,
        ]);
    }

    public function edit(Request $request, LoanApplication $loanApplication): JsonResponse|View|RedirectResponse
    {
        if ($loanApplication->status !== 'submitted' || $loanApplication->approval_stage !== 'loan_officer') {
            return redirect()->route('loan-applications.show', $loanApplication)
                ->with('error', 'Application cannot be edited at this stage.');
        }

        $data = array_merge($this->formDependencies(), [
            'application' => $loanApplication,
            'mode' => 'edit',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'html' => view('admin.loan-applications.partials.form', $data)->render(),
            ]);
        }

        return view('admin.loan-applications.edit', $data);
    }

    public function update(Request $request, LoanApplication $loanApplication): JsonResponse|RedirectResponse
    {
        if ($loanApplication->status !== 'submitted' || $loanApplication->approval_stage !== 'loan_officer') {
            return redirect()->route('loan-applications.show', $loanApplication)
                ->with('error', 'Application cannot be edited at this stage.');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'team_id' => 'nullable|exists:teams,id',
            'loan_product_id' => 'nullable|exists:loan_products,id',
            'loan_type' => 'nullable|string|max:255',
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
            'loan_officer_id' => 'nullable|exists:users,id',
            'credit_officer_id' => 'nullable|exists:users,id',
            'collection_officer_id' => 'nullable|exists:users,id',
            'finance_officer_id' => 'nullable|exists:users,id',
            'onboarding_data' => 'nullable|array',
            'loan_form' => 'nullable|array',
            'loan_form.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            'mpesa_statement' => 'nullable|array',
            'mpesa_statement.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            'business_photo' => 'nullable|array',
            'business_photo.*' => 'image|max:5120',
        ]);

        DB::beginTransaction();

        try {
            $loanProduct = null;
            if (!empty($validated['loan_product_id'])) {
                $loanProduct = LoanProduct::find($validated['loan_product_id']);
                $validated['loan_type'] = $loanProduct?->name;
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

            $registrationFee = $validated['registration_fee'] ?? $loanApplication->registration_fee ?? 0;
            $totalRepayment = round($validated['amount'] + $interestAmount + $registrationFee, 2);
            $weeklyPayment = $this->calculateWeeklyPayment($totalRepayment, $totalWeeks);
            $cyclePayment = $this->calculateCyclePayment($totalRepayment, $intervalCount);

            $filePaths = $this->handleFileUploads($request, $loanApplication);

            $oldValues = $loanApplication->toArray();

            $loanApplication->update(array_merge(Arr::except($validated, ['loan_form', 'mpesa_statement', 'business_photo']), [
                'interest_rate' => $interestRate,
                'interest_rate_type' => $interestRateType,
                'interest_amount' => $interestAmount,
                'total_repayment_amount' => $totalRepayment,
                'weekly_payment_amount' => $weeklyPayment,
                'repayment_cycle_amount' => $cyclePayment,
                'repayment_frequency' => $validated['repayment_frequency'],
                'repayment_interval_weeks' => $selectedFrequency['weeks'],
                'loan_form_path' => $filePaths['loan_form_path'] ?? $loanApplication->loan_form_path,
                'mpesa_statement_path' => $filePaths['mpesa_statement_path'] ?? $loanApplication->mpesa_statement_path,
                'business_photo_path' => $filePaths['business_photo_path'] ?? $loanApplication->business_photo_path,
            ]));

            $this->syncSpecialDocumentUploads($loanApplication, $filePaths);

            AuditLog::log(
                LoanApplication::class,
                $loanApplication->id,
                'updated',
                "Loan application {$loanApplication->application_number} updated",
                $oldValues,
                $loanApplication->fresh()->toArray()
            );

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Loan application updated successfully.',
                    'application' => $loanApplication->load(['client', 'loanProduct']),
                ]);
            }

            return redirect()->route('loan-applications.show', $loanApplication)
                ->with('success', 'Loan application updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Failed to update loan application: ' . $e->getMessage());
        }
    }

    public function destroy(LoanApplication $loanApplication): RedirectResponse
    {
        if ($loanApplication->status !== 'submitted') {
            return redirect()->route('loan-applications.show', $loanApplication)
                ->with('error', 'Only submitted applications can be deleted.');
        }

        foreach ($loanApplication->kycDocuments as $document) {
            Storage::disk('public')->delete($document->file_path);
            $document->delete();
        }

        collect([
            $loanApplication->loan_form_path,
            $loanApplication->mpesa_statement_path,
            $loanApplication->business_photo_path,
        ])->filter()->each(fn ($path) => Storage::disk('public')->delete($path));

        $applicationNumber = $loanApplication->application_number;
        $loanApplication->delete();

        return redirect()->route('loan-applications.index')
            ->with('success', "Loan application {$applicationNumber} deleted successfully.");
    }

    public function assignLoanOfficer(Request $request, LoanApplication $loanApplication): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'role' => 'required|in:loan_officer,credit_officer,collection_officer,finance_officer',
            'user_id' => 'required|exists:users,id',
        ]);

        $column = match ($validated['role']) {
            'loan_officer' => 'loan_officer_id',
            'credit_officer' => 'credit_officer_id',
            'collection_officer' => 'collection_officer_id',
            'finance_officer' => 'finance_officer_id',
        };

        $loanApplication->update([$column => $validated['user_id']]);

        AuditLog::log(
            LoanApplication::class,
            $loanApplication->id,
            "{$validated['role']}_assigned",
            ucfirst(str_replace('_', ' ', $validated['role'])) . " assigned to application {$loanApplication->application_number}"
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Officer assigned successfully.',
                'application' => $loanApplication->load(['loanOfficer', 'creditOfficer', 'collectionOfficer', 'financeOfficer']),
            ]);
        }

        return back()->with('success', 'Officer assigned successfully.');
    }

    private function formDependencies(): array
    {
        $usersByRole = fn(string $role) => User::whereHas('roles', fn($q) => $q->where('name', $role))
            ->orderBy('name')
            ->get();

        return [
            'clients' => Client::where('status', 'active')->orderBy('first_name')->get(),
            'teams' => Team::orderBy('name')->get(),
            'loanProducts' => LoanProduct::where('is_active', true)->orderBy('name')->get(),
            'loanOfficers' => $usersByRole('Loan Officer'),
            'creditOfficers' => $usersByRole('Credit Officer'),
            'collectionOfficers' => $usersByRole('Collection Officer'),
            'financeOfficers' => $usersByRole('Finance'),
            'repaymentFrequencies' => $this->repaymentFrequencyOptions(),
        ];
    }

    private function handleFileUploads(Request $request, ?LoanApplication $application = null): array
    {
        $paths = [];

        if ($request->hasFile('loan_form')) {
            [$primary, $files] = $this->storeUploadedFileGroup(
                $request->file('loan_form'),
                'loan-applications/forms',
                $application ? array_filter(Arr::wrap($application->loan_form_path)) : []
            );

            if ($primary) {
                $paths['loan_form_path'] = $primary;
            }

            $paths['loan_form_files'] = $files;
        }

        if ($request->hasFile('mpesa_statement')) {
            [$primary, $files] = $this->storeUploadedFileGroup(
                $request->file('mpesa_statement'),
                'loan-applications/statements',
                $application ? array_filter(Arr::wrap($application->mpesa_statement_path)) : []
            );

            if ($primary) {
                $paths['mpesa_statement_path'] = $primary;
            }

            $paths['mpesa_statement_files'] = $files;
        }

        if ($request->hasFile('business_photo')) {
            [$primary, $files] = $this->storeUploadedFileGroup(
                $request->file('business_photo'),
                'loan-applications/business-photos',
                $application ? array_filter(Arr::wrap($application->business_photo_path)) : []
            );

            if ($primary) {
                $paths['business_photo_path'] = $primary;
            }

            $paths['business_photo_files'] = $files;
        }

        return $paths;
    }

    private function calculateTotalWeeks(int $durationMonths): int
    {
        return max(1, (int) ceil($durationMonths * 4));
    }

    private function calculateIntervalCount(int $totalWeeks, int $intervalWeeks): int
    {
        $intervalWeeks = max(1, $intervalWeeks);
        return max(1, (int) ceil($totalWeeks / $intervalWeeks));
    }

    private function calculateWeeklyPayment(float $totalRepayment, int $totalWeeks): float
    {
        $weeks = max(1, $totalWeeks);
        return round($totalRepayment / $weeks, 2);
    }

    private function calculateCyclePayment(float $totalRepayment, int $cycleCount): float
    {
        $cycles = max(1, $cycleCount);
        return round($totalRepayment / $cycles, 2);
    }

    private function repaymentFrequencyOptions(): array
    {
        return [
            'weekly' => [
                'label' => 'Weekly · 10% per week',
                'weeks' => 1,
                'rate' => 10,
            ],
            'biweekly' => [
                'label' => 'Every 2 Weeks · 20% per cycle',
                'weeks' => 2,
                'rate' => 20,
            ],
            'monthly' => [
                'label' => 'Monthly · 35% per month',
                'weeks' => 4,
                'rate' => 35,
            ],
        ];
    }

    /**
     * @param  \Illuminate\Http\UploadedFile|\Illuminate\Http\UploadedFile[]  $files
     */
    private function storeUploadedFileGroup($files, string $directory, array $existingPaths = []): array
    {
        collect($existingPaths)->filter()->each(fn ($path) => Storage::disk('public')->delete($path));

        $files = Arr::wrap($files);
        $stored = [];

        foreach ($files as $file) {
            $stored[] = [
                'path' => $file->store($directory, 'public'),
                'name' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
            ];
        }

        return [$stored[0]['path'] ?? null, $stored];
    }

    private function syncSpecialDocumentUploads(LoanApplication $application, array $filePaths): void
    {
        $mapping = [
            'loan_form_files' => 'other',
            'mpesa_statement_files' => 'bank_statement',
            'business_photo_files' => 'business_license',
        ];

        foreach ($mapping as $key => $type) {
            if (empty($filePaths[$key] ?? [])) {
                continue;
            }

            $this->deleteDocumentSet($application, $type);

                $additionalFiles = array_slice($filePaths[$key], 1);

            foreach ($additionalFiles as $file) {
                KycDocument::create([
                    'loan_application_id' => $application->id,
                        'document_type' => $type,
                        'document_name' => ucfirst(str_replace('_files', '', $key)) . ' - ' . ($file['name'] ?? basename($file['path'])),
                    'file_path' => $file['path'],
                    'file_type' => $file['mime'] ?? null,
                    'file_size' => $file['size'] ?? null,
                ]);
            }
        }
    }

    private function deleteDocumentSet(LoanApplication $application, string $type): void
    {
        $application->kycDocuments()
            ->where('document_type', $type)
            ->get()
            ->each(function (KycDocument $document) {
                Storage::disk('public')->delete($document->file_path);
                $document->delete();
            });
    }
}
