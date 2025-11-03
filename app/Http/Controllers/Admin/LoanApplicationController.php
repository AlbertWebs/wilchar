<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\Client;
use App\Models\KycDocument;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LoanApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LoanApplication::with(['client', 'loanOfficer', 'creditOfficer', 'director', 'creator'])
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by approval stage
        if ($request->has('stage') && $request->stage !== '') {
            $query->where('approval_stage', $request->stage);
        }

        // Filter by loan officer
        if ($request->has('loan_officer') && $request->loan_officer !== '') {
            $query->where('loan_officer_id', $request->loan_officer);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('application_number', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('id_number', 'like', "%{$search}%");
                  });
            });
        }

        $applications = $query->paginate(15);

        return view('admin.loan-applications.index', compact('applications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('first_name')->get();
        return view('admin.loan-applications.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'loan_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000|max:10000000',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'duration_months' => 'required|integer|min:1|max:120',
            'purpose' => 'nullable|string|max:1000',
            'documents' => 'nullable|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        DB::beginTransaction();
        try {
            $application = LoanApplication::create([
                'client_id' => $validated['client_id'],
                'loan_type' => $validated['loan_type'],
                'amount' => $validated['amount'],
                'interest_rate' => $validated['interest_rate'],
                'duration_months' => $validated['duration_months'],
                'purpose' => $validated['purpose'] ?? null,
                'status' => 'submitted',
                'approval_stage' => 'loan_officer',
                'created_by' => auth()->id(),
            ]);

            // Handle KYC documents upload
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $index => $file) {
                    $documentType = $request->input("document_types.{$index}", 'other');
                    $path = $file->store('kyc-documents', 'public');
                    
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

            // Create audit log
            AuditLog::log(
                LoanApplication::class,
                $application->id,
                'created',
                "Loan application {$application->application_number} created"
            );

            DB::commit();

            return redirect()->route('loan-applications.show', $application)
                ->with('success', 'Loan application created successfully. KYC documents uploaded.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create loan application: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LoanApplication $loanApplication)
    {
        $loanApplication->load([
            'client',
            'creator',
            'loanOfficer',
            'creditOfficer',
            'director',
            'kycDocuments',
            'approvals.approver'
        ]);

        $auditLogs = AuditLog::where('model_type', LoanApplication::class)
            ->where('model_id', $loanApplication->id)
            ->with('user')
            ->latest()
            ->get();

        return view('admin.loan-applications.show', compact('loanApplication', 'auditLogs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoanApplication $loanApplication)
    {
        if ($loanApplication->status !== 'submitted' && $loanApplication->approval_stage !== 'loan_officer') {
            return redirect()->route('loan-applications.show', $loanApplication)
                ->with('error', 'Application cannot be edited at this stage.');
        }

        $clients = Client::where('status', 'active')->orderBy('first_name')->get();
        return view('admin.loan-applications.edit', compact('loanApplication', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoanApplication $loanApplication)
    {
        if ($loanApplication->status !== 'submitted' && $loanApplication->approval_stage !== 'loan_officer') {
            return redirect()->route('loan-applications.show', $loanApplication)
                ->with('error', 'Application cannot be edited at this stage.');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'loan_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1000|max:10000000',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'duration_months' => 'required|integer|min:1|max:120',
            'purpose' => 'nullable|string|max:1000',
        ]);

        $oldValues = $loanApplication->toArray();

        $loanApplication->update($validated);

        // Create audit log
        AuditLog::log(
            LoanApplication::class,
            $loanApplication->id,
            'updated',
            "Loan application {$loanApplication->application_number} updated",
            $oldValues,
            $loanApplication->fresh()->toArray()
        );

        return redirect()->route('loan-applications.show', $loanApplication)
            ->with('success', 'Loan application updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoanApplication $loanApplication)
    {
        if ($loanApplication->status !== 'submitted') {
            return redirect()->route('loan-applications.show', $loanApplication)
                ->with('error', 'Only submitted applications can be deleted.');
        }

        // Delete KYC documents
        foreach ($loanApplication->kycDocuments as $document) {
            Storage::disk('public')->delete($document->file_path);
            $document->delete();
        }

        $applicationNumber = $loanApplication->application_number;
        $loanApplication->delete();

        return redirect()->route('loan-applications.index')
            ->with('success', "Loan application {$applicationNumber} deleted successfully.");
    }

    /**
     * Assign loan officer to application
     */
    public function assignLoanOfficer(Request $request, LoanApplication $loanApplication)
    {
        $validated = $request->validate([
            'loan_officer_id' => 'required|exists:users,id',
        ]);

        $loanApplication->update([
            'loan_officer_id' => $validated['loan_officer_id'],
        ]);

        AuditLog::log(
            LoanApplication::class,
            $loanApplication->id,
            'loan_officer_assigned',
            "Loan officer assigned to application {$loanApplication->application_number}"
        );

        return back()->with('success', 'Loan officer assigned successfully.');
    }
}


