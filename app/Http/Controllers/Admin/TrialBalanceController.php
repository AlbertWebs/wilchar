<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AuditLog;
use App\Models\TrialBalance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class TrialBalanceController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $trialBalances = TrialBalance::with('preparer')
            ->withSum('entries as debit_total', 'debit')
            ->withSum('entries as credit_total', 'credit')
            ->orderByDesc('period_end')
            ->paginate(10);

        if ($request->wantsJson()) {
            return response()->json([
                'trial_balances' => $trialBalances,
            ]);
        }

        return view('admin.trial-balances.index', [
            'trialBalances' => $trialBalances,
        ]);
    }

    public function show(TrialBalance $trialBalance, Request $request): View|JsonResponse
    {
        $trialBalance->load(['entries.account', 'preparer']);

        if ($request->wantsJson()) {
            return response()->json([
                'trial_balance' => $trialBalance,
            ]);
        }

        return view('admin.trial-balances.show', [
            'trialBalance' => $trialBalance,
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $start = Carbon::parse($validated['period_start'])->startOfDay();
        $end = Carbon::parse($validated['period_end'])->endOfDay();

        $accounts = Account::with(['transactions' => function ($query) use ($start, $end) {
            $query->whereBetween('created_at', [$start, $end]);
        }])->get();

        $trialBalance = TrialBalance::create([
            'period_start' => $start,
            'period_end' => $end,
            'generated_by' => auth()->id(),
        ]);

        $totalDebits = 0;
        $totalCredits = 0;
        $entries = [];

        foreach ($accounts as $account) {
            $debit = $account->transactions->where('type', 'debit')->sum('amount');
            $credit = $account->transactions->where('type', 'credit')->sum('amount');

            if ($debit == 0 && $credit == 0) {
                continue;
            }

            $entries[] = [
                'trial_balance_id' => $trialBalance->id,
                'account_id' => $account->id,
                'account_name' => $account->name,
                'debit' => $debit,
                'credit' => $credit,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $totalDebits += $debit;
            $totalCredits += $credit;
        }

        $trialBalance->entries()->insert($entries);
        $trialBalance->update([
            'total_debits' => $totalDebits,
            'total_credits' => $totalCredits,
            'snapshot' => [
                'accounts' => count($entries),
                'balance_difference' => round($totalDebits - $totalCredits, 2),
            ],
        ]);

        AuditLog::log(TrialBalance::class, $trialBalance->id, 'created', 'Trial balance generated', null, $trialBalance->toArray());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Trial balance generated successfully.',
                'trial_balance' => $trialBalance->load('entries'),
                'trial_balance_url' => route('trial-balances.show', $trialBalance),
            ]);
        }

        return redirect()->route('trial-balances.index')->with('success', 'Trial balance generated successfully.');
    }

    public function destroy(Request $request, TrialBalance $trialBalance): JsonResponse|RedirectResponse
    {
        $trialBalance->entries()->delete();
        $trialBalance->delete();

        AuditLog::log(TrialBalance::class, $trialBalance->id, 'deleted', 'Trial balance deleted');

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Trial balance deleted successfully.',
            ]);
        }

        return redirect()->route('trial-balances.index')->with('success', 'Trial balance deleted successfully.');
    }
}

