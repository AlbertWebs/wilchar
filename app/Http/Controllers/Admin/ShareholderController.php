<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Shareholder;
use App\Models\ShareholderContribution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShareholderController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $shareholders = Shareholder::withSum('contributions as contributions_total', 'amount')
            ->withCount('contributions')
            ->orderBy('name')
            ->paginate(20);

        if ($request->wantsJson()) {
            return response()->json([
                'shareholders' => $shareholders,
            ]);
        }

        return view('admin.shareholders.index', [
            'shareholders' => $shareholders,
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'shares_owned' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $shareholder = Shareholder::create($validated);

        AuditLog::log(Shareholder::class, $shareholder->id, 'created', "Shareholder {$shareholder->name} added", null, $shareholder->toArray());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Shareholder added successfully.',
                'shareholder' => $shareholder,
            ]);
        }

        return redirect()->route('shareholders.index')->with('success', 'Shareholder added successfully.');
    }

    public function update(Request $request, Shareholder $shareholder): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'shares_owned' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $old = $shareholder->toArray();
        $shareholder->update($validated);

        AuditLog::log(Shareholder::class, $shareholder->id, 'updated', "Shareholder {$shareholder->name} updated", $old, $shareholder->fresh()->toArray());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Shareholder updated successfully.',
                'shareholder' => $shareholder,
            ]);
        }

        return redirect()->route('shareholders.index')->with('success', 'Shareholder updated successfully.');
    }

    public function destroy(Request $request, Shareholder $shareholder): JsonResponse|RedirectResponse
    {
        $name = $shareholder->name;
        $shareholder->delete();

        AuditLog::log(Shareholder::class, $shareholder->id, 'deleted', "Shareholder {$name} deleted");

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Shareholder deleted successfully.',
            ]);
        }

        return redirect()->route('shareholders.index')->with('success', 'Shareholder deleted successfully.');
    }

    public function storeContribution(Request $request, Shareholder $shareholder): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'contribution_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'reference' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $contribution = $shareholder->contributions()->create($validated);

        AuditLog::log(
            ShareholderContribution::class,
            $contribution->id,
            'created',
            "Contribution {$contribution->reference} added for {$shareholder->name}",
            null,
            $contribution->toArray()
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Contribution recorded successfully.',
                'contribution' => $contribution,
                'totals' => [
                    'contributions_total' => $shareholder->contributions()->sum('amount'),
                    'contributions_count' => $shareholder->contributions()->count(),
                ],
            ]);
        }

        return back()->with('success', 'Contribution recorded successfully.');
    }
}

