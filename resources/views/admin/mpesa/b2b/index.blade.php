@extends('layouts.admin', ['title' => 'B2B Transactions'])

@section('header')
    B2B Transactions
@endsection

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600">
                <form method="GET" action="{{ route('mpesa.b2b.index') }}" class="flex flex-wrap items-center gap-2">
                    <select name="status" class="rounded-xl border-slate-200 text-xs">
                        <option value="">Status: All</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="success" @selected(request('status') === 'success')>Success</option>
                        <option value="failed" @selected(request('status') === 'failed')>Failed</option>
                    </select>
                    <input
                        type="text"
                        name="party_b"
                        value="{{ request('party_b') }}"
                        placeholder="Party B shortcode"
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

            <a
                href="{{ route('mpesa.b2b.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                New B2B Payment
            </a>
        </div>

        <x-admin.section title="B2B Payment History" description="Payments between M-Pesa shortcodes.">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Originator Conversation</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Parties</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Created</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $tx)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-slate-900">{{ $tx->originator_conversation_id ?? '—' }}</p>
                                    <p class="text-xs text-slate-400">{{ $tx->transaction_receipt ?? 'No receipt yet' }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <p>From: {{ $tx->party_a }}</p>
                                    <p class="text-xs text-slate-500">To: {{ $tx->party_b }}</p>
                                </td>
                                <td class="px-4 py-3 font-semibold text-emerald-600">
                                    KES {{ number_format($tx->amount, 2) }}
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                            @if($tx->status === 'success')
                                                bg-emerald-100 text-emerald-700
                                            @elseif($tx->status === 'failed')
                                                bg-rose-100 text-rose-700
                                            @else
                                                bg-amber-100 text-amber-700
                                            @endif"
                                    >
                                        {{ ucfirst($tx->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-xs text-slate-500">
                                    {{ $tx->created_at?->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a
                                        href="{{ route('mpesa.b2b.show', $tx) }}"
                                        class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                                    >
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">
                                    No B2B transactions found.
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


