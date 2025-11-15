@extends('layouts.admin', ['title' => 'Loan Applications'])

@section('header')
    Loan Applications
@endsection

@section('content')
    <div
        x-data="{
            filters: {
                status: '{{ request('status') }}',
                stage: '{{ request('stage') }}',
                team_id: '{{ request('team_id') }}',
            },
            init() {
                window.addEventListener('loan-applications:refresh', () => {
                    window.location.reload();
                });
            },
            openEditModal(id) {
                const urlTemplate = '{{ route('loan-applications.edit', ['loan_application' => '__ID__']) }}';
                const url = urlTemplate.replace('__ID__', id);
                Admin.showModal({ title: 'Edit Loan Application', url, method: 'get', size: 'xl' });
            }
        }"
        class="space-y-6"
        x-ref="loanApplicationsPage"
    >
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-sm text-slate-600">
                <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2">
                    <span class="text-xs uppercase tracking-wide text-slate-500">Total</span>
                    <span class="font-semibold text-slate-900">{{ number_format($applications->total()) }}</span>
                </div>
                <div class="hidden md:block h-6 w-px bg-slate-200"></div>
                <div class="flex flex-wrap gap-2">
                    <select class="rounded-xl border-slate-200 text-sm" x-model="filters.stage" @change="window.location = '{{ route('loan-applications.index') }}?stage=' + filters.stage">
                        <option value="">Stage: All</option>
                        <option value="loan_officer" @selected(request('stage') === 'loan_officer')>Loan Officer</option>
                        <option value="credit_officer" @selected(request('stage') === 'credit_officer')>Credit Officer</option>
                        <option value="finance_officer" @selected(request('stage') === 'finance_officer')>Finance Officer</option>
                        <option value="director" @selected(request('stage') === 'director')>Director</option>
                        <option value="completed" @selected(request('stage') === 'completed')>Completed</option>
                    </select>
                    <select class="rounded-xl border-slate-200 text-sm" x-model="filters.status" @change="window.location = '{{ route('loan-applications.index') }}?status=' + filters.status">
                        <option value="">Status: All</option>
                        <option value="submitted" @selected(request('status') === 'submitted')>Submitted</option>
                        <option value="under_review" @selected(request('status') === 'under_review')>Under Review</option>
                        <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                    </select>
                </div>
            </div>
            <a
                href="{{ route('loan-applications.create') }}"
                class="flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-400"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                New Application
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Application</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Team & Officers</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Stage</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($applications as $application)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-4">
                                <div class="font-semibold text-slate-900">{{ $application->application_number }}</div>
                                <div class="text-xs text-slate-500">
                                    {{ $application->client->full_name }} · {{ $application->client->phone }}
                                </div>
                                <div class="mt-1 text-xs text-slate-400">
                                    Submitted {{ $application->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-4 py-4 text-slate-600">
                                <p class="font-medium">{{ $application->team->name ?? '—' }}</p>
                                <p class="text-xs text-slate-500">
                                    Loan Officer: {{ $application->loanOfficer->name ?? 'Pending' }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    Credit Officer: {{ $application->creditOfficer->name ?? 'Pending' }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    Collections: {{ $application->collectionOfficer->name ?? 'Pending' }}
                                </p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-semibold text-slate-900">KES {{ number_format($application->amount, 2) }}</p>
                                <p class="text-xs text-slate-500">Interest {{ number_format($application->interest_rate, 2) }}%</p>
                                <p class="text-xs text-slate-500">Total {{ number_format($application->total_repayment_amount ?? 0, 2) }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                    {{ ucfirst(str_replace('_', ' ', $application->approval_stage)) }}
                                </span>
                                <span
                                    class="ml-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                        {{ $application->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($application->status === 'rejected' ? 'bg-rose-100 text-rose-600' : 'bg-amber-100 text-amber-700') }}"
                                >
                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('loan-applications.show', $application) }}" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                        View
                                    </a>
                                    @if($application->approval_stage === 'loan_officer' && $application->status === 'submitted')
                                        <button @click="openEditModal({{ $application->id }})" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                            Edit
                                        </button>
                                    @endif
                                    <a href="{{ route('approvals.show', $application) }}" class="rounded-lg bg-emerald-500 px-3 py-1 text-xs font-semibold text-white hover:bg-emerald-600">
                                        Approve
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">
                                No applications found. Start by creating a new loan application.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                {{ $applications->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection

