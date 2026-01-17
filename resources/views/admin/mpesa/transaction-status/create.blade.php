@extends('layouts.admin', ['title' => 'New Transaction Status Query'])

@section('header')
    New Transaction Status Query
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section
            title="Query Transaction Status"
            description="Check the status of a specific M-Pesa transaction by Transaction ID or receipt number."
        >
            <form method="POST" action="{{ route('mpesa.transaction-status.store') }}" class="space-y-5 max-w-xl">
                @csrf

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Transaction ID / Receipt Number
                    </label>
                    <input
                        type="text"
                        name="transaction_id"
                        value="{{ old('transaction_id') }}"
                        placeholder="e.g. LHG3Q7YJ5T"
                        class="mt-1 w-full rounded-xl border-slate-200 text-sm"
                        required
                    >
                    @error('transaction_id')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-slate-500">
                        Use the M-Pesa receipt number (e.g. LHG3Q7YJ5T) or transaction ID you received.
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Remarks (optional)
                    </label>
                    <input
                        type="text"
                        name="remarks"
                        value="{{ old('remarks') }}"
                        placeholder="Short description e.g. Confirm failed B2C payout"
                        class="mt-1 w-full rounded-xl border-slate-200 text-sm"
                    >
                    @error('remarks')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-3 flex items-center gap-3">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                        Submit Query
                    </button>

                    <a href="{{ route('mpesa.transaction-status.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700">
                        Cancel
                    </a>
                </div>
            </form>
        </x-admin.section>
    </div>
@endsection


