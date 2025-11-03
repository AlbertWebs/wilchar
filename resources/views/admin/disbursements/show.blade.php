@extends('adminlte::page')

@section('title', 'Disbursement Details')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Disbursement #{{ $disbursement->id }}</h1>
        <div class="flex gap-2">
            @if($disbursement->status === 'failed')
            <form action="{{ route('disbursements.retry', $disbursement) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2"
                        onclick="return confirm('Are you sure you want to retry this disbursement?')">
                    <i class="fas fa-redo"></i> Retry
                </button>
            </form>
            @endif
            <a href="{{ route('disbursements.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid space-y-4">
    <!-- Status Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <p class="text-2xl font-semibold mt-1">
                    <span class="px-3 py-2 text-sm font-medium rounded-full
                        {{ $disbursement->status === 'success' ? 'bg-green-100 text-green-800' : 
                           ($disbursement->status === 'failed' ? 'bg-red-100 text-red-800' :
                           'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($disbursement->status ?? 'Pending') }}
                    </span>
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Amount</p>
                <p class="text-2xl font-bold text-green-600 mt-1">KES {{ number_format($disbursement->amount ?? 0, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Disbursement Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-money-bill-wave text-green-500"></i> Disbursement Information
            </h3>
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Disbursement ID</label>
                        <p class="text-gray-900 font-semibold">#{{ $disbursement->id }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Method</label>
                        <p class="text-gray-900">{{ ucfirst(str_replace('_', ' ', $disbursement->method ?? 'N/A')) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Recipient Phone</label>
                        <p class="text-gray-900">{{ $disbursement->recipient_phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Disbursement Date</label>
                        <p class="text-gray-900">{{ $disbursement->disbursement_date ? \Carbon\Carbon::parse($disbursement->disbursement_date)->format('F d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Disbursed By</label>
                        <p class="text-gray-900">{{ $disbursement->disburser->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Created At</label>
                        <p class="text-gray-900">{{ $disbursement->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Application Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-file-alt text-blue-500"></i> Loan Application
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Application Number</label>
                    <p class="text-gray-900 font-semibold">{{ $disbursement->loanApplication->application_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Client</label>
                    <p class="text-gray-900 font-semibold">{{ $disbursement->loanApplication->client->full_name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">{{ $disbursement->loanApplication->client->phone ?? '' }}</p>
                </div>
                <div>
                    <a href="{{ route('loan-applications.show', $disbursement->loanApplication) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-eye"></i> View Application Details
                    </a>
                </div>
            </div>
        </div>

        <!-- M-Pesa Details -->
        @if($disbursement->method === 'mpesa_b2c')
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-mobile-alt text-purple-500"></i> M-Pesa Transaction Details
            </h3>
            <div class="space-y-3">
                @if($disbursement->transaction_receipt)
                <div>
                    <label class="text-sm font-medium text-gray-500">Transaction Receipt</label>
                    <p class="text-gray-900 font-semibold">{{ $disbursement->transaction_receipt }}</p>
                </div>
                @endif
                @if($disbursement->mpesa_originator_conversation_id)
                <div>
                    <label class="text-sm font-medium text-gray-500">Originator Conversation ID</label>
                    <p class="text-gray-900">{{ $disbursement->mpesa_originator_conversation_id }}</p>
                </div>
                @endif
                @if($disbursement->mpesa_conversation_id)
                <div>
                    <label class="text-sm font-medium text-gray-500">Conversation ID</label>
                    <p class="text-gray-900">{{ $disbursement->mpesa_conversation_id }}</p>
                </div>
                @endif
                @if($disbursement->mpesa_response_description)
                <div>
                    <label class="text-sm font-medium text-gray-500">Response Description</label>
                    <p class="text-gray-900">{{ $disbursement->mpesa_response_description }}</p>
                </div>
                @endif
                @if($disbursement->mpesa_result_description)
                <div>
                    <label class="text-sm font-medium text-gray-500">Result Description</label>
                    <p class="text-gray-900">{{ $disbursement->mpesa_result_description }}</p>
                </div>
                @endif
                @if($disbursement->status === 'failed' && $disbursement->retry_count > 0)
                <div>
                    <label class="text-sm font-medium text-gray-500">Retry Count</label>
                    <p class="text-gray-900">{{ $disbursement->retry_count }} / 3</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

