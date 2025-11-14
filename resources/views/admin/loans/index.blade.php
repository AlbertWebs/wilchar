@extends('layouts.admin', ['title' => 'Loan Portfolio'])

@section('header')
    Loan Portfolio
@endsection

@section('content')
    <div x-data="{ filters: { status: '{{ request('status') }}', team_id: '{{ request('team_id') }}' } }" class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-admin.stat-card title="Portfolio Value" :value="'KES ' . number_format($portfolioSummary['portfolio_value'], 2)" />
            <x-admin.stat-card title="Outstanding Balance" :value="'KES ' . number_format($portfolioSummary['outstanding_balance'], 2)" />
            <x-admin.stat-card title="Active Loans" :value="number_format($portfolioSummary['active_loans'])" />
            <x-admin.stat-card title="Overdue Balance" :value="'KES ' . number_format($portfolioSummary['overdue_balance'], 2)" />
        </div>

        <x-admin.section title="Loans" description="Filter loans by status or team.">
            <div class="mb-4 flex flex-wrap items-center gap-3 text-sm">
                <select class="rounded-xl border-slate-200" x-model="filters.status" @change="window.location = '{{ route('loans.index') }}?status=' + filters.status + '&team_id=' + filters.team_id">
                    <option value="">Status: All</option>
                    <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                    <option value="disbursed" @selected(request('status') === 'disbursed')>Disbursed</option>
                    <option value="closed" @selected(request('status') === 'closed')>Closed</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                </select>
                <select class="rounded-xl border-slate-200" x-model="filters.team_id" @change="window.location = '{{ route('loans.index') }}?team_id=' + filters.team_id + '&status=' + filters.status">
                    <option value="">Team: All</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" @selected(request('team_id') == $team->id)>{{ $team->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Loan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Client</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Amounts</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($loans as $loan)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-4 py-4 text-slate-700">
                                    <p class="font-semibold text-slate-900">{{ $loan->loan_type }}</p>
                                    <p class="text-xs text-slate-500">{{ $loan->loanProduct->name ?? 'Custom Product' }}</p>
                                    <p class="text-xs text-slate-400">{{ $loan->team->name ?? 'Unassigned' }}</p>
                                </td>
                                <td class="px-4 py-4 text-slate-700">
                                    {{ $loan->client->full_name }}
                                    <div class="text-xs text-slate-500">{{ $loan->client->phone }}</div>
                                </td>
                                <td class="px-4 py-4 text-slate-700">
                                    Requested: {{ number_format($loan->amount_requested, 2) }} <br>
                                    Outstanding: {{ number_format($loan->outstanding_balance, 2) }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                    <div class="mt-1 text-xs text-slate-500">
                                        Next Due: {{ $loan->next_due_date?->format('d M Y') ?? '—' }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <a href="{{ route('loans.show', $loan) }}" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                    {{ $loans->withQueryString()->links() }}
                </div>
            </div>
        </x-admin.section>
    </div>
@endsection

