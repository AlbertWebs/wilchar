@extends('layouts.admin', ['title' => 'Client Payments'])

@section('header')
    Client Payments
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="STK Push Payments" description="Review Lipa na M-Pesa Online transactions and attach them to loans.">
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Reference</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Client Phone</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Loan</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Attach</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($stkPushes as $payment)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ $payment->mpesa_receipt_number ?? '—' }}</p>
                                    <p class="text-xs text-slate-400">{{ $payment->created_at->diffForHumans() }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <p>{{ $payment->phone_number }}</p>
                                    <p class="text-xs text-slate-400">{{ $payment->account_reference }}</p>
                                </td>
                                <td class="px-4 py-3 font-semibold text-emerald-600">
                                    KES {{ number_format($payment->amount, 2) }}
                                    <span class="ml-2 text-xs {{ $payment->status === 'success' ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    @if($payment->loan)
                                        Loan #{{ $payment->loan->id }} <span class="text-xs text-slate-400">Outstanding: KES {{ number_format($payment->loan->outstanding_balance, 2) }}</span>
                                    @else
                                        <span class="text-xs text-amber-500">Unattached</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('payments.attach') }}" class="inline-flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="type" value="stk">
                                        <input type="hidden" name="payment_id" value="{{ $payment->id }}">
                                        <input
                                            type="number"
                                            name="loan_id"
                                            class="w-28 rounded-xl border-slate-200 text-xs"
                                            placeholder="Loan ID"
                                            value="{{ old('loan_id') }}"
                                            required
                                        >
                                        <button
                                            type="submit"
                                            class="rounded-xl bg-indigo-500 px-3 py-1 text-xs font-semibold text-white hover:bg-indigo-600"
                                        >
                                            Attach
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $stkPushes->links() }}
            </div>
        </x-admin.section>

        <x-admin.section title="C2B Payments" description="Incoming PayBill/BuyGoods transactions matched to loans.">
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Trans ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Payer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Loan</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Attach</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($c2bTransactions as $transaction)
                            <tr>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ $transaction->trans_id }}</p>
                                    <p class="text-xs text-slate-400">{{ $transaction->trans_time }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <p>{{ $transaction->msisdn }}</p>
                                    <p class="text-xs text-slate-400">{{ $transaction->full_name }}</p>
                                </td>
                                <td class="px-4 py-3 font-semibold text-emerald-600">
                                    KES {{ number_format($transaction->trans_amount, 2) }}
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    @if($transaction->loan)
                                        Loan #{{ $transaction->loan->id }} <span class="text-xs text-slate-400">Outstanding: KES {{ number_format($transaction->loan->outstanding_balance, 2) }}</span>
                                    @else
                                        <span class="text-xs text-amber-500">Unattached</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('payments.attach') }}" class="inline-flex items-center gap-2">
                                        @csrf
                                        <input type="hidden" name="type" value="c2b">
                                        <input type="hidden" name="payment_id" value="{{ $transaction->id }}">
                                        <input
                                            type="number"
                                            name="loan_id"
                                            class="w-28 rounded-xl border-slate-200 text-xs"
                                            placeholder="Loan ID"
                                            required
                                        >
                                        <button
                                            type="submit"
                                            class="rounded-xl bg-indigo-500 px-3 py-1 text-xs font-semibold text-white hover:bg-indigo-600"
                                        >
                                            Attach
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $c2bTransactions->links() }}
            </div>
        </x-admin.section>
    </div>
@endsection

