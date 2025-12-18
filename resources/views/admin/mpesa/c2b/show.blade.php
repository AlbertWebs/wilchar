@extends('layouts.admin', ['title' => 'C2B Transaction'])

@section('header')
    C2B Transaction
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section
            title="Transaction Overview"
            description="Details for this C2B PayBill / Till transaction."
        >
            <div class="grid gap-6 lg:grid-cols-3 text-sm text-slate-700">
                <div class="lg:col-span-2 space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500">Payment</p>
                            <dl class="mt-3 space-y-2">
                                <div class="flex justify-between">
                                    <dt>Trans ID</dt>
                                    <dd class="font-semibold text-slate-900">{{ $c2bTransaction->trans_id }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Amount</dt>
                                    <dd class="font-semibold text-emerald-600">
                                        KES {{ number_format($c2bTransaction->trans_amount, 2) }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Time</dt>
                                    <dd>{{ $c2bTransaction->trans_time }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Bill Ref</dt>
                                    <dd>{{ $c2bTransaction->bill_ref_number ?? '—' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Business ShortCode</dt>
                                    <dd>{{ $c2bTransaction->business_short_code ?? '—' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500">Payer</p>
                            <dl class="mt-3 space-y-2">
                                <div class="flex justify-between">
                                    <dt>MSISDN</dt>
                                    <dd class="font-semibold text-slate-900">{{ $c2bTransaction->msisdn }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Name</dt>
                                    <dd>{{ $c2bTransaction->full_name }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Status</dt>
                                    <dd>
                                        <span
                                            class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                                @if($c2bTransaction->status === 'completed')
                                                    bg-emerald-100 text-emerald-700
                                                @else
                                                    bg-amber-100 text-amber-700
                                                @endif"
                                        >
                                            {{ ucfirst($c2bTransaction->status ?? 'pending') }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Linking</p>
                        <dl class="mt-3 space-y-2">
                            <div class="flex justify-between">
                                <dt>Linked Loan</dt>
                                <dd>
                                    @if($c2bTransaction->loan)
                                        Loan #{{ $c2bTransaction->loan->id }}
                                    @else
                                        <span class="text-xs text-amber-500">Not attached to a loan</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt>Org Account Balance</dt>
                                <dd>{{ $c2bTransaction->org_account_balance ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Raw Callback Payload</p>
                        <p class="mt-2 text-xs text-slate-500">
                            Full payload received on the C2B confirmation endpoint.
                        </p>
                        <pre class="mt-3 max-h-80 overflow-auto rounded-lg bg-slate-900 p-3 text-xs text-slate-100">
{{ json_encode($c2bTransaction->callback_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
                        </pre>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('mpesa.c2b.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700">
                    &larr; Back to C2B list
                </a>
            </div>
        </x-admin.section>
    </div>
@endsection


