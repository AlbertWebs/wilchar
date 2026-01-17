@extends('layouts.admin', ['title' => $loanApplication->application_number])

@section('header')
    {{ $loanApplication->application_number }}
@endsection

@section('content')
    <div class="space-y-6">
        <div class="grid gap-6 xl:grid-cols-3">
            <x-admin.section class="xl:col-span-2" title="Applicant Overview" description="Key loan details and officers">
                <dl class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Client</dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            {{ $loanApplication->client->full_name }}
                            <span class="ml-2 text-xs text-slate-500">{{ $loanApplication->client->phone }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Team</dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            {{ $loanApplication->team->name ?? 'Unassigned' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Loan Product</dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            {{ $loanApplication->loanProduct->name ?? $loanApplication->loan_type }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-slate-500">Business Type</dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            {{ $loanApplication->business_type }} · {{ $loanApplication->business_location }}
                        </dd>
                    </div>
                </dl>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Financial Summary</p>
                        <div class="mt-4 space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Amount Requested</span>
                                <span class="font-semibold text-slate-900">KES {{ number_format($loanApplication->amount, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Amount Approved</span>
                                <span class="font-semibold text-emerald-600">
                                    {{ $loanApplication->amount_approved ? 'KES ' . number_format($loanApplication->amount_approved, 2) : 'Pending' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Interest</span>
                                <span class="font-semibold text-slate-900">
                                    {{ number_format($loanApplication->interest_rate, 2) }}% · KES {{ number_format($loanApplication->interest_amount ?? 0, 2) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Total Repayable</span>
                                <span class="font-semibold text-slate-900">
                                    KES {{ number_format($loanApplication->total_repayment_amount ?? 0, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Officers</p>
                        <dl class="mt-4 space-y-2 text-sm text-slate-700">
                            <div class="flex items-center justify-between">
                                <dt>Loan Officer</dt>
                                <dd class="font-semibold">{{ $loanApplication->loanOfficer->name ?? 'Not assigned' }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt>Credit Officer</dt>
                                <dd class="font-semibold">{{ $loanApplication->creditOfficer->name ?? 'Not assigned' }}</dd>
                            </div>
                            <div>
                                <dt>Collection Officer</dt>
                                <dd class="font-semibold">{{ $loanApplication->collectionOfficer->name ?? 'Not assigned' }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt>Finance Officer</dt>
                                <dd class="font-semibold">{{ $loanApplication->financeOfficer->name ?? 'Not assigned' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </x-admin.section>

            <x-admin.section title="Status" description="Current stage & workflow">
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2">
                        <span class="text-slate-500">Stage</span>
                        <span class="font-semibold text-slate-900">{{ ucfirst(str_replace('_', ' ', $loanApplication->approval_stage)) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-3 py-2">
                        <span class="text-slate-500">Status</span>
                        <span class="font-semibold {{ $loanApplication->status === 'approved' ? 'text-emerald-600' : ($loanApplication->status === 'rejected' ? 'text-rose-500' : 'text-slate-900') }}">
                            {{ ucfirst(str_replace('_', ' ', $loanApplication->status)) }}
                        </span>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white px-3 py-3">
                        <p class="text-xs uppercase tracking-wide text-slate-500">Timeline</p>
                        <ol class="mt-3 space-y-3 text-xs text-slate-600">
                            @foreach($loanApplication->approvals()->latest()->get() as $approval)
                                <li>
                                    <span class="font-semibold text-slate-800">{{ ucfirst(str_replace('_', ' ', $approval->approval_level)) }}</span>
                                    by {{ $approval->approver->name ?? 'System' }} —
                                    <span class="text-slate-400">{{ $approval->approved_at->diffForHumans() }}</span>
                                    @if($approval->comment)
                                        <p class="mt-1 italic text-slate-500">"{{ $approval->comment }}"</p>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    @php
                        $disbursement = $loanApplication->loan?->disbursements->first() ?? $loanApplication->disbursements->first() ?? null;
                        $hasRole = auth()->user()->hasAnyRole(['Admin', 'Finance', 'Director']);
                        $hasSuccessfulDisbursement = $disbursement && $disbursement->status === 'success';
                    @endphp

                    @if($loanApplication->isApproved() && $hasRole && !$hasSuccessfulDisbursement)
                        <div class="mt-3 flex flex-wrap gap-2">
                            @if($disbursement && in_array($disbursement->status, ['pending', 'processing']))
                                <a
                                    href="{{ route('disbursements.show', $disbursement) }}"
                                    class="inline-flex items-center rounded-lg bg-emerald-500 px-3 py-1 text-xs font-semibold text-white hover:bg-emerald-600"
                                >
                                    View Disbursement
                                </a>
                            @elseif(!$disbursement)
                                <a
                                    href="{{ route('disbursements.create', $loanApplication) }}"
                                    class="inline-flex items-center rounded-lg bg-emerald-500 px-3 py-1 text-xs font-semibold text-white hover:bg-emerald-600"
                                >
                                    Disburse Now
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </x-admin.section>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <x-admin.section title="Supporting Documents" description="KYC & business documents">
                <div class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                    @if($loanApplication->loan_form_path)
                        <a href="{{ Storage::disk('public')->url($loanApplication->loan_form_path) }}" target="_blank" class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 hover:border-emerald-300">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 12l9-4.5-9-4.5-9 4.5 9 4.5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 12l9-4.5-9-4.5-9 4.5 9 4.5zm0 0v9" />
                                </svg>
                            </span>
                            <span>
                                <span class="block font-semibold text-slate-900">Loan Form</span>
                                <span class="text-xs text-slate-500">View Uploaded</span>
                            </span>
                        </a>
                    @endif

                    @if($loanApplication->mpesa_statement_path)
                        <a href="{{ Storage::disk('public')->url($loanApplication->mpesa_statement_path) }}" target="_blank" class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 hover:border-emerald-300">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-sky-100 text-sky-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <span>
                                <span class="block font-semibold text-slate-900">M-PESA Statement</span>
                                <span class="text-xs text-slate-500">View Uploaded</span>
                            </span>
                        </a>
                    @endif

                    @if($loanApplication->business_photo_path)
                        <div class="rounded-xl border border-slate-200 p-3">
                            <p class="text-xs font-semibold text-slate-500">Business Photo</p>
                            <img src="{{ Storage::disk('public')->url($loanApplication->business_photo_path) }}" class="mt-2 h-32 w-full rounded-lg object-cover">
                        </div>
                    @endif
                </div>

                <div class="mt-6">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">KYC Documents</p>
                    <div class="mt-3 grid gap-3 md:grid-cols-2">
                        @forelse($loanApplication->kycDocuments as $document)
                            <a href="{{ Storage::disk('public')->url($document->file_path) }}" target="_blank" class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 text-sm hover:border-emerald-300">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ ucfirst($document->document_type) }}</p>
                                    <p class="text-xs text-slate-500">{{ $document->document_name }}</p>
                                </div>
                                <span class="text-xs text-emerald-600">View</span>
                            </a>
                        @empty
                            <p class="text-sm text-slate-500">No KYC documents uploaded.</p>
                        @endforelse
                    </div>
                </div>
            </x-admin.section>

            <x-admin.section title="Activity Log" description="Important actions & approvals">
                <div class="space-y-4 text-xs text-slate-600">
                    @forelse($auditLogs as $log)
                        <div class="rounded-xl border border-slate-200 px-4 py-3">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-slate-800">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</span>
                                <span class="text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mt-1 text-slate-500">{{ $log->description }}</p>
                            @if($log->user)
                                <p class="mt-1 text-slate-400">By {{ $log->user->name }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No activity logged yet.</p>
                    @endforelse
                </div>
            </x-admin.section>
        </div>
    </div>
@endsection

