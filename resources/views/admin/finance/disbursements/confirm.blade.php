@extends('layouts.admin', ['title' => 'Authorize Disbursement'])

@section('header')
    Authorize Disbursement
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Disbursement Details" description="Verify the payout before entering the OTP.">
            <dl class="grid gap-4 md:grid-cols-3 text-sm text-slate-600">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Application</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $disbursement->loanApplication->application_number }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Client</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $disbursement->loanApplication->client->full_name }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Prepared By</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $disbursement->preparedBy->name ?? 'Finance' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Amount</dt>
                    <dd class="mt-1 text-2xl font-semibold text-slate-900">KES {{ number_format($disbursement->amount, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Recipient Phone</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $disbursement->recipient_phone }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Notes</dt>
                    <dd class="mt-1 text-slate-700">{{ $disbursement->processing_notes ?? '—' }}</dd>
                </div>
            </dl>
        </x-admin.section>

        <x-admin.section title="Enter OTP" description="The OTP was sent to your email (and phone via SMS).">
            <form method="POST" action="{{ route('finance-disbursements.confirm.store', $disbursement) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">One-Time Password</label>
                    <input
                        type="text"
                        name="otp"
                        maxlength="6"
                        class="mt-1 w-full rounded-xl border-slate-200 text-lg tracking-[0.5em] text-center"
                        placeholder="••••••"
                        required
                    >
                </div>
                <p class="text-xs text-slate-400">
                    This code expires {{ optional($disbursement->otp_expires_at)->diffForHumans() ?? 'soon' }}.
                </p>
                <button type="submit" class="w-full rounded-xl bg-emerald-500 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-600">
                    Verify OTP & Disburse
                </button>
            </form>
        </x-admin.section>
    </div>
@endsection

