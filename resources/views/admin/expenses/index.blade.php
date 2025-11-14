@extends('layouts.admin', ['title' => 'Expenses'])

@section('header')
    Expense Management
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Record Expense" description="Track operational expenses by category and team.">
            <form
                action="{{ route('expenses.store') }}"
                method="POST"
                x-ajax="{ successMessage: { title: 'Expense Added' }, onSuccess() { window.location.reload(); } }"
                class="grid gap-4 md:grid-cols-5"
            >
                @csrf
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Category</label>
                    <input type="text" name="category" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</label>
                    <input type="number" name="amount" step="0.01" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date</label>
                    <input type="date" name="expense_date" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Account</label>
                    <select name="account_id" class="mt-1 w-full rounded-xl border-slate-200">
                        <option value="">Select account</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Team</label>
                    <select name="team_id" class="mt-1 w-full rounded-xl border-slate-200">
                        <option value="">All teams</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Description</label>
                    <input type="text" name="description" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div class="md:col-span-5 flex justify-end">
                    <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                        Save Expense
                    </button>
                </div>
            </form>
        </x-admin.section>

        <x-admin.section title="Expense Log">
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Reference</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Category</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Team</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($expenses as $expense)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-4 py-4 text-slate-700">{{ $expense->reference }}</td>
                                <td class="px-4 py-4 text-slate-700">
                                    {{ $expense->category }}
                                    <div class="text-xs text-slate-500">{{ $expense->description }}</div>
                                </td>
                                <td class="px-4 py-4 font-semibold text-slate-900">KES {{ number_format($expense->amount, 2) }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $expense->expense_date->format('d M Y') }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $expense->team->name ?? 'All' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                    {{ $expenses->links() }}
                </div>
            </div>
        </x-admin.section>
    </div>
@endsection

