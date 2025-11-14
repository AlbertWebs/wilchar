@extends('layouts.admin', ['title' => 'Account Balances'])

@section('header')
    Daily Account Balances
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Record Balance">
            <form
                action="{{ route('account-balances.store') }}"
                method="POST"
                x-ajax="{ successMessage: { title: 'Balance Saved' }, onSuccess() { window.location.reload(); } }"
                class="grid gap-4 md:grid-cols-5"
            >
                @csrf
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Account</label>
                    <select name="account_id" class="mt-1 w-full rounded-xl border-slate-200" required>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date</label>
                    <input type="date" name="balance_date" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Opening</label>
                    <input type="number" name="opening_balance" step="0.01" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Closing</label>
                    <input type="number" name="closing_balance" step="0.01" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Credits</label>
                    <input type="number" name="credits" step="0.01" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Debits</label>
                    <input type="number" name="debits" step="0.01" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div class="md:col-span-5 flex justify-end">
                    <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                        Save Balance
                    </button>
                </div>
            </form>
        </x-admin.section>

        <x-admin.section title="Historical Balances">
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Account</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Opening / Closing</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Credits / Debits</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($balances as $balance)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-4 py-4 text-slate-700">{{ $balance->account->name }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $balance->balance_date->format('d M Y') }}</td>
                                <td class="px-4 py-4 text-slate-700">
                                    Opening: {{ number_format($balance->opening_balance, 2) }} <br>
                                    Closing: {{ number_format($balance->closing_balance, 2) }}
                                </td>
                                <td class="px-4 py-4 text-slate-700">
                                    Credits: {{ number_format($balance->credits, 2) }} <br>
                                    Debits: {{ number_format($balance->debits, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                    {{ $balances->links() }}
                </div>
            </div>
        </x-admin.section>
    </div>
@endsection

