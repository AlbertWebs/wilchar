@extends('layouts.admin', ['title' => 'Assets'])

@section('header')
    Assets Management
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Add Asset" description="Track organisational assets and depreciation.">
            <form
                action="{{ route('assets.store') }}"
                method="POST"
                x-ajax="{ successMessage: { title: 'Asset Saved' }, onSuccess() { window.location.reload(); } }"
                class="grid gap-4 md:grid-cols-4"
            >
                @csrf
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Asset Name</label>
                    <input type="text" name="name" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Category</label>
                    <input type="text" name="category" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Purchase Date</label>
                    <input type="date" name="purchase_date" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Purchase Price</label>
                    <input type="number" name="purchase_price" step="0.01" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Current Value</label>
                    <input type="number" name="current_value" step="0.01" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Depreciation</label>
                    <select name="depreciation_method" class="mt-1 w-full rounded-xl border-slate-200">
                        <option value="straight_line">Straight Line</option>
                        <option value="declining_balance">Declining Balance</option>
                        <option value="none">None</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Useful Life (months)</label>
                    <input type="number" name="useful_life_months" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Team</label>
                    <select name="assigned_team_id" class="mt-1 w-full rounded-xl border-slate-200">
                        <option value="">Unassigned</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Notes</label>
                    <input type="text" name="notes" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                        Save Asset
                    </button>
                </div>
            </form>
        </x-admin.section>

        <x-admin.section title="Asset Register">
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Asset</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Value</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Depreciation</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Team</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($assets as $asset)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-slate-900">{{ $asset->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $asset->category ?? '—' }}</p>
                                </td>
                                <td class="px-4 py-4 text-slate-700">
                                    Purchase: {{ number_format($asset->purchase_price, 2) }} <br>
                                    Current: {{ number_format($asset->current_value, 2) }}
                                </td>
                                <td class="px-4 py-4 text-slate-700">
                                    {{ ucfirst(str_replace('_', ' ', $asset->depreciation_method)) }} <br>
                                    Monthly: {{ number_format($asset->monthly_depreciation, 2) }}
                                </td>
                                <td class="px-4 py-4 text-slate-700">
                                    {{ $asset->team->name ?? 'Unassigned' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                    {{ $assets->links() }}
                </div>
            </div>
        </x-admin.section>
    </div>
@endsection

