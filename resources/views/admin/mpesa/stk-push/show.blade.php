@extends('layouts.admin', ['title' => 'STK Push Details'])

@section('header')
    STK Push Details
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section
            title="Transaction Overview"
            description="Details for this STK Push request and its callback from M-Pesa."
        >
            <div class="grid gap-6 lg:grid-cols-3 text-sm text-slate-700">
                <div class="lg:col-span-2 space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500">Payment</p>
                            <dl class="mt-3 space-y-2">
                                <div class="flex justify-between">
                                    <dt>Amount</dt>
                                    <dd class="font-semibold text-slate-900">
                                        KES {{ number_format($stkPush->amount, 2) }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Phone</dt>
                                    <dd class="font-semibold text-slate-900">
                                        {{ $stkPush->phone_number }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Account Reference</dt>
                                    <dd class="font-semibold text-slate-900">
                                        {{ $stkPush->account_reference }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Description</dt>
                                    <dd class="text-slate-700">
                                        {{ $stkPush->transaction_desc }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500">Status</p>
                            <dl class="mt-3 space-y-2">
                                <div class="flex justify-between">
                                    <dt>Current Status</dt>
                                    <dd>
                                        <span
                                            class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                                @if($stkPush->status === 'success')
                                                    bg-emerald-100 text-emerald-700
                                                @elseif($stkPush->status === 'failed')
                                                    bg-rose-100 text-rose-700
                                                @else
                                                    bg-amber-100 text-amber-700
                                                @endif"
                                        >
                                            {{ ucfirst($stkPush->status ?? 'pending') }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Receipt</dt>
                                    <dd class="font-semibold text-slate-900">
                                        {{ $stkPush->mpesa_receipt_number ?? '—' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Result Code</dt>
                                    <dd>{{ $stkPush->result_code ?? '—' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Result Description</dt>
                                    <dd class="text-right text-xs text-slate-600 max-w-xs">
                                        {{ $stkPush->result_desc ?? '—' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Created At</dt>
                                    <dd>{{ $stkPush->created_at?->format('Y-m-d H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Linking</p>
                        <dl class="mt-3 space-y-2">
                            <div class="flex justify-between">
                                <dt>Initiated By</dt>
                                <dd>
                                    {{ $stkPush->initiator?->name ?? 'System' }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt>Linked Loan</dt>
                                <dd>
                                    @if($stkPush->loan)
                                        Loan #{{ $stkPush->loan->id }}
                                    @else
                                        <span class="text-xs text-amber-500">Not attached to a loan</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Raw Callback Payload</p>
                        <p class="mt-2 text-xs text-slate-500">
                            Useful for debugging integration issues with M-Pesa STK callbacks.
                        </p>
                        <pre class="mt-3 max-h-80 overflow-auto rounded-lg bg-slate-900 p-3 text-xs text-slate-100">
{{ json_encode($stkPush->callback_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
                        </pre>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('mpesa.stk-push.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700">
                    &larr; Back to STK Push list
                </a>
            </div>
        </x-admin.section>
    </div>
@endsection


