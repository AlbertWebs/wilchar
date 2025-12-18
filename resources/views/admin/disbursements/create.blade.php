@extends('layouts.admin', ['title' => 'Initiate Disbursement'])

@section('header')
    Initiate Disbursement
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Disbursement Details" description="Review the loan details and provide disbursement information.">
            <dl class="grid gap-4 md:grid-cols-3 text-sm text-slate-600">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Application</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        {{ $loanApplication->application_number }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Client</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        {{ $loanApplication->client->full_name }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Approved Amount</dt>
                    <dd class="mt-1 text-2xl font-semibold text-slate-900">
                        KES {{ number_format($loanApplication->amount_approved ?? $loanApplication->amount, 2) }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Loan Product</dt>
                    <dd class="mt  -1 font-semibold text-slate-900">
                        {{ $loanApplication->loanProduct->name ?? $loanApplication->loan_type }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Recipient Phone</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        {{ $loanApplication->client->phone ?? '—' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Status</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        {{ ucfirst($loanApplication->status) }} · {{ ucfirst(str_replace('_', ' ', $loanApplication->approval_stage)) }}
                    </dd>
                </div>
            </dl>
        </x-admin.section>

        <x-admin.section title="Disbursement Instructions" description="Enter the disbursement amount and recipient M-Pesa number.">
            <form method="POST" action="{{ route('disbursements.store', $loanApplication) }}" class="space-y-4">
                @csrf
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-slate-700">Amount to Disburse</label>
                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            step="0.01"
                            min="1"
                            value="{{ old('amount', $loanApplication->amount_approved ?? $loanApplication->amount) }}"
                            class="mt-1 block w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500"
                            required
                        >
                        @error('amount')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="recipient_phone" class="block text-sm font-medium text-slate-700">Recipient M-Pesa Number</label>
                        <input
                            type="text"
                            name="recipient_phone"
                            id="recipient_phone"
                            value="{{ old('recipient_phone', $loanApplication->client->phone) }}"
                            class="mt-1 block w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500"
                            placeholder="2547XXXXXXXX"
                            required
                        >
                        @error('recipient_phone')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="remarks" class="block text-sm font-medium text-slate-700">Remarks (optional)</label>
                    <textarea
                        name="remarks"
                        id="remarks"
                        rows="3"
                        class="mt-1 block w-full rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500"
                        placeholder="Additional details about this disbursement..."
                    >{{ old('remarks') }}</textarea>
                    @error('remarks')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('loan-applications.show', $loanApplication) }}" class="rounded-lg border border-slate-200 px  -4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center rounded-lg bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600"
                    >
                        Proceed to Disburse via M-Pesa
                    </button>
                </div>
            </form>
        </x-admin.section>
    </div>
@endsection


