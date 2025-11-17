@extends('layouts.admin', ['title' => 'Approvals'])

@section('header')
    Loan Approvals
@endsection

@section('content')
    <x-admin.section
        title="Applications Awaiting Action"
        description="Review and progress applications through the workflow"
    >
        <div class="overflow-hidden rounded-2xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Application</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Stage</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Email Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($applications as $application)
                        @php
                            $emailStatus = $application->email_status ?? ['sent' => false, 'sent_at' => null, 'sent_count' => 0, 'total_recipients' => 0, 'errors' => []];
                        @endphp
                        <tr class="hover:bg-slate-50/80" x-data="{ 
                            sending: false,
                            emailStatus: @js($emailStatus),
                            sendEmail() {
                                if (this.sending) return;
                                this.sending = true;
                                fetch('{{ route('approvals.send-email', $application) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    this.sending = false;
                                    if (data.success) {
                                        this.emailStatus = {
                                            sent: true,
                                            sent_at: new Date().toISOString(),
                                            sent_count: data.sent_count,
                                            total_recipients: data.total_recipients,
                                            errors: data.errors || []
                                        };
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Email Sent',
                                            html: `Email sent to ${data.sent_count} approver(s).<br>${data.errors.length > 0 ? '<br>Errors: ' + data.errors.join('<br>') : ''}`,
                                            timer: 3000,
                                            showConfirmButton: true
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Failed',
                                            text: data.message || 'Failed to send email',
                                        });
                                    }
                                })
                                .catch(error => {
                                    this.sending = false;
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Failed to send email: ' + error.message,
                                    });
                                });
                            }
                        }">
                            <td class="px-4 py-4">
                                <div class="font-semibold text-slate-900">{{ $application->application_number }}</div>
                                <div class="text-xs text-slate-500">{{ $application->loan_type }}</div>
                            </td>
                            <td class="px-4 py-4 text-slate-700">
                                {{ $application->client->full_name }}
                                <div class="text-xs text-slate-500">{{ $application->client->phone }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    {{ ucfirst(str_replace('_', ' ', $application->approval_stage)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-slate-700">
                                KES {{ number_format($application->amount, 2) }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <template x-if="emailStatus.sent">
                                        <div class="flex items-center gap-1 text-xs text-emerald-600" :title="`Email sent to ${emailStatus.sent_count}/${emailStatus.total_recipients} recipients`">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            <span x-text="`Sent (${emailStatus.sent_count}/${emailStatus.total_recipients})`"></span>
                                        </div>
                                    </template>
                                    <template x-if="!emailStatus.sent">
                                        <div class="flex items-center gap-1 text-xs text-slate-400" title="Email not sent yet">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Not sent</span>
                                        </div>
                                    </template>
                                    <template x-if="emailStatus.errors && emailStatus.errors.length > 0">
                                        <div class="flex items-center gap-1 text-xs text-rose-600" :title="`Errors: ${emailStatus.errors.join(', ')}`">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            <span x-text="`${emailStatus.errors.length} error(s)`"></span>
                                        </div>
                                    </template>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        @click="sendEmail()"
                                        :disabled="sending"
                                        class="inline-flex items-center gap-1 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                        title="Send email notification to approvers"
                                    >
                                        <svg x-show="!sending" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <svg x-show="sending" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span x-text="sending ? 'Sending...' : 'Send Email'"></span>
                                    </button>
                                    <a href="{{ route('approvals.show', $application) }}" class="rounded-lg bg-emerald-500 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-600">
                                        Review & Approve
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">
                                No applications are pending your approval right now.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                {{ $applications->links() }}
            </div>
        </div>
    </x-admin.section>
@endsection

