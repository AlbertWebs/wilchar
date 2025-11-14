@extends('layouts.admin', ['title' => 'Trial Balance Detail'])

@section('header')
    Trial Balance {{ $trialBalance->period_start->format('d M Y') }} - {{ $trialBalance->period_end->format('d M Y') }}
@endsection

@section('content')
    <x-admin.section title="Summary" description="Debits vs Credits">
        <div class="grid gap-4 md:grid-cols-3 text-sm">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Debits</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">KES {{ number_format($trialBalance->total_debits, 2) }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Credits</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">KES {{ number_format($trialBalance->total_credits, 2) }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Difference</p>
                <p class="mt-2 text-2xl font-semibold {{ ($trialBalance->total_debits - $trialBalance->total_credits) === 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                    KES {{ number_format($trialBalance->total_debits - $trialBalance->total_credits, 2) }}
                </p>
            </div>
        </div>
    </x-admin.section>

    <x-admin.section title="Entries">
        <div class="overflow-hidden rounded-2xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Account</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Debit</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Credit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach($trialBalance->entries as $entry)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-4 py-4 text-slate-700">{{ $entry->account_name }}</td>
                            <td class="px-4 py-4 text-right text-slate-700">{{ number_format($entry->debit, 2) }}</td>
                            <td class="px-4 py-4 text-right text-slate-700">{{ number_format($entry->credit, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-admin.section>
@endsection

