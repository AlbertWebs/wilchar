@extends('layouts.admin', ['title' => 'New B2B Payment'])

@section('header')
    New B2B Payment
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section
            title="Initiate B2B Payment"
            description="Send a payment from your M-Pesa shortcode to another shortcode."
        >
            <form method="POST" action="{{ route('mpesa.b2b.store') }}" class="space-y-5 max-w-xl">
                @csrf

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Party B Shortcode
                    </label>
                    <input
                        type="text"
                        name="party_b"
                        value="{{ old('party_b') }}"
                        placeholder="Target paybill or till number"
                        class="mt-1 w-full rounded-xl border-slate-200 text-sm"
                        required
                    >
                    @error('party_b')
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

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Account Reference (optional)
                    </label>
                    <input
                        type="text"
                        name="account_reference"
                        value="{{ old('account_reference') }}"
                        placeholder="Reference to appear on the receiving side"
                        class="mt-1 w-full rounded-xl border-slate-200 text-sm"
                    >
                    @error('account_reference')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wide text-slate-600">
                        Remarks (optional)
                    </label>
                    <input
                        type="text"
                        name="remarks"
                        value="{{ old('remarks') }}"
                        placeholder="Short description e.g. Float top-up"
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
                        Send B2B Payment
                    </button>

                    <a href="{{ route('mpesa.b2b.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-700">
                        Cancel
                    </a>
                </div>
            </form>
        </x-admin.section>
    </div>
@endsection


