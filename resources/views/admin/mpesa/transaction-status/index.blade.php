@extends('layouts.admin', ['title' => 'M-Pesa Transaction Status'])

@section('header')
    M-Pesa Transaction Status
@endsection

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="text-sm text-slate-600">
                Use this page to review previous status queries for specific M-Pesa transactions.
            </div>
            <a
                href="{{ route('mpesa.transaction-status.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                New Status Query
            </a>
        </div>

        <x-admin.section title="Status Queries" description="History of transaction status lookups.">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Transaction ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Receipt</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Requested By</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($statuses as $status)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ $status->transaction_id }}</p>
                                    <p class="text-xs text-slate-400">
                                        Originator: {{ $status->originator_conversation_id ?? '—' }}
                                    </p>
                                </td>
                                <td class="px-4 py-3 font-semibold text-emerald-600">
                                    @if($status->transaction_amount)
                                        KES {{ number_format($status->transaction_amount, 2) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $status->receipt_number ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                            @if($status->status === 'found')
                                                bg-emerald-100 text-emerald-700
                                            @elseif($status->status === 'not_found')
                                                bg-amber-100 text-amber-700
                                            @elseif($status->status === 'failed')
                                                bg-rose-100 text-rose-700
                                            @else
                                                bg-slate-100 text-slate-700
                                            @endif"
                                    >
                                        {{ ucfirst(str_replace('_', ' ', $status->status ?? 'pending')) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $status->requester?->name ?? 'System' }}
                                </td>
                                <td class="px-4 py-3 text-right text-xs text-slate-500">
                                    {{ $status->created_at?->format('Y-m-d H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">
                                    No transaction status queries found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $statuses->links() }}
            </div>
        </x-admin.section>
    </div>
@endsection


