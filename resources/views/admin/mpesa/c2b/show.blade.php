@extends('adminlte::page')

@section('title', 'C2B Transaction Details')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">C2B Transaction Details</h1>
        <a href="{{ route('mpesa.c2b.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
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
                        <p class="text-gray-900 font-mono text-sm font-semibold">{{ $c2bTransaction->trans_id }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Amount</label>
                        <p class="text-gray-900 font-semibold text-green-600">KES {{ number_format($c2bTransaction->trans_amount, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Customer Name</label>
                        <p class="text-gray-900 font-semibold">{{ $c2bTransaction->full_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Phone Number</label>
                        <p class="text-gray-900">{{ $c2bTransaction->msisdn }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Business Short Code</label>
                        <p class="text-gray-900">{{ $c2bTransaction->business_short_code }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Bill Reference</label>
                        <p class="text-gray-900">{{ $c2bTransaction->bill_ref_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Invoice Number</label>
                        <p class="text-gray-900">{{ $c2bTransaction->invoice_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <p>
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $c2bTransaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($c2bTransaction->status === 'failed' ? 'bg-red-100 text-red-800' :
                                   'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($c2bTransaction->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Transaction Time</label>
                        <p class="text-gray-900">
                            @php
                                $date = \Carbon\Carbon::createFromFormat('YmdHis', $c2bTransaction->trans_time);
                            @endphp
                            {{ $date->format('F d, Y h:i A') }}
                        </p>
                    </div>
                    @if($c2bTransaction->org_account_balance)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Account Balance</label>
                        <p class="text-gray-900">{{ $c2bTransaction->org_account_balance }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

