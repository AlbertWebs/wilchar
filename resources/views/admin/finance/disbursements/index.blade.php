@extends('layouts.admin', ['title' => 'Finance Disbursements'])

@section('header')
    Finance Disbursements
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Liquidity Snapshot" description="Latest M-Pesa balances and internal cash.">
            <div class="grid gap-4 md:grid-cols-3 text-sm">
                <div class="rounded-2xl border border-slate-200 bg-emerald-50/60 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">M-Pesa Working Account</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">
                        KES {{ number_format($balances->working_account_balance ?? 0, 2) }}
                    </p>
                    <p class="text-xs text-slate-500">
                        Updated {{ optional($balances?->account_balance_time)->diffForHumans() ?? '—' }}
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-blue-50/60 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">M-Pesa Utility Account</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">
                        KES {{ number_format($balances->utility_account_balance ?? 0, 2) }}
                    </p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-violet-50/60 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-violet-600">Cash Account Balance</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">
                        KES {{ number_format($cashAccount->balance ?? 0, 2) }}
                    </p>
                    <p class="text-xs text-slate-500">Ledger: {{ $cashAccount->name ?? '—' }}</p>
                </div>
            </div>
        </x-admin.section>

        @role('Finance')
            <x-admin.section title="Loans Awaiting Finance Preparation" description="Prepare the payout then request director approval.">
                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Application</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Client</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Requested</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pendingApplications as $application)
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-900">
                                        {{ $application->application_number }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">
                                        <p>{{ $application->client->full_name }}</p>
                                        <p class="text-xs text-slate-400">{{ $application->client->phone }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-slate-900">KES {{ number_format($application->amount_approved ?? $application->amount, 2) }}</p>
                                        <p class="text-xs text-slate-500">{{ ucfirst($application->repayment_frequency ?? 'monthly') }} repayments</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <form method="POST" action="{{ route('finance-disbursements.prepare') }}" class="space-y-2">
                                            @csrf
                                            <input type="hidden" name="loan_application_id" value="{{ $application->id }}">
                                            <div class="flex gap-2">
                                                <input
                                                    type="number"
                                                    name="amount"
                                                    class="w-1/2 rounded-xl border-slate-200 text-sm"
                                                    value="{{ old('amount', $application->amount_approved ?? $application->amount) }}"
                                                    min="1"
                                                    step="0.01"
                                                    required
                                                >
                                                <input
                                                    type="text"
                                                    name="recipient_phone"
                                                    class="w-1/2 rounded-xl border-slate-200 text-sm"
                                                    value="{{ old('recipient_phone', $application->client->phone) }}"
                                                    placeholder="2547XXXXXXXX"
                                                    required
                                                >
                                            </div>
                                            <textarea
                                                name="remarks"
                                                rows="2"
                                                class="w-full rounded-xl border-slate-200 text-xs"
                                                placeholder="Internal note (optional)"
                                            >{{ old('remarks') }}</textarea>
                                            <button
                                                type="submit"
                                                class="w-full rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600"
                                            >
                                                Prepare & Send OTP
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">
                                        No applications awaiting finance action.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-admin.section>
        @endrole

        @role('Director|Finance')
            <x-admin.section title="Awaiting Director Authorization" description="OTP required before sending cash to customer.">
                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Application</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Prepared By</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($awaitingDirector as $disbursement)
                                <tr>
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-slate-900">{{ $disbursement->loanApplication->application_number }}</p>
                                        <p class="text-xs text-slate-500">{{ $disbursement->loanApplication->client->full_name }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">
                                        {{ $disbursement->preparedBy->name ?? '—' }}
                                        <p class="text-xs text-slate-400">{{ $disbursement->created_at->diffForHumans() }}</p>
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-slate-900">
                                        KES {{ number_format($disbursement->amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">
                                        OTP expires {{ optional($disbursement->otp_expires_at)->diffForHumans() ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @role('Director')
                                            <a
                                                href="{{ route('finance-disbursements.confirm.show', $disbursement) }}"
                                                class="rounded-xl bg-indigo-500 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600"
                                            >
                                                Enter OTP
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400">Waiting director OTP</span>
                                        @endrole
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">
                                        No disbursements awaiting director approval.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-admin.section>
        @endrole
    </div>
@endsection

