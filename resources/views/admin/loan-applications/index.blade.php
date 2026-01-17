@extends('layouts.admin', ['title' => 'Loan Applications'])

@section('header')
    Loan Applications
@endsection

@section('content')
    <div
        x-data="{
            filters: {
                status: '{{ request('status') }}',
                stage: '{{ request('stage') }}',
                team_id: '{{ request('team_id') }}',
            },
            init() {
                window.addEventListener('loan-applications:refresh', () => {
                    window.location.reload();
                });
            },
            openEditModal(id) {
                const urlTemplate = '{{ route('loan-applications.edit', ['loanApplication' => '__ID__']) }}';
                const url = urlTemplate.replace('__ID__', id);
                Admin.showModal({ title: 'Edit Loan Application', url, method: 'get', size: 'xl' });
            },
            openDisbursementModal(disbursementId) {
                console.log('Button clicked, disbursementId:', disbursementId);
                // Test if modal store is accessible
                if (window.Alpine && window.Alpine.store('modal')) {
                    console.log('Modal store is accessible');
                } else {
                    console.error('Modal store not accessible');
                }
                window.openDisbursementModalHandler(disbursementId);
            }
        }"
        class="space-y-6"
        x-ref="loanApplicationsPage"
    >
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-sm text-slate-600">
                <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2">
                    <span class="text-xs uppercase tracking-wide text-slate-500">Total</span>
                    <span class="font-semibold text-slate-900">{{ number_format($applications->total()) }}</span>
                </div>
                <div class="hidden md:block h-6 w-px bg-slate-200"></div>
                <div class="flex flex-wrap gap-2">
                    <select class="rounded-xl border-slate-200 text-sm" x-model="filters.stage" @change="window.location = '{{ route('loan-applications.index') }}?stage=' + filters.stage">
                        <option value="">Stage: All</option>
                        <option value="loan_officer" @selected(request('stage') === 'loan_officer')>Loan Officer</option>
                        <option value="credit_officer" @selected(request('stage') === 'credit_officer')>Credit Officer</option>
                        <option value="finance_officer" @selected(request('stage') === 'finance_officer')>Finance Officer</option>
                        <option value="director" @selected(request('stage') === 'director')>Director</option>
                        <option value="completed" @selected(request('stage') === 'completed')>Completed</option>
                    </select>
                    <select class="rounded-xl border-slate-200 text-sm" x-model="filters.status" @change="window.location = '{{ route('loan-applications.index') }}?status=' + filters.status">
                        <option value="">Status: All</option>
                        <option value="submitted" @selected(request('status') === 'submitted')>Submitted</option>
                        <option value="under_review" @selected(request('status') === 'under_review')>Under Review</option>
                        <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                    </select>
                </div>
            </div>
            <a
                href="{{ route('loan-applications.create') }}"
                class="flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-400"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                New Application
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Application</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Team & Officers</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Stage</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($applications as $application)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-4">
                                <div class="font-semibold text-slate-900">{{ $application->application_number }}</div>
                                <div class="text-xs text-slate-500">
                                    {{ $application->client->full_name }} · {{ $application->client->phone }}
                                </div>
                                <div class="mt-1 text-xs text-slate-400">
                                    Submitted {{ $application->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-4 py-4 text-slate-600">
                                <p class="font-medium">{{ $application->team->name ?? '—' }}</p>
                                <p class="text-xs text-slate-500">
                                    Loan Officer: {{ $application->loanOfficer->name ?? 'Pending' }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    Credit Officer: {{ $application->creditOfficer->name ?? 'Pending' }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    Collections: {{ $application->collectionOfficer->name ?? 'Pending' }}
                                </p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-semibold text-slate-900">KES {{ number_format($application->amount, 2) }}</p>
                                <p class="text-xs text-slate-500">Interest {{ number_format($application->interest_rate, 2) }}%</p>
                                <p class="text-xs text-slate-500">Total {{ number_format($application->total_repayment_amount ?? 0, 2) }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600"
                                    title="Current approval stage"
                                >
                                    {{ ucfirst(str_replace('_', ' ', $application->approval_stage)) }}
                                </span>
                                <span
                                    class="ml-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                        {{ $application->status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($application->status === 'rejected' ? 'bg-rose-100 text-rose-600' : 'bg-amber-100 text-amber-700') }}"
                                    title="Application status"
                                >
                                    {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                </span>
                                @if($application->status === 'approved' && $application->approval_stage === 'completed')
                                    @php
                                        $disbursement = $application->loan?->disbursements->first() ?? $application->disbursements->first() ?? null;
                                    @endphp
                                    @if($disbursement)
                                        @php
                                            $desc = strtolower($disbursement->mpesa_response_description ?? '');
                                            $wasAborted = str_contains($desc, 'aborted by user');
                                        @endphp
                                        <span
                                            class="mt-1 block inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                                {{ $disbursement->status === 'success'
                                                    ? 'bg-emerald-100 text-emerald-700'
                                                    : ($wasAborted
                                                        ? 'bg-rose-100 text-rose-600'
                                                        : ($disbursement->status === 'failed'
                                                            ? 'bg-rose-100 text-rose-600'
                                                            : 'bg-amber-100 text-amber-700')) }}"
                                            title="Disbursement status"
                                        >
                                            @if($disbursement->status === 'success')
                                                ✓ Disbursed
                                            @elseif($wasAborted)
                                                ✗ Disbursement Cancelled
                                            @elseif($disbursement->status === 'failed')
                                                ✗ Disbursement Failed
                                            @else
                                                ⏳ Pending Disbursement
                                            @endif
                                        </span>
                                    @else
                                        <span class="mt-1 block inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                            Not Disbursed
                                        </span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('loan-applications.show', $application) }}" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                        View
                                    </a>
                                    @if($application->approval_stage === 'loan_officer' && $application->status === 'submitted')
                                        <button @click="openEditModal({{ $application->id }})" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                            Edit
                                        </button>
                                    @endif
                                    @php
                                        $disbursement = $application->loan?->disbursements->first() ?? $application->disbursements->first() ?? null;
                                        $hasRole = auth()->user()->hasAnyRole(['Admin', 'Finance', 'Director']);
                                        $desc = strtolower($disbursement->mpesa_response_description ?? '');
                                        $wasAborted = $disbursement && str_contains($desc, 'aborted by user');
                                        // Inline OTP-based disbursement is allowed when disbursement is pending, processing, or failed (incl. previously cancelled/failed)
                                        $canInlineDisburse = $disbursement
                                            && in_array($disbursement->status, ['pending', 'processing', 'failed'], true)
                                            && $hasRole;
                                        // If loan is approved & completed and there is NO disbursement yet, allow privileged user to create one via legacy form
                                        $canCreateDisbursement = !$disbursement && $application->isApproved() && $hasRole;
                                        $showApprove = $application->status !== 'approved' || $application->approval_stage !== 'completed';

                                        // Refine disbursement call-to-action labels based on current status
                                        $disburseCtaLabel = 'Disburse Now';
                                        $disburseCtaTitle = 'Initiate M-Pesa disbursement';

                                        if ($disbursement) {
                                            if (in_array($disbursement->status, ['pending', 'processing'], true)) {
                                                $disburseCtaLabel = 'Resume Disbursement';
                                                $disburseCtaTitle = 'Resume the in-progress disbursement';
                                            } elseif ($disbursement->status === 'failed' || $wasAborted) {
                                                $disburseCtaLabel = 'Retry Disbursement';
                                                $disburseCtaTitle = 'Retry sending this disbursement';
                                            }
                                        }
                                    @endphp
                                    {{-- Debug: Uncomment to see why button isn't showing
                                    @if($application->status === 'approved' && $application->approval_stage === 'completed')
                                        <!-- Debug: Disbursement exists: {{ $disbursement ? 'Yes' : 'No' }}, Status: {{ $disbursement?->status }}, Has Role: {{ $hasRole ? 'Yes' : 'No' }} -->
                                    @endif
                                    --}}
                                    @if($canInlineDisburse)
                                        <button
                                            type="button"
                                            title="{{ $disburseCtaTitle }}"
                                            onclick="window.openDisbursementModalHandler({{ $disbursement->id }})"
                                            class="rounded-lg bg-emerald-500 px-3 py-1 text-xs font-semibold text-white hover:bg-emerald-600"
                                        >
                                            {{ $disburseCtaLabel }}
                                        </button>
                                    @elseif($canCreateDisbursement)
                                        <a
                                            href="{{ route('disbursements.create', $application) }}"
                                            class="rounded-lg bg-emerald-500 px-3 py-1 text-xs font-semibold text-white hover:bg-emerald-600"
                                            title="{{ $disburseCtaTitle }}"
                                        >
                                            {{ $disburseCtaLabel }}
                                        </a>
                                    @endif
                                    @if($showApprove && !$canInlineDisburse && !$canCreateDisbursement)
                                        <a href="{{ route('approvals.show', $application) }}" class="rounded-lg bg-emerald-500 px-3 py-1 text-xs font-semibold text-white hover:bg-emerald-600">
                                            Approve
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">
                                No applications found. Start by creating a new loan application.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                {{ $applications->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
window.openDisbursementModalHandler = function(disbursementId) {
    console.log('=== openDisbursementModalHandler START ===');
    console.log('Disbursement ID:', disbursementId);
    
    if (!disbursementId) {
        console.error('Disbursement ID is missing');
        return;
    }
    
    // Check Alpine immediately
    if (!window.Alpine) {
        console.error('Alpine.js not found');
        alert('Alpine.js not initialized. Please refresh the page.');
        return;
    }
    
    const modalStore = window.Alpine.store('modal');
    if (!modalStore) {
        console.error('Modal store not found');
        alert('Modal store not initialized. Please refresh the page.');
        return;
    }
    
    console.log('Alpine and modal store are available');
    
    // Check if Admin is available
    if (!window.Admin) {
        console.error('Admin object not found');
        alert('Admin modal system not initialized. Please refresh the page.');
        return;
    }
    
    console.log('Fetching disbursement status for ID:', disbursementId);
        const url = `{{ route('disbursements.status', ['disbursement' => '__ID__']) }}`.replace('__ID__', disbursementId);
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(data => {
                        throw new Error(data.message || `HTTP error! status: ${res.status}`);
                    });
                }
                return res.json();
            })
            .then(data => {
                console.log('Disbursement data received:', data);
                if (data.success) {
                    const html = window.getDisbursementModalHtml(disbursementId, data);
                    console.log('Generated HTML length:', html.length);
                    console.log('Calling Admin.showModal...');
                    try {
                        console.log('About to call Admin.showModal with:', {
                            title: 'Initiate Disbursement',
                            bodyLength: html.length,
                            size: 'lg'
                        });
                        
                        // Directly access modal store and set values
                        const modalStore = window.Alpine.store('modal');
                        if (!modalStore) {
                            throw new Error('Modal store not found');
                        }
                        
                        console.log('Modal store before show:', {
                            open: modalStore.open,
                            title: modalStore.title
                        });
                        
                        // Call Admin.showModal
                        Admin.showModal({
                            title: 'Initiate Disbursement',
                            body: html,
                            size: 'lg'
                        });
                        
                        console.log('Admin.showModal called successfully');
                        
                        // Immediately check modal store
                        const checkStore = () => {
                            const store = window.Alpine.store('modal');
                            console.log('Modal store after show:', {
                                open: store?.open,
                                title: store?.title,
                                bodyLength: store?.body?.length,
                                size: store?.size,
                                storeExists: !!store
                            });
                            
                            // If store exists but open is false, force it
                            if (store && !store.open) {
                                console.warn('Forcing modal open');
                                store.open = true;
                            }
                        };
                        
                        checkStore();
                        
                        // Also check after a microtask
                        queueMicrotask(checkStore);
                        
                        // Force Alpine to re-evaluate if needed
                        setTimeout(() => {
                            const store = window.Alpine.store('modal');
                            console.log('Modal store state after 100ms:', {
                                open: store?.open,
                                title: store?.title,
                                bodyLength: store?.body?.length,
                                size: store?.size
                            });
                            
                            // Double-check and force if needed
                            if (store && !store.open) {
                                console.warn('Modal store open is false, forcing open');
                                store.open = true;
                                // Trigger Alpine reactivity
                                if (window.Alpine && window.Alpine.evaluateLater) {
                                    window.Alpine.evaluateLater(() => {}).then(() => {
                                        console.log('Alpine reactivity triggered');
                                    });
                                }
                            }
                            
                            // Check if modal element is visible
                            const modalElement = document.querySelector('[x-show="$store.modal.open"]');
                            console.log('Modal element found:', modalElement);
                            if (modalElement) {
                                const isVisible = window.getComputedStyle(modalElement).display !== 'none';
                                console.log('Modal element visible:', isVisible);
                            }
                        }, 200);
                    } catch (error) {
                        console.error('Error calling Admin.showModal:', error);
                        console.error('Error stack:', error.stack);
                        alert('Failed to open modal: ' + error.message);
                    }
                } else {
                    console.error('Failed to load disbursement status:', data);
                    alert('Failed to load disbursement details: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error loading disbursement:', error);
                alert('An error occurred while loading disbursement details: ' + error.message);
            });
};

window.getDisbursementModalHtml = function(disbursementId, initialData) {
    // Escape JSON for HTML attribute - use base64 encoding to avoid escaping issues
    const dataStr = btoa(JSON.stringify(initialData));
    const amount = parseFloat(initialData.disbursement?.amount || 0).toLocaleString();
    const method = (initialData.disbursement?.method || 'N/A').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    const recipient = (initialData.disbursement?.recipient_phone || 'N/A').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    
    // Use data attribute to pass the JSON safely, then decode in Alpine
    let html = '<div data-disbursement-id="' + disbursementId + '" data-initial-data="' + dataStr + '" x-data="disbursementFlowFromData($el)" class="space-y-6">';
    html += '<div class="space-y-4">';
    html += '<div class="rounded-xl border border-slate-200 bg-slate-50 p-4">';
    html += '<h3 class="text-sm font-semibold text-slate-900">Disbursement Details</h3>';
    html += '<dl class="mt-3 space-y-2 text-sm">';
    html += '<div class="flex justify-between"><dt class="text-slate-600">Amount:</dt><dd class="font-semibold text-slate-900">KES ' + amount + '</dd></div>';
    html += '<div class="flex justify-between"><dt class="text-slate-600">Method:</dt><dd class="text-slate-900">' + method + '</dd></div>';
    html += '<div class="flex justify-between"><dt class="text-slate-600">Recipient:</dt><dd class="text-slate-900">' + recipient + '</dd></div>';
    html += '</dl></div>';
    
    html += '<div class="space-y-3">';
    html += '<h3 class="text-sm font-semibold text-slate-900">Disbursement Steps</h3>';
    html += '<div class="space-y-2">';
    html += '<template x-for="(step, key) in Object.entries(steps)" :key="key">';
    html += '<div class="flex items-center gap-3 rounded-lg border border-slate-200 p-3" ';
    html += ':class="{';
    html += '\'bg-emerald-50 border-emerald-200\': step[1].status === \'completed\',';
    html += '\'bg-amber-50 border-amber-200\': step[1].status === \'in_progress\',';
    html += '\'bg-slate-50\': step[1].status === \'pending\'';
    html += '}">';
    html += '<div class="flex-shrink-0">';
    html += '<template x-if="step[1].status === \'completed\'">';
    html += '<svg class="h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">';
    html += '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>';
    html += '</svg></template>';
    html += '<template x-if="step[1].status === \'in_progress\'">';
    html += '<svg class="h-5 w-5 animate-spin text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">';
    html += '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>';
    html += '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
    html += '</svg></template>';
    html += '<template x-if="step[1].status === \'pending\'">';
    html += '<div class="h-5 w-5 rounded-full border-2 border-slate-300"></div>';
    html += '</template></div>';
    html += '<div class="flex-1">';
    html += '<p class="text-sm font-medium text-slate-900" x-text="step[1].label"></p>';
    html += '</div></div></template></div></div>';
    
    html += '<div x-show="(disbursementStatus === \'pending\' || disbursementStatus === \'failed\') && !loading" class="space-y-3">';
    html += '<button type="button" @click="generateOtp()" :disabled="loading" class="w-full cursor-pointer rounded-xl px-4 py-2 text-sm font-semibold text-white shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed" style="background-color:#22c55e;">';
    html += '<span x-show="!loading">Generate & Send OTP</span>';
    html += '<span x-show="loading" class="flex items-center justify-center gap-2">';
    html += '<svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">';
    html += '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>';
    html += '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
    html += '</svg> Sending OTP...</span></button></div>';
    
    html += '<div x-show="steps.otp_generation && steps.otp_generation.status === \'completed\' && (currentStep === \'otp_verification\' || !steps.otp_verification || steps.otp_verification.status !== \'completed\')" class="space-y-3">';
    html += '<div><label class="block text-sm font-medium text-slate-700">Enter OTP</label>';
    html += '<input type="text" x-model="otp" maxlength="6" pattern="[0-9]{6}" placeholder="000000" class="mt-1 w-full rounded-xl border-slate-200 text-center text-2xl tracking-widest" />';
    html += '<p class="mt-1 text-xs text-slate-500">Check your email for the 6-digit OTP code</p></div>';
    html += '<button type="button" @click="verifyOtpAndDisburse()" :disabled="loading || !otp || otp.length !== 6" class="w-full cursor-pointer rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600 disabled:opacity-50 disabled:cursor-not-allowed">';
    html += '<span x-show="!loading">Verify OTP & Disburse</span>';
    html += '<span x-show="loading" class="flex items-center justify-center gap-2">';
    html += '<svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">';
    html += '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>';
    html += '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
    html += '</svg> Processing...</span></button></div>';
    
    html += '<div x-show="steps.payment_confirmation && steps.payment_confirmation.status === \'completed\'" class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">';
    html += '<p class="text-sm font-semibold text-emerald-900">✓ Disbursement completed successfully!</p></div>';
    
    html += '<div x-show="error" class="rounded-xl border border-rose-200 bg-rose-50 p-4">';
    html += '<p class="text-sm font-semibold text-rose-900" x-text="error"></p></div>';

    html += `<div class="mt-4 flex items-center justify-between border-t border-slate-200 pt-3">`;
    html += `<button type="button" @click="abortProcess()" x-show="(disbursementStatus === 'pending' || disbursementStatus === 'processing' || disbursementStatus === 'failed') && !loading" class="text-xs font-semibold text-rose-600 hover:text-rose-700" title="Abort the current disbursement attempt">Abort Disbursement</button>`;
    html += '<p class="ml-3 flex-1 text-right text-[11px] text-slate-400">You can request another OTP after 2 minutes from the last one.</p>';
    html += '</div>';
    
    html += '</div></div>';
    
    return html;
};

function disbursementFlowFromData(el) {
    const disbursementId = parseInt(el.getAttribute('data-disbursement-id'));
    const dataStr = el.getAttribute('data-initial-data');
    let initialData = {};
    try {
        initialData = JSON.parse(atob(dataStr));
    } catch (e) {
        console.error('Failed to parse initial data:', e);
    }
    return disbursementFlow(disbursementId, initialData);
}

function disbursementFlow(disbursementId, initialData) {
    return {
        disbursementId: disbursementId,
        steps: initialData.steps || {},
        currentStep: initialData.current_step || 'otp_generation',
        disbursementStatus: initialData.disbursement?.status || 'pending',
        otp: '',
        loading: false,
        error: null,
        
        init() {
            this.updateSteps(initialData);
        },
        
        updateSteps(data) {
            if (data.steps) {
                this.steps = data.steps;
            }
            if (data.current_step) {
                this.currentStep = data.current_step;
            }
            if (data.disbursement && data.disbursement.status) {
                this.disbursementStatus = data.disbursement.status;
            }
        },
        
        async generateOtp() {
            this.loading = true;
            this.error = null;
            
            try {
                const response = await fetch(`{{ route('disbursements.generate-otp', ['disbursement' => '__ID__']) }}`.replace('__ID__', this.disbursementId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    await this.refreshStatus();
                } else {
                    this.error = data.message || 'Failed to generate OTP';
                }
            } catch (e) {
                this.error = 'An error occurred while generating OTP';
                console.error(e);
            } finally {
                this.loading = false;
            }
        },

        async abortProcess() {
            this.loading = true;
            this.error = null;

            try {
                const response = await fetch(`{{ route('disbursements.abort', ['disbursement' => '__ID__']) }}`.replace('__ID__', this.disbursementId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.disbursementStatus = 'cancelled';
                    window.dispatchEvent(new CustomEvent('loan-applications:refresh'));
                    if (window.Alpine?.store('modal')) {
                        window.Alpine.store('modal').close();
                    }
                } else {
                    this.error = data.message || 'Failed to abort disbursement';
                }
            } catch (e) {
                this.error = 'An error occurred while aborting disbursement';
                console.error(e);
            } finally {
                this.loading = false;
            }
        },
        
        async verifyOtpAndDisburse() {
            if (!this.otp || this.otp.length !== 6) {
                this.error = 'Please enter a valid 6-digit OTP';
                return;
            }
            
            this.loading = true;
            this.error = null;
            
            try {
                const response = await fetch(`{{ route('disbursements.verify-otp', ['disbursement' => '__ID__']) }}`.replace('__ID__', this.disbursementId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ otp: this.otp })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    await this.refreshStatus();
                    if (data.step === 'payment_sent' || data.step === 'completed') {
                        setTimeout(() => {
                            window.dispatchEvent(new CustomEvent('loan-applications:refresh'));
                            if (window.Alpine?.store('modal')) {
                                window.Alpine.store('modal').close();
                            }
                        }, 2000);
                    }
                } else {
                    this.error = data.message || 'Failed to verify OTP';
                    if (data.step === 'otp_expired' || data.step === 'otp_not_found') {
                        this.steps.otp_generation = { label: 'Generate and Send OTP', status: 'pending' };
                    }
                }
            } catch (e) {
                this.error = 'An error occurred while processing disbursement';
                console.error(e);
            } finally {
                this.loading = false;
            }
        },
        
        async refreshStatus() {
            try {
                const response = await fetch(`{{ route('disbursements.status', ['disbursement' => '__ID__']) }}`.replace('__ID__', this.disbursementId));
                const data = await response.json();
                
                if (data.success) {
                    this.updateSteps(data);
                }
            } catch (e) {
                console.error('Failed to refresh status:', e);
            }
        }
    };
}
</script>
@endpush

