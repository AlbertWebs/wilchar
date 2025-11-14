<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AuditLog;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $assets = Asset::with('team')->orderByDesc('purchase_date')->paginate(15);

        if ($request->wantsJson()) {
            return response()->json([
                'assets' => $assets,
            ]);
        }

        $teams = Team::orderBy('name')->get();

        return view('admin.assets.index', [
            'assets' => $assets,
            'teams' => $teams,
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'required|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0',
            'depreciation_method' => 'required|in:straight_line,declining_balance,none',
            'useful_life_months' => 'nullable|integer|min:0',
            'residual_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'assigned_team_id' => 'nullable|exists:teams,id',
        ]);

        $monthlyDepreciation = 0;
        if (($validated['depreciation_method'] ?? 'none') !== 'none' && !empty($validated['useful_life_months'])) {
            $baseValue = $validated['purchase_price'] - ($validated['residual_value'] ?? 0);
            $monthlyDepreciation = $baseValue > 0
                ? round($baseValue / $validated['useful_life_months'], 2)
                : 0;
        }

        $asset = Asset::create(array_merge($validated, [
            'monthly_depreciation' => $monthlyDepreciation,
            'current_value' => $validated['current_value'] ?? $validated['purchase_price'],
        ]));

        AuditLog::log(Asset::class, $asset->id, 'created', "Asset {$asset->name} recorded", null, $asset->toArray());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Asset added successfully.',
                'asset' => $asset->load('team'),
            ]);
        }

        return redirect()->route('assets.index')->with('success', 'Asset added successfully.');
    }

    public function update(Request $request, Asset $asset): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'required|numeric|min:0',
            'current_value' => 'nullable|numeric|min:0',
            'depreciation_method' => 'required|in:straight_line,declining_balance,none',
            'useful_life_months' => 'nullable|integer|min:0',
            'residual_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'assigned_team_id' => 'nullable|exists:teams,id',
        ]);

        $monthlyDepreciation = $asset->monthly_depreciation;
        if (($validated['depreciation_method'] ?? 'none') !== 'none' && !empty($validated['useful_life_months'])) {
            $baseValue = $validated['purchase_price'] - ($validated['residual_value'] ?? 0);
            $monthlyDepreciation = $baseValue > 0
                ? round($baseValue / $validated['useful_life_months'], 2)
                : 0;
        } else {
            $monthlyDepreciation = 0;
        }

        $old = $asset->toArray();
        $asset->update(array_merge($validated, [
            'monthly_depreciation' => $monthlyDepreciation,
            'current_value' => $validated['current_value'] ?? $asset->current_value,
        ]));

        AuditLog::log(Asset::class, $asset->id, 'updated', "Asset {$asset->name} updated", $old, $asset->fresh()->toArray());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Asset updated successfully.',
                'asset' => $asset->load('team'),
            ]);
        }

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy(Request $request, Asset $asset): JsonResponse|RedirectResponse
    {
        $assetName = $asset->name;
        $asset->delete();

        AuditLog::log(Asset::class, $asset->id, 'deleted', "Asset {$assetName} deleted");

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Asset deleted successfully.',
            ]);
        }

        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }
}

