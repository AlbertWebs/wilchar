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
    public function index()
    {
        // Example: pass data to the view
        $loans = \App\Models\Loan::with('client')->get(); // adjust model name
        return view('admin.loan-applications.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        return view('admin.loan-applications.create', compact('clients'));
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
        return redirect()->route('loan-applications.index')->with('success', 'Loan application created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan->load('client');
        return view('admin.loan-applications.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        $clients = Client::all();
        return view('admin.loan-applications.edit', compact('loan', 'clients'));
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
            return redirect()->route('loan-applications.index')->with('success', 'Loan application updated successfully.');
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        $loan->delete();
        return redirect()->route('loan-applications.index')->with('success', 'Loan application deleted successfully.');
    }
}
