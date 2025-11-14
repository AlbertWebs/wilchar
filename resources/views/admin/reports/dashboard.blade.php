@extends('layouts.admin', ['title' => 'Reports Dashboard'])

@section('header')
    Reports Dashboard
@endsection

@section('content')
    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Applications</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($stats['total_applications']) }}</p>
                <p class="text-xs text-slate-400">Pending {{ number_format($stats['pending_applications']) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-500">Approved Applications</p>
                <p class="mt-2 text-3xl font-semibold text-emerald-600">{{ number_format($stats['approved_applications']) }}</p>
                <p class="text-xs text-slate-400">Rejected {{ number_format($stats['rejected_applications']) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Disbursed</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">KES {{ number_format($stats['total_disbursed'], 2) }}</p>
                <p class="text-xs text-slate-400">{{ number_format($stats['disbursed_loans']) }} applications disbursed</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Active Clients</p>
                <p class="mt-2 text-3xl font-semibold text-slate-900">{{ number_format($stats['active_clients']) }}</p>
                <p class="text-xs text-slate-400">Pending approvals {{ number_format($stats['pending_approvals']) }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-base font-semibold text-slate-900">Monthly Disbursements</p>
                        <p class="text-sm text-slate-500">Past 6 months · success status only</p>
                    </div>
                </div>
                <div class="mt-6">
                    <canvas id="monthlyDisbursementsChart"></canvas>
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-base font-semibold text-slate-900">Applications by Stage</p>
                <div class="mt-4 space-y-3">
                    @forelse($applicationsByStage as $stage)
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ \Illuminate\Support\Str::headline($stage->approval_stage) }}</p>
                                <p class="text-xs text-slate-400">Awaiting action</p>
                            </div>
                            <span class="text-base font-semibold text-slate-900">{{ $stage->count }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No pending applications.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-base font-semibold text-slate-900">Recent Applications</p>
                    <p class="text-sm text-slate-500">Latest 10 submissions</p>
                </div>
            </div>
            <div class="mt-4 overflow-hidden rounded-xl border border-slate-100">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Application</th>
                            <th class="px-4 py-3 text-left">Client</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Amount</th>
                            <th class="px-4 py-3 text-left">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentApplications as $application)
                            <tr>
                                <td class="px-4 py-3 font-semibold text-slate-900">{{ $application->application_number }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $application->client->full_name ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                                        {{ $application->status === 'approved' ? 'bg-emerald-50 text-emerald-600' : ($application->status === 'rejected' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600') }}">
                                        {{ \Illuminate\Support\Str::headline($application->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-900">KES {{ number_format($application->amount, 2) }}</td>
                                <td class="px-4 py-3 text-slate-500 text-xs">{{ $application->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">
                                    No applications recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('monthlyDisbursementsChart');
            if (!ctx || !window.ChartJS) {
                return;
            }

            const data = @json($monthlyDisbursements);
            const labels = data.map(item => item.month);
            const totals = data.map(item => Number(item.total) || 0);

            new window.ChartJS(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'KES Disbursed',
                            data: totals,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.15)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback(value) {
                                    return 'KES ' + value.toLocaleString();
                                },
                            },
                        },
                    },
                },
            });
        });
    </script>
@endpush

