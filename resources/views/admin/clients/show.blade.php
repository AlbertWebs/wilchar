@extends('layouts.admin', ['title' => $client->full_name])

@section('header')
    {{ $client->full_name }}
@endsection

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-base font-semibold text-slate-900">{{ $client->full_name }}</p>
                <p class="text-sm text-slate-500">Client Code: {{ $client->client_code }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.clients.edit', $client) }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Client
                </a>
                <a
                    href="{{ route('admin.clients.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50"
                >
                    Back to List
                </a>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <x-admin.section class="xl:col-span-2" title="Client Profile" description="Personal and business information">
                <div class="space-y-6">
                    <!-- Personal Information -->
                    <div class="rounded-xl border border-slate-200 bg-white p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-4">Personal Information</p>
                        <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Full Name</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $client->full_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">ID Number</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $client->id_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Date of Birth</dt>
                                <dd class="mt-1 text-slate-700">{{ $client->date_of_birth ? $client->date_of_birth->format('M d, Y') : '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Gender</dt>
                                <dd class="mt-1 text-slate-700">{{ ucfirst($client->gender ?? '—') }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Nationality</dt>
                                <dd class="mt-1 text-slate-700">{{ $client->nationality ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Contact Information -->
                    <div class="rounded-xl border border-slate-200 bg-white p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-4">Contact Information</p>
                        <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Phone</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $client->phone }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Email</dt>
                                <dd class="mt-1 text-slate-700">{{ $client->email ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">M-PESA Phone</dt>
                                <dd class="mt-1 text-slate-700">{{ $client->mpesa_phone ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Alternate Phone</dt>
                                <dd class="mt-1 text-slate-700">{{ $client->alternate_phone ?? '—' }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Address</dt>
                                <dd class="mt-1 text-slate-700">{{ $client->address ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Business Information -->
                    <div class="rounded-xl border border-slate-200 bg-white p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-4">Business Information</p>
                        <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                            <div class="md:col-span-2">
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Business Name</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $client->business_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Business Type</dt>
                                <dd class="mt-1 text-slate-700">{{ $client->business_type }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Location</dt>
                                <dd class="mt-1 text-slate-700">{{ $client->location }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Occupation</dt>
                                <dd class="mt-1 text-slate-700">{{ $client->occupation ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Employer</dt>
                                <dd class="mt-1 text-slate-700">{{ $client->employer ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </x-admin.section>

            <x-admin.section title="Status & Activity" description="Client status and related information">
                <div class="space-y-4">
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-3">Status</p>
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                            {{ $client->status === 'active' ? 'bg-emerald-50 text-emerald-600' : ($client->status === 'blacklisted' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600') }}">
                            {{ ucfirst($client->status ?? 'inactive') }}
                        </span>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-3">KYC Status</p>
                        <div class="flex items-center gap-2">
                            @if($client->kyc_completed)
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                    <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Completed
                                </span>
                                @if($client->kyc_completed_at)
                                    <span class="text-xs text-slate-500">{{ $client->kyc_completed_at->format('M d, Y') }}</span>
                                @endif
                            @else
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-600">
                                    Pending
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($client->credit_score)
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-3">Credit Score</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $client->credit_score }}</p>
                        @if($client->credit_score_updated_at)
                            <p class="text-xs text-slate-500 mt-1">Updated {{ $client->credit_score_updated_at->diffForHumans() }}</p>
                        @endif
                    </div>
                    @endif

                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-3">Loan Activity</p>
                        <dl class="space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-600">Total Loans</dt>
                                <dd class="font-semibold text-slate-900">{{ $client->loans()->count() }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-600">Applications</dt>
                                <dd class="font-semibold text-slate-900">{{ $client->loanApplications()->count() }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500 mb-3">Account Information</p>
                        <dl class="space-y-2 text-sm text-slate-700">
                            <div>
                                <dt class="text-slate-500">Created</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $client->created_at->format('M d, Y') }}</dd>
                            </div>
                            @if($client->created_by)
                            <div>
                                <dt class="text-slate-500">Created By</dt>
                                <dd class="mt-1 text-slate-700">{{ $client->created_by }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </x-admin.section>
        </div>

        @if($client->loans()->count() > 0 || $client->loanApplications()->count() > 0)
        <x-admin.section title="Loan History" description="All loans and applications for this client">
            <div class="space-y-4">
                @if($client->loanApplications()->count() > 0)
                <div>
                    <p class="text-sm font-semibold text-slate-900 mb-3">Loan Applications ({{ $client->loanApplications()->count() }})</p>
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3 text-left">Application #</th>
                                    <th class="px-4 py-3 text-left">Amount</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-left">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($client->loanApplications()->latest()->take(5)->get() as $application)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('loan-applications.show', $application) }}" class="font-semibold text-emerald-600 hover:text-emerald-700">
                                            {{ $application->application_number ?? 'APP-' . $application->id }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">KES {{ number_format($application->amount, 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2 py-1 text-xs font-semibold
                                            {{ $application->status === 'approved' ? 'bg-emerald-50 text-emerald-600' : ($application->status === 'rejected' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600') }}">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">{{ $application->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                @if($client->loans()->count() > 0)
                <div>
                    <p class="text-sm font-semibold text-slate-900 mb-3">Active Loans ({{ $client->loans()->count() }})</p>
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <tr>
                                    <th class="px-4 py-3 text-left">Loan Type</th>
                                    <th class="px-4 py-3 text-left">Amount</th>
                                    <th class="px-4 py-3 text-left">Outstanding</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($client->loans()->latest()->take(5)->get() as $loan)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('loans.show', $loan) }}" class="font-semibold text-emerald-600 hover:text-emerald-700">
                                            {{ $loan->loan_type }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">KES {{ number_format($loan->amount_approved, 2) }}</td>
                                    <td class="px-4 py-3 font-semibold text-rose-600">KES {{ number_format($loan->outstanding_balance, 2) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full px-2 py-1 text-xs font-semibold bg-emerald-50 text-emerald-600">
                                            {{ ucfirst($loan->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </x-admin.section>
        @endif
    </div>
@endsection

