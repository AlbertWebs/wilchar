@extends('adminlte::page')

@section('title', 'Loan Application Details')

@section('content_header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">Application #{{ $loanApplication->application_number }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $loanApplication->created_at->format('F d, Y h:i A') }}</p>
        </div>
        <div class="flex gap-2">
            @if($loanApplication->isApproved() && !$loanApplication->disbursements()->where('status', 'success')->exists())
                <a href="{{ route('disbursements.create', $loanApplication) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                    <i class="fas fa-money-bill-wave"></i> Disburse Loan
                </a>
            @endif
            <a href="{{ route('loan-applications.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid space-y-4">
    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="text-xl font-semibold mt-1">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $loanApplication->status === 'approved' ? 'bg-green-100 text-green-800' : 
                               ($loanApplication->status === 'rejected' ? 'bg-red-100 text-red-800' :
                               ($loanApplication->status === 'disbursed' ? 'bg-blue-100 text-blue-800' :
                               ($loanApplication->status === 'under_review' ? 'bg-yellow-100 text-yellow-800' :
                               'bg-gray-100 text-gray-800'))) }}">
                            {{ ucfirst(str_replace('_', ' ', $loanApplication->status)) }}
                        </span>
                    </p>
                </div>
                <i class="fas fa-info-circle text-2xl text-blue-500"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Current Stage</p>
                    <p class="text-xl font-semibold mt-1">{{ $loanApplication->current_stage_display }}</p>
                </div>
                <i class="fas fa-layer-group text-2xl text-purple-500"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Amount Requested</p>
                    <p class="text-xl font-semibold mt-1">KES {{ number_format($loanApplication->amount, 2) }}</p>
                </div>
                <i class="fas fa-money-bill-wave text-2xl text-green-500"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Amount Approved</p>
                    <p class="text-xl font-semibold mt-1">
                        {{ $loanApplication->amount_approved ? 'KES ' . number_format($loanApplication->amount_approved, 2) : 'Pending' }}
                    </p>
                </div>
                <i class="fas fa-check-circle text-2xl text-green-500"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Application Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i class="fas fa-file-alt text-blue-500"></i> Application Details
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Application Number</label>
                        <p class="text-gray-900 font-semibold">{{ $loanApplication->application_number }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Loan Type</label>
                        <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $loanApplication->loan_type)) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Amount Requested</label>
                        <p class="text-gray-900 font-semibold">KES {{ number_format($loanApplication->amount, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Interest Rate</label>
                        <p class="text-gray-900">{{ number_format($loanApplication->interest_rate, 2) }}%</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Duration</label>
                        <p class="text-gray-900">{{ $loanApplication->duration_months }} months</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Purpose</label>
                        <p class="text-gray-900">{{ $loanApplication->purpose ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Client Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-green-500"></i> Client Information
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Name</label>
                        <p class="text-gray-900 font-semibold">{{ $loanApplication->client->full_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Phone</label>
                        <p class="text-gray-900">{{ $loanApplication->client->phone }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900">{{ $loanApplication->client->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">ID Number</label>
                        <p class="text-gray-900">{{ $loanApplication->client->id_number }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Business Name</label>
                        <p class="text-gray-900">{{ $loanApplication->client->business_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">M-Pesa Phone</label>
                        <p class="text-gray-900">{{ $loanApplication->client->mpesa_phone ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- KYC Documents -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i class="fas fa-file-upload text-purple-500"></i> KYC Documents
                </h3>
                @if($loanApplication->kycDocuments->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($loanApplication->kycDocuments as $document)
                        <div class="border rounded-lg p-3 hover:shadow-md transition">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-medium px-2 py-1 rounded
                                    {{ $document->verification_status === 'verified' ? 'bg-green-100 text-green-800' :
                                       ($document->verification_status === 'rejected' ? 'bg-red-100 text-red-800' :
                                       'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($document->document_type) }}
                                </span>
                                <span class="text-xs text-gray-500">{{ ucfirst($document->verification_status) }}</span>
                            </div>
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $document->document_name }}</p>
                            <a href="{{ $document->file_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs mt-2 inline-flex items-center gap-1">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No KYC documents uploaded</p>
                @endif
            </div>

            <!-- Approval History -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i class="fas fa-history text-orange-500"></i> Approval History
                </h3>
                @if($loanApplication->approvals->count() > 0)
                    <div class="space-y-3">
                        @foreach($loanApplication->approvals as $approval)
                        <div class="border-l-4 {{ $approval->status === 'approved' ? 'border-green-500' : 'border-red-500' }} pl-4 py-2">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold">{{ $approval->level_display }}</p>
                                    <p class="text-sm text-gray-600">{{ $approval->approver->name ?? 'N/A' }}</p>
                                    @if($approval->comment)
                                        <p class="text-sm text-gray-500 mt-1">{{ $approval->comment }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs font-medium rounded
                                        {{ $approval->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($approval->status) }}
                                    </span>
                                    <p class="text-xs text-gray-500 mt-1">{{ $approval->approved_at?->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No approval history</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <!-- Approval Actions -->
            @if(in_array($loanApplication->approval_stage, ['loan_officer', 'credit_officer', 'director']) && $loanApplication->status !== 'rejected')
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i class="fas fa-check-circle text-blue-500"></i> Approval Actions
                </h3>
                <form action="{{ route('approvals.show', $loanApplication) }}" method="GET" class="mb-3">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg">
                        <i class="fas fa-eye"></i> Review Application
                    </button>
                </form>
            </div>
            @endif

            <!-- Background Check Status -->
            @if($loanApplication->background_check_status)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Background Check</h3>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Status</span>
                        <span class="px-2 py-1 text-xs font-medium rounded
                            {{ $loanApplication->background_check_status === 'passed' ? 'bg-green-100 text-green-800' :
                               ($loanApplication->background_check_status === 'failed' ? 'bg-red-100 text-red-800' :
                               'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($loanApplication->background_check_status) }}
                        </span>
                    </div>
                    @if($loanApplication->background_check_notes)
                        <div class="mt-3">
                            <label class="text-sm font-medium text-gray-500">Notes</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $loanApplication->background_check_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Assigned Officers -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Assigned Officers</h3>
                <div class="space-y-3">
                    @if($loanApplication->loanOfficer)
                    <div>
                        <label class="text-xs font-medium text-gray-500">Loan Officer</label>
                        <p class="text-sm text-gray-900">{{ $loanApplication->loanOfficer->name }}</p>
                    </div>
                    @endif
                    @if($loanApplication->creditOfficer)
                    <div>
                        <label class="text-xs font-medium text-gray-500">Credit Officer</label>
                        <p class="text-sm text-gray-900">{{ $loanApplication->creditOfficer->name }}</p>
                    </div>
                    @endif
                    @if($loanApplication->director)
                    <div>
                        <label class="text-xs font-medium text-gray-500">Director</label>
                        <p class="text-sm text-gray-900">{{ $loanApplication->director->name }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Audit Logs -->
            @if($auditLogs->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @foreach($auditLogs->take(5) as $log)
                    <div class="border-l-2 border-gray-300 pl-3 py-1">
                        <p class="text-xs text-gray-600">{{ $log->description }}</p>
                        <p class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop
