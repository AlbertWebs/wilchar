<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Client Model
use App\Models\Client;
use App\Models\Loan;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Loan::with('client');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('client', function ($clientQuery) use ($search) {
                    $clientQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })->orWhere('loan_type', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default to showing approved, disbursed, and closed loans
            $query->whereIn('status', ['approved', 'disbursed', 'closed']);
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        return view('admin.loans.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
   
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'loan_type' => 'required|string|max:255',
            'amount_requested' => 'required|numeric|min:0',
            'amount_approved' => 'nullable|numeric|min:0',
            'term_months' => 'required|integer|min:1',
            'interest_rate' => 'required|numeric|min:0',
            'repayment_frequency' => 'required|string|max:255',
            'status' => 'required|in:pending,approved,rejected,disbursed,closed'
        ]);

        Loan::create($request->all());
        return redirect()->route('loans.index')->with('success', 'Loan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan->load(['client', 'disbursements', 'repayments']);
        return view('admin.loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        $clients = Client::all();
        return view('admin.loans.edit', compact('loan', 'clients'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
        {
            $request->validate([
                'client_id' => 'required|exists:clients,id',
                'loan_type' => 'required|string|max:255',
                'amount_requested' => 'required|numeric|min:0',
                'amount_approved' => 'nullable|numeric|min:0',
                'term_months' => 'required|integer|min:1',
                'interest_rate' => 'required|numeric|min:0',
                'repayment_frequency' => 'required|string|max:255',
                'status' => 'required|in:pending,approved,rejected,disbursed,closed'
            ]);

            $loan->update($request->all());
            return redirect()->route('loans.index')->with('success', 'Loan updated successfully.');
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Loan deleted successfully.');
    }
}
