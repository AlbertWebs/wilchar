@extends('layouts.admin', ['title' => 'Shareholders'])

@section('header')
    Shareholders & Contributions
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Add Shareholder">
            <form
                action="{{ route('shareholders.store') }}"
                method="POST"
                x-ajax="{ successMessage: { title: 'Shareholder Added' }, onSuccess() { window.location.reload(); } }"
                class="grid gap-4 md:grid-cols-3"
            >
                @csrf
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Name</label>
                    <input type="text" name="name" class="mt-1 w-full rounded-xl border-slate-200" required>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</label>
                    <input type="email" name="email" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone</label>
                    <input type="text" name="phone" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div class="md:col-span-3">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Address</label>
                    <input type="text" name="address" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Shares Owned</label>
                    <input type="number" name="shares_owned" step="0.01" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div class="md:col-span-3">
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Notes</label>
                    <input type="text" name="notes" class="mt-1 w-full rounded-xl border-slate-200">
                </div>
                <div class="md:col-span-3 flex justify-end">
                    <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                        Save Shareholder
                    </button>
                </div>
            </form>
        </x-admin.section>

        <div class="grid gap-6 xl:grid-cols-2">
            @foreach($shareholders as $shareholder)
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">{{ $shareholder->name }}</h3>
                            <p class="text-xs text-slate-500">{{ $shareholder->email }} · {{ $shareholder->phone }}</p>
                        </div>
                        <div class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-600">
                            KES {{ number_format($shareholder->contributions_total ?? 0, 2) }}
                        </div>
                    </div>

                    <div class="mt-4 border-t border-slate-100 pt-4 text-sm text-slate-600">
                        <p>Shares Owned: <span class="font-semibold text-slate-900">{{ number_format($shareholder->shares_owned ?? 0, 2) }}</span></p>
                        <p class="text-xs text-slate-500">{{ $shareholder->notes ?? 'No notes provided.' }}</p>
                    </div>

                    <form
                        action="{{ route('shareholders.contributions.store', $shareholder) }}"
                        method="POST"
                        x-ajax="{ successMessage: { title: 'Contribution Added' }, onSuccess() { window.location.reload(); } }"
                        class="mt-4 grid gap-3 md:grid-cols-3"
                    >
                        @csrf
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Date</label>
                            <input type="date" name="contribution_date" class="mt-1 w-full rounded-xl border-slate-200" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</label>
                            <input type="number" name="amount" step="0.01" class="mt-1 w-full rounded-xl border-slate-200" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Reference</label>
                            <input type="text" name="reference" class="mt-1 w-full rounded-xl border-slate-200">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Description</label>
                            <input type="text" name="description" class="mt-1 w-full rounded-xl border-slate-200">
                        </div>
                        <div class="flex items-end justify-end">
                            <button type="submit" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                Add Contribution
                            </button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>

        <div>
            {{ $shareholders->links() }}
        </div>
    </div>
@endsection

