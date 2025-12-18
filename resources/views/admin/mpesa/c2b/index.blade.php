@extends('layouts.admin', ['title' => 'C2B Transactions'])

@section('header')
    C2B Transactions
@endsection

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600">
                <form method="GET" action="{{ route('mpesa.c2b.index') }}" class="flex flex-wrap items-center gap-2">
                    <select name="status" class="rounded-xl border-slate-200 text-xs">
                        <option value="">Status: All</option>
                        <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    </select>
                    <input
                        type="text"
                        name="phone"
                        value="{{ request('phone') }}"
                        placeholder="Phone (MSISDN)"
                        class="w-40 rounded-xl border-slate-200 text-xs"
                    >
                    <input
                        type="text"
                        name="trans_id"
                        value="{{ request('trans_id') }}"
                        placeholder="TransID"
                        class="w-40 rounded-xl border-slate-200 text-xs"
                    >
                    <input
                        type="date"
                        name="start_date"
                        value="{{ request('start_date') }}"
                        class="rounded-xl border-slate-200 text-xs"
                    >
                    <input
                        type="date"
                        name="end_date"
                        value="{{ request('end_date') }}"
                        class="rounded-xl border-slate-200 text-xs"
                    >
                    <button
                        type="submit"
                        class="rounded-xl bg-slate-900 px-3 py-1 text-xs font-semibold text-white hover:bg-slate-800"
                    >
                        Filter
                    </button>
                </form>
            </div>

            <div class="text-xs text-slate-500">
                Total: <span class="font-semibold text-slate-900">{{ number_format($stats['total']) }}</span> ·
                Completed: <span class="font-semibold text-emerald-600">{{ number_format($stats['completed']) }}</span> ·
                Amount (completed): <span class="font-semibold text-slate-900">KES {{ number_format($stats['total_amount'], 2) }}</span>
            </div>
        </div>

        <x-admin.section title="C2B Payments" description="Incoming PayBill / Till (BuyGoods) transactions.">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Trans ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Payer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Bill Ref</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Loan</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Time</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $tx)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ $tx->trans_id }}</p>
                                    <p class="text-xs text-slate-400">{{ $tx->transaction_type }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <p>{{ $tx->msisdn }}</p>
                                    <p class="text-xs text-slate-400">{{ $tx->full_name }}</p>
                                </td>
                                <td class="px-4 py-3 font-semibold text-emerald-600">
                                    KES {{ number_format($tx->trans_amount, 2) }}
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $tx->bill_ref_number ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    @if($tx->loan)
                                        Loan #{{ $tx->loan->id }}
                                    @else
                                        <span class="text-xs text-amber-500">Not attached</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right text-xs text-slate-500">
                                    {{ $tx->trans_time }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a
                                        href="{{ route('mpesa.c2b.show', $tx) }}"
                                        class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                                    >
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">
                                    No C2B transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $transactions->withQueryString()->links() }}
            </div>
        </x-admin.section>
    </div>
@endsection


