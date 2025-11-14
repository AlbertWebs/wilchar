<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Liability;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LiabilityController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $query = Liability::with('team')->orderByDesc('due_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        $liabilities = $query->paginate(15);

        if ($request->wantsJson()) {
            return response()->json([
                'liabilities' => $liabilities,
            ]);
        }

        $teams = Team::orderBy('name')->get();

        return view('admin.liabilities.index', [
            'liabilities' => $liabilities,
            'teams' => $teams,
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'creditor' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'outstanding_balance' => 'nullable|numeric|min:0',
            'issued_on' => 'nullable|date',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,active,settled,overdue',
            'notes' => 'nullable|string',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        $liability = Liability::create(array_merge($validated, [
            'outstanding_balance' => $validated['outstanding_balance'] ?? $validated['amount'],
        ]));

        AuditLog::log(Liability::class, $liability->id, 'created', "Liability {$liability->name} recorded", null, $liability->toArray());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Liability recorded successfully.',
                'liability' => $liability->load('team'),
            ]);
        }

        return redirect()->route('liabilities.index')->with('success', 'Liability recorded successfully.');
    }

    public function update(Request $request, Liability $liability): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'creditor' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'outstanding_balance' => 'nullable|numeric|min:0',
            'issued_on' => 'nullable|date',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,active,settled,overdue',
            'notes' => 'nullable|string',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        $old = $liability->toArray();
        $liability->update($validated);

        AuditLog::log(Liability::class, $liability->id, 'updated', "Liability {$liability->name} updated", $old, $liability->fresh()->toArray());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Liability updated successfully.',
                'liability' => $liability->load('team'),
            ]);
        }

        return redirect()->route('liabilities.index')->with('success', 'Liability updated successfully.');
    }

    public function destroy(Request $request, Liability $liability): JsonResponse|RedirectResponse
    {
        $name = $liability->name;
        $liability->delete();

        AuditLog::log(Liability::class, $liability->id, 'deleted', "Liability {$name} deleted");

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Liability deleted successfully.',
            ]);
        }

        return redirect()->route('liabilities.index')->with('success', 'Liability deleted successfully.');
    }
}

