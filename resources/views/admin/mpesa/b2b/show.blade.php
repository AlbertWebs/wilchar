@extends('adminlte::page')

@section('title', 'B2B Transaction Details')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">B2B Transaction #{{ $b2bTransaction->id }}</h1>
        <a href="{{ route('mpesa.b2b.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid space-y-4">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Transaction Details</h3>
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Transaction ID</label>
                        <p class="text-gray-900 font-semibold">#{{ $b2bTransaction->id }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Amount</label>
                        <p class="text-gray-900 font-semibold text-green-600">KES {{ number_format($b2bTransaction->amount, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Sender (Party A)</label>
                        <p class="text-gray-900">{{ $b2bTransaction->party_a }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Receiver (Party B)</label>
                        <p class="text-gray-900">{{ $b2bTransaction->party_b }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <p>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $b2bTransaction->status === 'success' ? 'bg-green-100 text-green-800' : 
                                   ($b2bTransaction->status === 'failed' ? 'bg-red-100 text-red-800' :
                                   'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($b2bTransaction->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Transaction Receipt</label>
                        <p class="text-gray-900">{{ $b2bTransaction->transaction_receipt ?? 'Pending' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Account Reference</label>
                        <p class="text-gray-900">{{ $b2bTransaction->account_reference ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Remarks</label>
                        <p class="text-gray-900">{{ $b2bTransaction->remarks ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Result Code</label>
                        <p class="text-gray-900">{{ $b2bTransaction->result_code ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Result Description</label>
                        <p class="text-gray-900">{{ $b2bTransaction->result_desc ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Additional Information</h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Originator Conversation ID</label>
                    <p class="text-gray-900 text-sm font-mono">{{ $b2bTransaction->originator_conversation_id ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Conversation ID</label>
                    <p class="text-gray-900 text-sm font-mono">{{ $b2bTransaction->conversation_id ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Initiated By</label>
                    <p class="text-gray-900">{{ $b2bTransaction->initiator->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Transaction Date</label>
                    <p class="text-gray-900">{{ $b2bTransaction->transaction_date ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Created At</label>
                    <p class="text-gray-900">{{ $b2bTransaction->created_at->format('F d, Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

