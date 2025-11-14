@extends('layouts.admin', ['title' => 'Approval · ' . $loanApplication->application_number])

@section('header')
    Review {{ $loanApplication->application_number }}
@endsection

@section('content')
    <div class="grid gap-6 xl:grid-cols-3">
        <x-admin.section class="xl:col-span-2" title="Loan Snapshot" description="Double-check details before approving">
            <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Client</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $loanApplication->client->full_name }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Stage</dt>
                    <dd class="mt-1 font-semibold text-emerald-600">{{ ucfirst(str_replace('_', ' ', $loanApplication->approval_stage)) }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Amount Requested</dt>
                    <dd class="mt-1 font-semibold text-slate-900">KES {{ number_format($loanApplication->amount, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Interest</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ number_format($loanApplication->interest_rate ?? 0, 2) }}%</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Business</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $loanApplication->business_type }} · {{ $loanApplication->business_location }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Purpose</dt>
                    <dd class="mt-1 text-slate-700">{{ $loanApplication->purpose ?? '—' }}</dd>
                </div>
            </dl>
        </x-admin.section>

        <x-admin.section title="Approval Action" description="Provide comments & approve or reject" class="space-y-5">
            <form
                x-data="{ stage: '{{ $loanApplication->approval_stage }}', confirmApprove(event) { event.preventDefault(); Admin.confirmAction({ title: 'Approve Application?', text: 'This will move the application to the next stage.', confirmButtonText: 'Approve' }).then(confirmed => { if (confirmed) event.target.submit(); }); } }"
                method="POST"
                action="{{ route('approvals.approve', $loanApplication) }}"
                @submit="confirmApprove($event)"
                class="space-y-4"
            >
                @csrf
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Comment</label>
                    <textarea name="comment" rows="3" class="mt-1 w-full rounded-xl border-slate-200 text-sm">{{ old('comment') }}</textarea>
                </div>

                @if($loanApplication->approval_stage === 'collection_officer')
                    <div class="grid gap-3 text-sm md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Amount Approved</label>
                            <input type="number" name="amount_approved" class="mt-1 w-full rounded-xl border-slate-200" min="1000" value="{{ old('amount_approved', $loanApplication->amount_approved ?? $loanApplication->amount) }}" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Interest Rate (%)</label>
                            <input type="number" name="interest_rate" step="0.01" class="mt-1 w-full rounded-xl border-slate-200" value="{{ old('interest_rate', $loanApplication->interest_rate ?? 12) }}" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Duration (Months)</label>
                            <input type="number" name="duration_months" class="mt-1 w-full rounded-xl border-slate-200" value="{{ old('duration_months', $loanApplication->duration_months ?? 12) }}" required>
                        </div>
                    </div>
                @endif

                @if($loanApplication->approval_stage === 'finance_officer')
                    <div class="grid gap-3 text-sm md:grid-cols-2">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Amount to Disburse</label>
                            <input type="number" name="amount_approved" class="mt-1 w-full rounded-xl border-slate-200" value="{{ old('amount_approved', $loanApplication->amount_approved ?? $loanApplication->amount) }}" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Processing Fee</label>
                            <input type="number" name="processing_fee" step="0.01" class="mt-1 w-full rounded-xl border-slate-200" value="{{ old('processing_fee', 0) }}">
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Disbursement Method</label>
                            <input type="text" name="disbursement_method" class="mt-1 w-full rounded-xl border-slate-200" value="{{ old('disbursement_method', 'M-PESA B2C') }}" required>
                        </div>
                    </div>
                @endif

                <button type="submit" class="w-full rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                    Approve & Continue
                </button>
            </form>

            <form
                method="POST"
                action="{{ route('approvals.reject', $loanApplication) }}"
                class="space-y-3"
                x-data
                @submit.prevent="Admin.confirmAction({ title: 'Reject Application?', text: 'This will mark the application as rejected.', icon: 'error', confirmButtonText: 'Reject' }).then(confirmed => { if(confirmed) $el.submit(); })"
            >
                @csrf
                <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rejection Reason</label>
                <textarea name="rejection_reason" rows="3" class="w-full rounded-xl border-slate-200 text-sm" required>{{ old('rejection_reason') }}</textarea>
                <button type="submit" class="w-full rounded-xl border border-rose-300 bg-white px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50">
                    Reject Application
                </button>
            </form>
        </x-admin.section>
    </div>
@endsection

