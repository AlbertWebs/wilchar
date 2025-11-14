<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoanProduct;
use Illuminate\Validation\Rule;

class LoanProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loanProducts = LoanProduct::latest()->paginate(10);

        return view('admin.loan-products.index', compact('loanProducts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.loan-products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        LoanProduct::create($validated);

        return redirect()->route('loan-products.index')
            ->with('success', 'Loan product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoanProduct $loanProduct)
    {
        return view('admin.loan-products.edit', compact('loanProduct'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoanProduct $loanProduct)
    {
        $validated = $this->validateProduct($request, $loanProduct->id);

        $loanProduct->update($validated);

        return redirect()->route('loan-products.index')
            ->with('success', 'Loan product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoanProduct $loanProduct)
    {
        $loanProduct->delete();

        return redirect()->route('loan-products.index')
            ->with('success', 'Loan product deleted successfully.');
    }

    private function validateProduct(Request $request, ?int $productId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('loan_products', 'name')->ignore($productId)],
            'description' => ['nullable', 'string', 'max:500'],
            'base_interest_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'interest_rate_per_month' => ['required', 'numeric', 'min:0', 'max:100'],
            'processing_fee_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'min_amount' => ['nullable', 'numeric', 'min:0'],
            'max_amount' => ['nullable', 'numeric', 'min:0'],
            'min_duration_months' => ['required', 'integer', 'min:1', 'max:120'],
            'max_duration_months' => ['required', 'integer', 'min:1', 'max:120'],
            'is_active' => ['boolean'],
        ]);
    }
}
