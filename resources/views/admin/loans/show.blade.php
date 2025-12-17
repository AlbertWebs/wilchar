@extends('layouts.admin', ['title' => 'Loan Detail'])

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">{{ $loan->loan_type }}</h1>
            <p class="text-sm text-slate-500">{{ $loan->client->full_name }} · {{ $loan->client->phone }}</p>
        </div>
        @if($loan->status === 'disbursed' && $loan->outstanding_balance > 0)
            <button
                type="button"
                @click="$store.modal?.open('payment-modal')"
                class="rounded-xl bg-emerald-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-400"
            >
                <svg class="mr-2 inline h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Record Payment
            </button>
        @endif
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Loan Summary Cards -->
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Total Amount</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">KES {{ number_format($loan->total_amount, 2) }}</p>
                <p class="mt-1 text-xs text-slate-500">Principal: {{ number_format($loan->amount_approved, 2) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Outstanding Balance</p>
                <p class="mt-2 text-2xl font-semibold text-rose-500">KES {{ number_format($loan->outstanding_balance, 2) }}</p>
                <p class="mt-1 text-xs text-slate-500">Total Paid: {{ number_format($totalPaid, 2) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Interest Amount</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">KES {{ number_format($loan->interest_amount, 2) }}</p>
                <p class="mt-1 text-xs text-slate-500">Rate: {{ number_format($loan->interest_rate, 2) }}%</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Progress</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $totalInstalments > 0 ? round(($paidInstalments / $totalInstalments) * 100, 1) : 0 }}%</p>
                <p class="mt-1 text-xs text-slate-500">{{ $paidInstalments }} of {{ $totalInstalments }} instalments</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <!-- Loan Details -->
            <x-admin.section class="xl:col-span-2" title="Loan Information">
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-slate-900">Basic Details</h3>
                        <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Loan Type</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $loan->loan_type }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                        {{ $loan->status === 'disbursed' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                        {{ $loan->status === 'closed' ? 'bg-slate-100 text-slate-800' : '' }}
                                        {{ $loan->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $loan->status === 'approved' ? 'bg-blue-100 text-blue-800' : '' }}
                                    ">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </dd>
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
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Term</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $loan->term_months }} months</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Repayment Frequency</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ ucfirst($loan->repayment_frequency ?? 'Monthly') }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Amount Requested</dt>
                                <dd class="mt-1 font-semibold text-slate-900">KES {{ number_format($loan->amount_requested, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Amount Approved</dt>
                                <dd class="mt-1 font-semibold text-slate-900">KES {{ number_format($loan->amount_approved, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Processing Fee</dt>
                                <dd class="mt-1 font-semibold text-slate-900">KES {{ number_format($loan->processing_fee ?? 0, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Late Fees</dt>
                                <dd class="mt-1 font-semibold text-slate-900">KES {{ number_format($loan->late_fee_accrued ?? 0, 2) }}</dd>
                            </div>
                            @if($loan->next_due_date)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Next Due Date</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $loan->next_due_date->format('d M Y') }}</dd>
                            </div>
                            @endif
                            @if($nextInstalment)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Next Instalment</dt>
                                <dd class="mt-1 font-semibold text-slate-900">
                                    KES {{ number_format($nextInstalment->total_amount - $nextInstalment->amount_paid, 2) }}
                                    <span class="text-xs text-slate-500">(Due: {{ $nextInstalment->due_date->format('d M Y') }})</span>
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Client Information -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-slate-900">Client Information</h3>
                        <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Full Name</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $loan->client->full_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Phone</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $loan->client->phone }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Email</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $loan->client->email ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">ID Number</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $loan->client->id_number ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Officer Assignments -->
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-slate-900">Officer Assignments</h3>
                        <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Collection Officer</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $loan->collectionOfficer->name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Recovery Officer</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $loan->recoveryOfficer->name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Finance Officer</dt>
                                <dd class="mt-1 font-semibold text-slate-900">{{ $loan->financeOfficer->name ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Disbursements -->
                    @if($loan->disbursements->count() > 0)
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-slate-900">Disbursements</h3>
                        <div class="space-y-3">
                            @foreach($loan->disbursements as $disbursement)
                                <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-slate-900">KES {{ number_format($disbursement->amount, 2) }}</p>
                                            <p class="text-xs text-slate-500">
                                                {{ ucfirst($disbursement->method) }} · {{ $disbursement->disbursement_date->format('d M Y') }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                            {{ $disbursement->status === 'success' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                            {{ $disbursement->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $disbursement->status === 'failed' ? 'bg-rose-100 text-rose-800' : '' }}
                                        ">
                                            {{ ucfirst($disbursement->status) }}
                                        </span>
                                    </div>
                                    @if($disbursement->reference)
                                        <p class="mt-1 text-xs text-slate-500">Reference: {{ $disbursement->reference }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </x-admin.section>

            <!-- Quick Actions & Stats -->
            <x-admin.section title="Quick Actions">
                <div class="space-y-4">
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
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Next Due Date</label>
                            <input type="date" name="next_due_date" class="mt-1 w-full rounded-xl border-slate-200" value="{{ optional($loan->next_due_date)->format('Y-m-d') }}">
                        </div>
                        <button type="submit" class="w-full rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                            Update Loan
                        </button>
                    </form>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <h4 class="text-xs font-semibold uppercase tracking-wide text-slate-500">Loan Statistics</h4>
                        <dl class="mt-3 space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-600">Total Disbursed</dt>
                                <dd class="font-semibold text-slate-900">KES {{ number_format($totalDisbursed, 2) }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-600">Total Paid</dt>
                                <dd class="font-semibold text-emerald-600">KES {{ number_format($totalPaid, 2) }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-slate-600">Repayments Count</dt>
                                <dd class="font-semibold text-slate-900">{{ $loan->repayments->count() }}</dd>
                            </div>
                            @if($overdueInstalments > 0)
                            <div class="flex items-center justify-between">
                                <dt class="text-rose-600">Overdue Instalments</dt>
                                <dd class="font-semibold text-rose-600">{{ $overdueInstalments }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </x-admin.section>
        </div>

        <!-- Repayments & Instalments -->
        <div class="grid gap-6 lg:grid-cols-2">
            <x-admin.section title="Repayment History">
                <div class="space-y-3">
                    @forelse($loan->repayments as $repayment)
                        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">KES {{ number_format($repayment->amount, 2) }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ ucfirst($repayment->payment_method) }}
                                        @if($repayment->reference)
                                            · {{ $repayment->reference }}
                                        @endif
                                    </p>
                                    @if($repayment->receiver)
                                        <p class="text-xs text-slate-400">Received by {{ $repayment->receiver->name }}</p>
                                    @endif
                                </div>
                                <span class="text-xs text-slate-500">{{ $repayment->paid_at?->format('d M Y') }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No repayments recorded yet.</p>
                    @endforelse
                </div>
            </x-admin.section>

            <x-admin.section title="Instalment Schedule">
                <div class="space-y-3">
                    @forelse($loan->instalments as $instalment)
                        <div class="rounded-xl border border-slate-200 bg-white px-4 py-3
                            {{ $instalment->status === 'overdue' ? 'border-rose-200 bg-rose-50' : '' }}
                            {{ $instalment->status === 'paid' ? 'border-emerald-200 bg-emerald-50' : '' }}
                        ">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $instalment->due_date->format('d M Y') }}</p>
                                    <p class="text-xs text-slate-500">
                                        Amount: KES {{ number_format($instalment->total_amount, 2) }}
                                        @if($instalment->amount_paid > 0)
                                            · Paid: KES {{ number_format($instalment->amount_paid, 2) }}
                                        @endif
                                    </p>
                                </div>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    {{ $instalment->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ $instalment->status === 'overdue' ? 'bg-rose-100 text-rose-800' : '' }}
                                    {{ $instalment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                ">
                                    {{ ucfirst($instalment->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No instalment schedule available.</p>
                    @endforelse
                </div>
            </x-admin.section>
        </div>
    </div>

    <!-- Payment Modal -->
    <x-modal name="payment-modal" title="Record Payment">
        <form
            action="{{ route('collections.store') }}"
            method="POST"
            x-ajax="{
                successMessage: { title: 'Success', text: 'Payment recorded successfully.' },
                onSuccess(response) {
                    window.location.reload();
                }
            }"
            class="space-y-4"
        >
            @csrf
            <input type="hidden" name="loan_id" value="{{ $loan->id }}">

            <div>
                <label class="text-sm font-medium text-slate-700">Payment Amount (KES)</label>
                <input
                    type="number"
                    name="amount"
                    step="0.01"
                    min="0.01"
                    max="{{ $loan->outstanding_balance }}"
                    value="{{ $nextInstalment ? ($nextInstalment->total_amount - $nextInstalment->amount_paid) : $loan->outstanding_balance }}"
                    class="mt-1 w-full rounded-xl border-slate-200"
                    required
                >
                <p class="mt-1 text-xs text-slate-500">Outstanding: KES {{ number_format($loan->outstanding_balance, 2) }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">Payment Method</label>
                <select name="payment_method" class="mt-1 w-full rounded-xl border-slate-200" required>
                    <option value="">Select method</option>
                    <option value="mpesa">M-Pesa</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="cash">Cash</option>
                    <option value="cheque">Cheque</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">Payment Date</label>
                <input
                    type="date"
                    name="paid_at"
                    value="{{ now()->format('Y-m-d') }}"
                    class="mt-1 w-full rounded-xl border-slate-200"
                    required
                >
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">Reference/Receipt Number</label>
                <input
                    type="text"
                    name="reference"
                    placeholder="e.g., M-Pesa receipt number"
                    class="mt-1 w-full rounded-xl border-slate-200"
                >
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">Receipt URL (Optional)</label>
                <input
                    type="url"
                    name="receipt_url"
                    placeholder="https://..."
                    class="mt-1 w-full rounded-xl border-slate-200"
                >
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <button
                    type="button"
                    @click="$store.modal?.close()"
                    class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-400"
                >
                    Record Payment
                </button>
            </div>
        </form>
    </x-modal>
@endsection
