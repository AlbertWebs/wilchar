@extends('adminlte::page')

@section('title', 'STK Push Details')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">STK Push #{{ $stkPush->id }}</h1>
        <a href="{{ route('mpesa.stk-push.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
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
                        <label class="text-sm font-medium text-gray-500">Phone Number</label>
                        <p class="text-gray-900 font-semibold">{{ $stkPush->phone_number }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Amount</label>
                        <p class="text-gray-900 font-semibold text-green-600">KES {{ number_format($stkPush->amount, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <p>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $stkPush->status === 'success' ? 'bg-green-100 text-green-800' : 
                                   ($stkPush->status === 'failed' ? 'bg-red-100 text-red-800' :
                                   ($stkPush->status === 'cancelled' ? 'bg-gray-100 text-gray-800' :
                                   'bg-yellow-100 text-yellow-800')) }}">
                                {{ ucfirst($stkPush->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Receipt Number</label>
                        <p class="text-gray-900">{{ $stkPush->mpesa_receipt_number ?? 'Pending' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Account Reference</label>
                        <p class="text-gray-900">{{ $stkPush->account_reference ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Transaction Date</label>
                        <p class="text-gray-900">{{ $stkPush->transaction_date ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Result Code</label>
                        <p class="text-gray-900">{{ $stkPush->result_code ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Result Description</label>
                        <p class="text-gray-900">{{ $stkPush->result_desc ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Additional Information</h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Checkout Request ID</label>
                    <p class="text-gray-900 text-sm">{{ $stkPush->checkout_request_id ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Merchant Request ID</label>
                    <p class="text-gray-900 text-sm">{{ $stkPush->merchant_request_id ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Initiated By</label>
                    <p class="text-gray-900">{{ $stkPush->initiator->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Created At</label>
                    <p class="text-gray-900">{{ $stkPush->created_at->format('F d, Y h:i A') }}</p>
                </div>
                @if($stkPush->balance)
                <div>
                    <label class="text-sm font-medium text-gray-500">Balance</label>
                    <p class="text-gray-900 font-semibold">KES {{ number_format($stkPush->balance, 2) }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

