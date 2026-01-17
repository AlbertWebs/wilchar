@extends('layouts.admin', ['title' => 'B2B Transaction'])

@section('header')
    B2B Transaction
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section
            title="Transaction Overview"
            description="Details for this B2B payment between M-Pesa shortcodes."
        >
            <div class="grid gap-6 lg:grid-cols-3 text-sm text-slate-700">
                <div class="lg:col-span-2 space-y-4">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-slate-500">Payment</p>
                            <dl class="mt-3 space-y-2">
                                <div class="flex justify-between">
                                    <dt>Amount</dt>
                                    <dd class="font-semibold text-emerald-600">
                                        KES {{ number_format($b2bTransaction->amount, 2) }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Party A</dt>
                                    <dd>{{ $b2bTransaction->party_a }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Party B</dt>
                                    <dd>{{ $b2bTransaction->party_b }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Account Reference</dt>
                                    <dd>{{ $b2bTransaction->account_reference ?? '—' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Remarks</dt>
                                    <dd class="text-right text-xs text-slate-600 max-w-xs">
                                        {{ $b2bTransaction->remarks ?? '—' }}
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
                                                @if($b2bTransaction->status === 'success')
                                                    bg-emerald-100 text-emerald-700
                                                @elseif($b2bTransaction->status === 'failed')
                                                    bg-rose-100 text-rose-700
                                                @else
                                                    bg-amber-100 text-amber-700
                                                @endif"
                                        >
                                            {{ ucfirst($b2bTransaction->status ?? 'pending') }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Originator Conversation ID</dt>
                                    <dd class="text-xs text-slate-700">
                                        {{ $b2bTransaction->originator_conversation_id ?? '—' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Conversation ID</dt>
                                    <dd class="text-xs text-slate-700">
                                        {{ $b2bTransaction->conversation_id ?? '—' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Result Code</dt>
                                    <dd>{{ $b2bTransaction->result_code ?? '—' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Result Description</dt>
                                    <dd class="text-right text-xs text-slate-600 max-w-xs">
                                        {{ $b2bTransaction->result_desc ?? '—' }}
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt>Transaction Receipt</dt>
                                    <dd>{{ $b2bTransaction->transaction_receipt ?? '—' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Initiator</p>
                        <dl class="mt-3 space-y-2">
                            <div class="flex justify-between">
                                <dt>Initiator Name</dt>
                                <dd>{{ $b2bTransaction->initiator_name ?? '—' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt>Initiated By</dt>
                                <dd>{{ $b2bTransaction->initiator?->name ?? 'System' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt>Created At</dt>
                                <dd>{{ $b2bTransaction->created_at?->format('Y-m-d H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Raw Callback Payload</p>
                        <p class="mt-2 text-xs text-slate-500">
                            Full payload received on the B2B result callback endpoint.
                        </p>
                        <pre class="mt-3 max-h-80 overflow-auto rounded-lg bg-slate-900 p-3 text-xs text-slate-100">
{{ json_encode($b2bTransaction->callback_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
                        </pre>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('mpesa.b2b.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700">
                    &larr; Back to B2B list
                </a>
            </div>
        </x-admin.section>
    </div>
@endsection


