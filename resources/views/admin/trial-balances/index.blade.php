@extends('layouts.admin', ['title' => 'Trial Balances'])

@section('header')
    Trial Balance
@endsection

@section('content')
    <x-admin.section title="Generate Trial Balance" description="Summarise debits and credits for a specific period.">
        <form
            action="{{ route('trial-balances.store') }}"
            method="POST"
            x-ajax="{ successMessage: { title: 'Trial Balance Generated' }, onSuccess(response) { window.location = response.trial_balance_url ?? '{{ route('trial-balances.index') }}'; } }"
            class="grid gap-4 md:grid-cols-3"
        >
            @csrf
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Period Start</label>
                <input type="date" name="period_start" class="mt-1 w-full rounded-xl border-slate-200" required>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Period End</label>
                <input type="date" name="period_end" class="mt-1 w-full rounded-xl border-slate-200" required>
            </div>
            <div class="flex items-end justify-end">
                <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                    Generate
                </button>
            </div>
        </form>
    </x-admin.section>

    <x-admin.section title="Generated Trial Balances">
        <div class="overflow-hidden rounded-2xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Period</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Totals</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Prepared By</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @foreach($trialBalances as $trialBalance)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-4 py-4 text-slate-700">
                                {{ $trialBalance->period_start->format('d M Y') }} —
                                {{ $trialBalance->period_end->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4 text-slate-700">
                                Debits: {{ number_format($trialBalance->total_debits, 2) }} <br>
                                Credits: {{ number_format($trialBalance->total_credits, 2) }}
                            </td>
                            <td class="px-4 py-4 text-slate-700">
                                {{ $trialBalance->preparer->name ?? 'System' }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('trial-balances.show', $trialBalance) }}" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                        View
                                    </a>
                                    <form method="POST" action="{{ route('trial-balances.destroy', $trialBalance) }}" x-data @submit.prevent="Admin.confirmAction({ title: 'Delete Trial Balance?', icon: 'warning', text: 'Entries will be removed.', confirmButtonText: 'Delete' }).then(confirmed => { if (confirmed) $el.submit(); })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-50">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                {{ $trialBalances->links() }}
            </div>
        </div>
    </x-admin.section>
@endsection

