<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Loan;
use App\Models\LoanProduct;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = Loan::with(['client', 'loanProduct', 'team', 'collectionOfficer'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('team_id'), fn($q) => $q->where('team_id', $request->team_id))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($inner) use ($search) {
                    $inner->where('loan_type', 'like', "%{$search}%")
                        ->orWhereHas('client', function ($clientQuery) use ($search) {
                            $clientQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            });

        $loans = $query->orderByDesc('created_at')->paginate(15);

        if ($request->wantsJson()) {
            return response()->json([
                'loans' => $loans,
            ]);
        }

        $portfolioSummary = [
            'portfolio_value' => Loan::sum('total_amount'),
            'outstanding_balance' => Loan::sum('outstanding_balance'),
            'active_loans' => Loan::whereIn('status', ['approved', 'disbursed'])->count(),
            'overdue_balance' => Loan::where('outstanding_balance', '>', 0)
                ->whereNotNull('next_due_date')
                ->whereDate('next_due_date', '<', now())
                ->sum('outstanding_balance'),
        ];

        return view('admin.loans.index', [
            'loans' => $loans,
            'teams' => Team::orderBy('name')->get(),
            'loanProducts' => LoanProduct::orderBy('name')->get(),
            'portfolioSummary' => $portfolioSummary,
        ]);
    }

    public function show(Loan $loan): View
    {
        $loan->load([
            'client',
            'loanProduct',
            'team',
            'disbursements',
            'repayments',
            'instalments' => fn($q) => $q->orderBy('due_date'),
        ]);

        return view('admin.loans.show', [
            'loan' => $loan,
        ]);
    }

    public function update(Request $request, Loan $loan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,disbursed,closed',
            'collection_officer_id' => 'nullable|exists:users,id',
            'recovery_officer_id' => 'nullable|exists:users,id',
            'finance_officer_id' => 'nullable|exists:users,id',
            'next_due_date' => 'nullable|date',
        ]);

        $loan->update($validated);

        return redirect()->route('loans.show', $loan)->with('success', 'Loan updated successfully.');
    }
}
