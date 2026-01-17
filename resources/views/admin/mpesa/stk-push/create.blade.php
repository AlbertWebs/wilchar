@extends('layouts.admin', ['title' => 'New STK Push'])

@section('header')
    New STK Push
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section
            title="Initiate STK Push"
            description="Send a Lipa na M-Pesa Online prompt to a client."
        >
            <form method="POST" action="{{ route('mpesa.stk-push.store') }}" class="space-y-5 max-w-xl">
                @csrf

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                            Phone Number (MSISDN)
                        </label>
                        <input
                            type="text"
                            name="phone_number"
                            value="{{ old('phone_number') }}"
                            placeholder="2547xxxxxxxx"
                            class="mt-1 w-full rounded-xl border-slate-200 text-sm"
                            required
                        >
                        @error('phone_number')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                            Amount (KES)
                        </label>
                        <input
                            type="number"
                            step="0.01"
                            min="1"
                            name="amount"
                            value="{{ old('amount') }}"
                            class="mt-1 w-full rounded-xl border-slate-200 text-sm"
                            required
                        >
                        @error('amount')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Account Reference
                    </label>
                    <input
                        type="text"
                        name="account_reference"
                        value="{{ old('account_reference') }}"
                        placeholder="Loan number, account, or any reference"
                        class="mt-1 w-full rounded-xl border-slate-200 text-sm"
                    >
                    @error('account_reference')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-slate-500">
                        This appears in the client M-Pesa message. If left blank, the system generates one.
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Description (optional)
                    </label>
                    <input
                        type="text"
                        name="transaction_desc"
                        value="{{ old('transaction_desc') }}"
                        placeholder="e.g. Loan repayment"
                        class="mt-1 w-full rounded-xl border-slate-200 text-sm"
                    >
                    @error('transaction_desc')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Loan ID (optional)
                    </label>
                    <input
                        type="number"
                        name="loan_id"
                        value="{{ old('loan_id') }}"
                        placeholder="Attach to loan ID (optional)"
                        class="mt-1 w-full rounded-xl border-slate-200 text-sm"
                    >
                    @error('loan_id')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-slate-500">
                        If provided, the repayment can be auto-applied to this loan after a successful callback.
                    </p>
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
                        Send STK Push
                    </button>

                    <a href="{{ route('mpesa.stk-push.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700">
                        Cancel
                    </a>
                </div>
            </form>
        </x-admin.section>
    </div>
@endsection


