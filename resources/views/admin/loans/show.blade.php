@extends('layouts.admin', ['title' => 'Loan Detail'])

@section('header')
    {{ $loan->loan_type }} · {{ $loan->client->full_name }}
@endsection

@section('content')
    <div class="grid gap-6 xl:grid-cols-3">
        <x-admin.section class="xl:col-span-2" title="Loan Overview">
            <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Client</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $loan->client->full_name }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Status</dt>
                    <dd class="mt-1 font-semibold text-emerald-600">{{ ucfirst($loan->status) }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Loan Product</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $loan->loanProduct->name ?? 'Custom Product' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Team</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $loan->team->name ?? 'Unassigned' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Amount Approved</dt>
                    <dd class="mt-1 font-semibold text-slate-900">KES {{ number_format($loan->amount_approved, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Outstanding</dt>
                    <dd class="mt-1 font-semibold text-rose-500">KES {{ number_format($loan->outstanding_balance, 2) }}</dd>
                </div>
            </dl>
        </x-admin.section>

        <x-admin.section title="Update Status">
            <form action="{{ route('loans.update', $loan) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                    <select name="status" class="mt-1 w-full rounded-xl border-slate-200">
                        @foreach(['pending', 'approved', 'disbursed', 'closed'] as $status)
                            <option value="{{ $status }}" @selected($loan->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Collection Officer</label>
                    <input type="text" value="{{ $loan->collectionOfficer->name ?? '—' }}" class="mt-1 w-full rounded-xl border-slate-200" readonly>
                </div>
                <div>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Next Due Date</label>
                    <input type="date" name="next_due_date" class="mt-1 w-full rounded-xl border-slate-200" value="{{ optional($loan->next_due_date)->format('Y-m-d') }}">
                </div>
                <button type="submit" class="w-full rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                    Update Loan
                </button>
            </form>
        </x-admin.section>
    </div>

    <x-admin.section title="Repayments & Instalments" class="mt-6">
        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Repayments</h3>
                <div class="mt-3 space-y-3 text-sm">
                    @forelse($loan->repayments as $repayment)
                        <div class="rounded-xl border border-slate-200 px-4 py-3">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-slate-900">KES {{ number_format($repayment->amount, 2) }}</span>
                                <span class="text-xs text-slate-500">{{ $repayment->paid_at?->format('d M Y') }}</span>
                            </div>
                            <p class="text-xs text-slate-500">{{ ucfirst($repayment->payment_method) }} · {{ $repayment->reference }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No repayments recorded yet.</p>
                    @endforelse
                </div>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Instalment Schedule</h3>
                <div class="mt-3 space-y-3 text-sm">
                    @forelse($loan->instalments as $instalment)
                        <div class="rounded-xl border border-slate-200 px-4 py-3">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-slate-900">{{ $instalment->due_date->format('d M Y') }}</span>
                                <span class="text-xs text-slate-500">{{ ucfirst($instalment->status) }}</span>
                            </div>
                            <p class="text-xs text-slate-500">
                                Total {{ number_format($instalment->total_amount, 2) }} · Paid {{ number_format($instalment->amount_paid, 2) }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No instalment schedule available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </x-admin.section>
@endsection

