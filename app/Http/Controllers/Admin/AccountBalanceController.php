<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountBalance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountBalanceController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = AccountBalance::with('account')->orderByDesc('balance_date');

        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('balance_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('balance_date', '<=', $request->date_to);
        }

        $balances = $query->paginate(20);

        if ($request->wantsJson()) {
            return response()->json([
                'balances' => $balances,
            ]);
        }

        $accounts = Account::orderBy('name')->get();

        return view('admin.account-balances.index', [
            'balances' => $balances,
            'accounts' => $accounts,
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'balance_date' => 'required|date',
            'opening_balance' => 'required|numeric',
            'closing_balance' => 'required|numeric',
            'credits' => 'nullable|numeric|min:0',
            'debits' => 'nullable|numeric|min:0',
        ]);

        $balance = AccountBalance::updateOrCreate(
            [
                'account_id' => $validated['account_id'],
                'balance_date' => $validated['balance_date'],
            ],
            $validated
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Account balance saved successfully.',
                'balance' => $balance->load('account'),
            ]);
        }

        return redirect()->route('account-balances.index')->with('success', 'Account balance saved successfully.');
    }
}

