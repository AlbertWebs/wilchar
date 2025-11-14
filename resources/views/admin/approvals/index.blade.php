@extends('layouts.admin', ['title' => 'Approvals'])

@section('header')
    Loan Approvals
@endsection

@section('content')
    <x-admin.section
        title="Applications Awaiting Action"
        description="Review and progress applications through the workflow"
    >
        <div class="overflow-hidden rounded-2xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Application</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Stage</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($applications as $application)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-4">
                                <div class="font-semibold text-slate-900">{{ $application->application_number }}</div>
                                <div class="text-xs text-slate-500">{{ $application->loan_type }}</div>
                            </td>
                            <td class="px-4 py-4 text-slate-700">
                                {{ $application->client->full_name }}
                                <div class="text-xs text-slate-500">{{ $application->client->phone }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    {{ ucfirst(str_replace('_', ' ', $application->approval_stage)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-slate-700">
                                KES {{ number_format($application->amount, 2) }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('approvals.show', $application) }}" class="rounded-lg bg-emerald-500 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-600">
                                    Review & Approve
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">
                                No applications are pending your approval right now.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                {{ $applications->links() }}
            </div>
        </div>
    </x-admin.section>
@endsection

