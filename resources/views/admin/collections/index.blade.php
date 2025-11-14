@extends('layouts.admin', ['title' => 'Collections & Recovery'])

@section('header')
    Collections & Recovery
@endsection

@section('content')
    <x-admin.section title="Repayments" description="Track instalments and overdue follow-ups.">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Loan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($repayments as $collection)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-4">
                                <p class="font-semibold text-slate-900">#{{ optional($collection->loan)->id }}</p>
                                <p class="text-xs text-slate-500">{{ optional($collection->loan)->loan_type }}</p>
                            </td>
                            <td class="px-4 py-4 text-slate-700">
                                {{ optional($collection->loan?->client)->full_name ?? 'Unknown' }}
                                <div class="text-xs text-slate-500">{{ optional($collection->loan?->client)->phone }}</div>
                            </td>
                            <td class="px-4 py-4 text-slate-700">
                                KES {{ number_format($collection->amount, 2) }}
                                <div class="text-xs text-slate-500">{{ $collection->payment_method }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    Completed
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('collections.show', $collection) }}" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">
                                No collections recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                {{ $repayments->links() }}
            </div>
        </div>
    </x-admin.section>
@endsection

