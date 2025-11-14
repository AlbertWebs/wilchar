@extends('layouts.admin', ['title' => 'Liabilities'])

@section('header')
    Liability Management
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Add Liability">
            <form
                action="{{ route('liabilities.store') }}"
                method="POST"
                x-ajax="{ successMessage: { title: 'Liability Added' }, onSuccess() { window.location.reload(); } }"
                class="grid gap-4 md:grid-cols-4"
            >
                @csrf
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Name</label>
                    <input type="text" name="name" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Creditor</label>
                    <input type="text" name="creditor" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</label>
                    <input type="number" name="amount" step="0.01" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Outstanding</label>
                    <input type="number" name="outstanding_balance" step="0.01" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Issued On</label>
                    <input type="date" name="issued_on" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Due Date</label>
                    <input type="date" name="due_date" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                    <select name="status" class="mt-1 w-full rounded-xl border-slate-200">
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="settled">Settled</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Team</label>
                    <select name="team_id" class="mt-1 w-full rounded-xl border-slate-200">
                        <option value="">Unassigned</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Notes</label>
                    <input type="text" name="notes" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                        Save Liability
                    </button>
                </div>
            </form>
        </x-admin.section>

        <x-admin.section title="Liabilities Register">
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Liability</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Amounts</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Dates</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($liabilities as $liability)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-slate-900">{{ $liability->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $liability->creditor ?? '—' }}</p>
                                </td>
                                <td class="px-4 py-4 text-slate-700">
                                    Total: {{ number_format($liability->amount, 2) }} <br>
                                    Outstanding: {{ number_format($liability->outstanding_balance, 2) }}
                                </td>
                                <td class="px-4 py-4 text-slate-700">
                                    Issued: {{ optional($liability->issued_on)->format('d M Y') ?? '—' }} <br>
                                    Due: {{ optional($liability->due_date)->format('d M Y') ?? '—' }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                        @class([
                                            'bg-amber-100 text-amber-600' => $liability->status === 'pending',
                                            'bg-emerald-100 text-emerald-600' => $liability->status === 'active',
                                            'bg-slate-200 text-slate-600' => $liability->status === 'settled',
                                            'bg-rose-100 text-rose-600' => $liability->status === 'overdue',
                                        ])
                                    ">
                                        {{ ucfirst($liability->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                    {{ $liabilities->links() }}
                </div>
            </div>
        </x-admin.section>
    </div>
@endsection

