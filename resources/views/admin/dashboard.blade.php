@extends('layouts.admin', ['title' => 'Admin Dashboard'])

@section('content')
    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <x-admin.stat-card
                title="Active Clients"
                :value="number_format($totalClients)"
            >
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A4 4 0 018 17h8a4 4 0 013.879 2.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                    </svg>
                </x-slot:icon>
            </x-admin.stat-card>
            <x-admin.stat-card
                title="Loan Applications"
                :value="number_format($loanApplications)"
            >
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-6 4h6M5 7h14M5 11h14M5 15h14M5 19h14" />
                    </svg>
                </x-slot:icon>
            </x-admin.stat-card>
            <x-admin.stat-card
                title="Approved Loans"
                :value="number_format($approvedLoans)"
                :trend="$approvalGrowth"
                trend-label="vs last month"
            >
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 12l6 6L20 6" />
                    </svg>
                </x-slot:icon>
            </x-admin.stat-card>
            <x-admin.stat-card
                title="Disbursed Amount"
                :value="number_format($totalDisbursed, 2)"
            >
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.886 0-3.628.93-4.748 2.401L3 12l4.252 1.599C8.372 15.07 10.114 16 12 16s3.628-.93 4.748-2.401L21 12l-4.252-1.599C15.628 8.93 13.886 8 12 8z" />
                    </svg>
                </x-slot:icon>
            </x-admin.stat-card>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <x-admin.section class="lg:col-span-2" title="Disbursements vs Collections" description="Monthly M-PESA movements">
                <canvas id="disbursementCollectionChart" class="h-72 w-full"></canvas>
            </x-admin.section>

            <x-admin.section title="Pending Approvals" description="Applications awaiting action">
                <div class="space-y-4">
                    @foreach($pendingApprovalBreakdown as $stage => $count)
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ ucfirst(str_replace('_', ' ', $stage)) }}</p>
                                <p class="text-xs text-slate-500">Applications in queue</p>
                            </div>
                            <span class="text-lg font-semibold text-slate-900">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </x-admin.section>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <x-admin.section title="Team Performance" description="Onboarding vs Disbursements by team">
                <div class="space-y-4">
                    @foreach($teamStats as $team)
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 px-4 py-3">
                            <div>
                                <p class="text-sm font-medium text-slate-800">{{ $team['name'] }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $team['onboardings'] }} onboardings · {{ $team['disbursements'] }} disbursements
                                </p>
                            </div>
                            <span class="text-xs font-semibold text-emerald-600">
                                {{ number_format($team['collection_rate'], 1) }}% collection
                            </span>
                        </div>
                    @endforeach
                </div>
            </x-admin.section>

            <x-admin.section class="xl:col-span-2" title="Overdue Loans" description="Loans requiring collection & recovery">
                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Client</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Loan</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Outstanding</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Days Overdue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($overdueLoans as $loan)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-slate-800">{{ $loan->client->full_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $loan->client->phone }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">
                                        {{ $loan->loan_type }} · {{ $loan->term_months }} months
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-rose-500">
                                        {{ number_format($loan->outstanding_balance, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-slate-600">
                                        {{ $loan->days_overdue }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">
                                        No overdue loans. Great job!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-admin.section>
        </div>

        <x-admin.section title="Financial Snapshot" description="Expense, assets, liabilities overview">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200/70 bg-slate-900 p-5 text-slate-100 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-400">Monthly Expenses</p>
                    <p class="mt-3 text-2xl font-semibold">KES {{ number_format($financialSummary['expenses'], 2) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Assets Value</p>
                    <p class="mt-3 text-2xl font-semibold text-slate-900">KES {{ number_format($financialSummary['assets'], 2) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Liabilities</p>
                    <p class="mt-3 text-2xl font-semibold text-slate-900">KES {{ number_format($financialSummary['liabilities'], 2) }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Shareholder Contributions</p>
                    <p class="mt-3 text-2xl font-semibold text-slate-900">KES {{ number_format($financialSummary['shareholder_contributions'], 2) }}</p>
                </div>
            </div>
        </x-admin.section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('disbursementCollectionChart');
            if (!ctx) return;

            new ChartJS(ctx, {
                type: 'line',
                data: {
                    labels: @json($months),
                    datasets: [
                        {
                            label: 'Disbursements',
                            data: @json(array_values($disbursements)),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16,185,129,0.08)',
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: 'Collections',
                            data: @json(array_values($collections)),
                            borderColor: '#6366f1',
                            backgroundColor: 'rgba(99,102,241,0.08)',
                            tension: 0.4,
                            fill: true,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                        },
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: value => `KES ${Number(value).toLocaleString()}`,
                            },
                        },
                    },
                },
            });
        });
    </script>
@endpush

