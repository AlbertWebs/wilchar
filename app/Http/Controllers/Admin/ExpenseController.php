<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AuditLog;
use App\Models\Expense;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = Expense::with(['account', 'team', 'recorder'])->orderByDesc('expense_date');

        if ($request->filled('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        $expenses = $query->paginate(20);

        if ($request->wantsJson()) {
            return response()->json([
                'expenses' => $expenses,
            ]);
        }

        $teams = Team::orderBy('name')->get();
        $accounts = Account::orderBy('name')->get();

        return view('admin.expenses.index', [
            'expenses' => $expenses,
            'teams' => $teams,
            'accounts' => $accounts,
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'account_id' => 'nullable|exists:accounts,id',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        $reference = 'EXP-' . Str::upper(Str::random(8));
        $expense = Expense::create(array_merge($validated, [
            'reference' => $reference,
            'recorded_by' => auth()->id(),
        ]));

        AuditLog::log(Expense::class, $expense->id, 'created', "Expense {$reference} recorded", null, $expense->toArray());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Expense recorded successfully.',
                'expense' => $expense->load(['account', 'team', 'recorder']),
            ]);
        }

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function update(Request $request, Expense $expense): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'account_id' => 'nullable|exists:accounts,id',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        $old = $expense->toArray();
        $expense->update($validated);

        AuditLog::log(Expense::class, $expense->id, 'updated', "Expense {$expense->reference} updated", $old, $expense->fresh()->toArray());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Expense updated successfully.',
                'expense' => $expense->load(['account', 'team', 'recorder']),
            ]);
        }

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Request $request, Expense $expense): JsonResponse|RedirectResponse
    {
        $reference = $expense->reference;
        $expense->delete();

        AuditLog::log(Expense::class, $expense->id, 'deleted', "Expense {$reference} deleted");

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Expense deleted successfully.',
            ]);
        }

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}

