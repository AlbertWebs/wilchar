<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Repayment;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Repayment::with(['loan.client', 'receiver']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('loan.client', function ($clientQuery) use ($search) {
                    $clientQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                })->orWhere('reference', 'like', "%{$search}%");
            });
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('paid_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('paid_at', '<=', $request->end_date);
        }

        $repayments = $query->orderBy('paid_at', 'desc')->paginate(15);

        // Statistics
        $stats = [
            'total_collected' => Repayment::sum('amount'),
            'this_month' => Repayment::whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount'),
            'total_count' => Repayment::count(),
            'by_payment_method' => Repayment::select('payment_method', DB::raw('SUM(amount) as total'))
                ->groupBy('payment_method')
                ->get(),
        ];

        return view('admin.collections.index', compact('repayments', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $loans = Loan::with('client')->where('status', 'disbursed')->get();
        return view('admin.collections.create', compact('loans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'paid_at' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'receipt_url' => 'nullable|url',
        ]);

        $validated['received_by'] = auth()->id();

        Repayment::create($validated);

        return redirect()->route('collections.index')
            ->with('success', 'Collection recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Repayment $collection)
    {
        $collection->load(['loan.client', 'receiver']);
        return view('admin.collections.show', compact('collection'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Repayment $collection)
    {
        $loans = Loan::with('client')->where('status', 'disbursed')->get();
        return view('admin.collections.edit', compact('collection', 'loans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Repayment $collection)
    {
        $validated = $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'paid_at' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'receipt_url' => 'nullable|url',
        ]);

        $collection->update($validated);

        return redirect()->route('collections.index')
            ->with('success', 'Collection updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Repayment $collection)
    {
        $collection->delete();

        return redirect()->route('collections.index')
            ->with('success', 'Collection deleted successfully.');
    }
}
