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
                @php
                    $loanFormUrl = $loanApplication->publicStorageUrl($loanApplication->loan_form_path);
                    $mpesaUrl = $loanApplication->publicStorageUrl($loanApplication->mpesa_statement_path);
                    $businessPhotoUrl = $loanApplication->publicStorageUrl($loanApplication->business_photo_path);
                    $supportingPaths = $loanApplication->supportingDocumentPathsList();
                @endphp
                <div class="grid gap-4 md:grid-cols-2 text-sm text-slate-700">
                    @if($loanFormUrl)
                        <div class="flex flex-col gap-2 rounded-xl border border-slate-200 px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 12l9-4.5-9-4.5-9 4.5 9 4.5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 12l9-4.5-9-4.5-9 4.5 9 4.5zm0 0v9" />
                                    </svg>
                                </span>
                                <div>
                                    <span class="block font-semibold text-slate-900">Loan Form</span>
                                    <span class="text-xs text-slate-500">{{ basename($loanApplication->loan_form_path) }}</span>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 pl-12 text-xs">
                                <a href="{{ $loanFormUrl }}" target="_blank" rel="noopener" class="font-semibold text-emerald-600 hover:text-emerald-700">Open</a>
                                <a href="{{ $loanFormUrl }}" download="{{ basename($loanApplication->loan_form_path) }}" class="text-slate-600 hover:text-slate-800">Download</a>
                            </div>
                        </div>
                    @endif

                    @if($mpesaUrl)
                        <div class="flex flex-col gap-2 rounded-xl border border-slate-200 px-4 py-3">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-sky-100 text-sky-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </span>
                                <div>
                                    <span class="block font-semibold text-slate-900">M-PESA Statement</span>
                                    <span class="text-xs text-slate-500">{{ basename($loanApplication->mpesa_statement_path) }}</span>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 pl-12 text-xs">
                                <a href="{{ $mpesaUrl }}" target="_blank" rel="noopener" class="font-semibold text-emerald-600 hover:text-emerald-700">Open</a>
                                <a href="{{ $mpesaUrl }}" download="{{ basename($loanApplication->mpesa_statement_path) }}" class="text-slate-600 hover:text-slate-800">Download</a>
                            </div>
                        </div>
                    @endif

                    @if($businessPhotoUrl)
                        <div class="rounded-xl border border-slate-200 p-3 md:col-span-2">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Business Photo</p>
                            @if(\App\Models\LoanApplication::pathLooksLikeImage($loanApplication->business_photo_path))
                                <img src="{{ $businessPhotoUrl }}" alt="Business photo" class="mt-2 max-h-64 w-full rounded-lg object-contain bg-slate-50">
                            @else
                                <p class="mt-2 text-sm text-slate-600">{{ basename($loanApplication->business_photo_path) }}</p>
                            @endif
                            <div class="mt-3 flex flex-wrap gap-2 text-xs">
                                <a href="{{ $businessPhotoUrl }}" target="_blank" rel="noopener" class="font-semibold text-emerald-600 hover:text-emerald-700">Open</a>
                                <a href="{{ $businessPhotoUrl }}" download="{{ basename($loanApplication->business_photo_path) }}" class="text-slate-600 hover:text-slate-800">Download</a>
                            </div>
                        </div>
                    @endif
                </div>

                @if(count($supportingPaths))
                    <div class="mt-6">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Additional supporting files</p>
                        <ul class="mt-3 space-y-2">
                            @foreach($supportingPaths as $idx => $docPath)
                                @php $docUrl = $loanApplication->publicStorageUrl($docPath); @endphp
                                @if($docUrl)
                                    <li class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-200 px-4 py-3 text-sm">
                                        <span class="font-medium text-slate-900">{{ basename($docPath) ?: 'Document ' . ($idx + 1) }}</span>
                                        <span class="flex gap-3 text-xs">
                                            <a href="{{ $docUrl }}" target="_blank" rel="noopener" class="font-semibold text-emerald-600 hover:text-emerald-700">Open</a>
                                            <a href="{{ $docUrl }}" download="{{ basename($docPath) ?: 'document-' . ($idx + 1) }}" class="text-slate-600 hover:text-slate-800">Download</a>
                                        </span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mt-6">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">KYC Documents</p>
                    <div class="mt-3 grid gap-3 md:grid-cols-2">
                        @forelse($loanApplication->kycDocuments as $document)
                            @if($document->file_url)
                                <div class="flex flex-col justify-between gap-2 rounded-xl border border-slate-200 px-4 py-3 text-sm hover:border-emerald-300">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ ucfirst($document->document_type) }}</p>
                                        <p class="text-xs text-slate-500">{{ $document->document_name }}</p>
                                    </div>
                                    <div class="flex flex-wrap gap-3 text-xs">
                                        <a href="{{ $document->file_url }}" target="_blank" rel="noopener" class="font-semibold text-emerald-600">Open</a>
                                        <a href="{{ $document->file_url }}" download="{{ basename($document->file_path) }}" class="text-slate-600 hover:text-slate-800">Download</a>
                                    </div>
                                </div>
                            @else
                                <div class="rounded-xl border border-dashed border-slate-200 px-4 py-3 text-sm text-slate-500">
                                    {{ ucfirst($document->document_type) }} — file path missing or invalid
                                </div>
                            @endif
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

