@extends('adminlte::page')

@section('title', 'Review Application')

@section('content_header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">Review Application</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $loanApplication->application_number }}</p>
        </div>
        <a href="{{ route('approvals.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid space-y-4">
    <!-- Application Summary -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Application Summary</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="text-sm text-gray-500">Client</label>
                <p class="font-semibold">{{ $loanApplication->client->full_name }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Amount Requested</label>
                <p class="font-semibold">KES {{ number_format($loanApplication->amount, 2) }}</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Duration</label>
                <p class="font-semibold">{{ $loanApplication->duration_months }} months</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Interest Rate</label>
                <p class="font-semibold">{{ number_format($loanApplication->interest_rate, 2) }}%</p>
            </div>
        </div>
    </div>

    <!-- Approval/Rejection Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">
            @if($loanApplication->approval_stage === 'loan_officer')
                Loan Officer Review
            @elseif($loanApplication->approval_stage === 'credit_officer')
                Credit Officer Approval
            @else
                Director Final Approval
            @endif
        </h3>

        <!-- KYC Documents -->
        @if($loanApplication->approval_stage === 'loan_officer' && $loanApplication->kycDocuments->count() > 0)
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">KYC Documents</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($loanApplication->kycDocuments as $document)
                <div class="border rounded p-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium">{{ ucfirst($document->document_type) }}</span>
                        <span class="text-xs {{ $document->verification_status === 'verified' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ ucfirst($document->verification_status) }}
                        </span>
                    </div>
                    <a href="{{ $document->file_url }}" target="_blank" class="text-blue-600 hover:underline text-sm">
                        View Document
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Background Check (Loan Officer) -->
        @if($loanApplication->approval_stage === 'loan_officer')
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Background Check Status</label>
            <select name="background_check_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg" disabled>
                <option value="pending" {{ $loanApplication->background_check_status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="passed" {{ $loanApplication->background_check_status === 'passed' ? 'selected' : '' }}>Passed</option>
                <option value="failed" {{ $loanApplication->background_check_status === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            @if($loanApplication->background_check_status === 'passed')
                <p class="text-sm text-green-600 mt-1"><i class="fas fa-check-circle"></i> Background check passed</p>
            @endif
        </div>
        @endif

        <!-- Amount Approved (Credit Officer/Director) -->
        @if(in_array($loanApplication->approval_stage, ['credit_officer', 'director']))
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Approved Amount</label>
            <input type="number" step="0.01" name="amount_approved" value="{{ $loanApplication->amount_approved ?? $loanApplication->amount }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg" 
                   placeholder="Enter approved amount" form="approve-form">
            <p class="text-xs text-gray-500 mt-1">Requested: KES {{ number_format($loanApplication->amount, 2) }}</p>
        </div>
        @endif

        <!-- Action Forms -->
        <div class="flex gap-4">
            <!-- Approve Form -->
            <form id="approve-form" action="{{ route('approvals.approve', $loanApplication) }}" method="POST" class="flex-1">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comments (Optional)</label>
                    <textarea name="comment" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Add any comments or notes..."></textarea>
                </div>
                <button type="submit" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg font-medium">
                    <i class="fas fa-check-circle"></i> Approve & Move to Next Stage
                </button>
            </form>

            <!-- Reject Form -->
            <form action="{{ route('approvals.reject', $loanApplication) }}" method="POST" class="flex-1">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                    <textarea name="rejection_reason" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"
                              placeholder="Please provide a reason for rejection..."></textarea>
                </div>
                <button type="submit" 
                        class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-medium"
                        onclick="return confirm('Are you sure you want to reject this application?')">
                    <i class="fas fa-times-circle"></i> Reject Application
                </button>
            </form>
        </div>
    </div>
</div>
@stop


