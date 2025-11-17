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
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Application #</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $loanApplication->application_number }}</dd>
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
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Loan Product</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $loanApplication->loanProduct->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Business</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $loanApplication->business_type }} · {{ $loanApplication->business_location }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Purpose</dt>
                    <dd class="mt-1 text-slate-700">{{ $loanApplication->purpose ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Team</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $loanApplication->team->name ?? 'Unassigned' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Loan Officer</dt>
                    <dd class="mt-1 text-slate-700">{{ $loanApplication->loanOfficer->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Credit Officer</dt>
                    <dd class="mt-1 text-slate-700">{{ $loanApplication->creditOfficer->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Finance Officer</dt>
                    <dd class="mt-1 text-slate-700">{{ $loanApplication->financeOfficer->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Repayment Period</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        @if($loanApplication->loan)
                            {{ $loanApplication->loan->term_months }} months
                        @else
                            {{ $loanApplication->duration_months ?? '—' }} months
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Number of Instalments</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        @if($loanApplication->loan && $loanApplication->loan->instalments)
                            {{ $loanApplication->loan->instalments->count() }}
                        @else
                            {{ $loanApplication->duration_months ?? '—' }}
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Repayment Frequency</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ ucfirst(str_replace('_', ' ', $loanApplication->repayment_frequency ?? 'monthly')) }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Weekly/Cycle Payment</dt>
                    <dd class="mt-1 font-semibold text-slate-900">
                        @if($loanApplication->weekly_payment_amount)
                            Weekly · KES {{ number_format($loanApplication->weekly_payment_amount, 2) }}
                        @elseif($loanApplication->repayment_cycle_amount)
                            Cycle · KES {{ number_format($loanApplication->repayment_cycle_amount, 2) }}
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Total Repayment</dt>
                    <dd class="mt-1 font-semibold text-slate-900">KES {{ number_format($loanApplication->total_repayment_amount ?? ($loanApplication->amount + $loanApplication->interest_amount + ($loanApplication->registration_fee ?? 0)), 2) }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Registration / Processing Fee</dt>
                    <dd class="mt-1 font-semibold text-slate-900">KES {{ number_format($loanApplication->registration_fee ?? 0, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Submitted</dt>
                    <dd class="mt-1 text-slate-700">{{ $loanApplication->created_at?->format('d M Y, H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-500">Last Updated</dt>
                    <dd class="mt-1 text-slate-700">{{ $loanApplication->updated_at?->diffForHumans() }}</dd>
                </div>
            </dl>
        </x-admin.section>
        @php
            $requiredRoleLabels = [
                'loan_officer' => 'Loan Officer or Marketer',
                'credit_officer' => 'Credit Officer',
                'finance_officer' => 'Finance Officer or Director',
                'director' => 'Director',
            ];
            $requiredRole = $requiredRoleLabels[$loanApplication->approval_stage] ?? 'authorized user';
        @endphp
        <x-admin.section title="Approval Action" description="Provide comments & approve or reject" class="space-y-5">
            @unless($canApprove)
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-700">
                    <p class="font-semibold">Action locked</p>
                    <p class="mt-1 text-xs text-amber-600">
                        Only a {{ $requiredRole }} can take action at this stage. You can still view the details, but approval buttons are disabled.
                    </p>
                </div>
            @endunless
            <form
                x-data="{ stage: '{{ $loanApplication->approval_stage }}', confirmApprove(event) { event.preventDefault(); Admin.confirmAction({ title: 'Approve Application?', text: 'This will move the application to the next stage.', confirmButtonText: 'Approve' }).then(confirmed => { if (confirmed) event.target.submit(); }); } }"
                method="POST"
                action="{{ route('approvals.approve', $loanApplication) }}"
                @if($canApprove)
                    @submit="confirmApprove($event)"
                @endif
                class="space-y-4"
            >
                @csrf
                <fieldset class="space-y-4" @disabled(!$canApprove)>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Comment</label>
                        <textarea name="comment" rows="3" class="mt-1 w-full rounded-xl border-slate-200 text-sm">{{ old('comment') }}</textarea>
                    </div>

                    @if($loanApplication->approval_stage === 'credit_officer')
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

                    @if($loanApplication->approval_stage === 'director')
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50/60 p-4 text-sm text-slate-700">
                            <p class="font-semibold text-slate-900">Finance officer has prepared disbursement instructions.</p>
                            <p class="mt-1 text-xs text-slate-500">Review and approve to release funds.</p>
                        </div>
                    @endif
                </fieldset>

                <button
                    type="submit"
                    @disabled(!$canApprove)
                    class="w-full rounded-xl px-4 py-2 text-sm font-semibold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 {{ $canApprove ? 'bg-emerald-500 hover:bg-emerald-600' : 'bg-slate-300 cursor-not-allowed' }}"
                >
                    Approve & Continue
                </button>
            </form>

            <form
                method="POST"
                action="{{ route('approvals.reject', $loanApplication) }}"
                class="space-y-3"
                x-data
                @if($canApprove)
                    @submit.prevent="Admin.confirmAction({ title: 'Reject Application?', text: 'This will mark the application as rejected.', icon: 'error', confirmButtonText: 'Reject' }).then(confirmed => { if(confirmed) $el.submit(); })"
                @endif
            >
                @csrf
                <fieldset class="space-y-3" @disabled(!$canApprove)>
                    <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rejection Reason</label>
                    <textarea name="rejection_reason" rows="3" class="w-full rounded-xl border-slate-200 text-sm" required>{{ old('rejection_reason') }}</textarea>
                </fieldset>
                <button
                    type="submit"
                    @disabled(!$canApprove)
                    class="w-full rounded-xl border px-4 py-2 text-sm font-semibold {{ $canApprove ? 'border-rose-300 bg-white text-rose-600 hover:bg-rose-50' : 'border-slate-200 bg-slate-100 text-slate-400 cursor-not-allowed' }}"
                >
                    Reject Application
                </button>
            </form>
        </x-admin.section>
    </div>
    @if($loanApplication->loan && $loanApplication->loan->instalments->isNotEmpty())
        <x-admin.section class="xl:col-span-3 mt-6" title="Repayment Schedule" description="Projected instalments from first to final payment">
            <div class="overflow-hidden rounded-2xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">#</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Due Date</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Principal</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Interest</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Total</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($loanApplication->loan->instalments->sortBy('due_date')->values() as $index => $instalment)
                            <tr class="text-slate-700">
                                <td class="px-4 py-2 text-xs font-semibold text-slate-500">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">{{ optional($instalment->due_date)->format('d M Y') }}</td>
                                <td class="px-4 py-2 text-right">KES {{ number_format($instalment->principal_amount, 2) }}</td>
                                <td class="px-4 py-2 text-right">KES {{ number_format($instalment->interest_amount, 2) }}</td>
                                <td class="px-4 py-2 text-right font-semibold text-slate-900">KES {{ number_format($instalment->total_amount, 2) }}</td>
                                <td class="px-4 py-2">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                        @class([
                                            'bg-emerald-50 text-emerald-700' => $instalment->status === 'paid',
                                            'bg-amber-50 text-amber-700' => $instalment->status === 'pending',
                                            'bg-rose-50 text-rose-700' => $instalment->status === 'overdue',
                                            'bg-slate-100 text-slate-500' => !in_array($instalment->status, ['paid','pending','overdue']),
                                        ])
                                    ">
                                        {{ ucfirst($instalment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-admin.section>
    @endif
@endsection

