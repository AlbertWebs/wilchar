@extends('adminlte::page')

@section('title', 'Collection Details')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Collection #{{ $collection->id }}</h1>
        <div class="flex gap-2">
            <a href="{{ route('collections.edit', $collection) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('collections.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid space-y-4">
    <!-- Details Card -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-money-bill-wave text-green-500"></i> Collection Information
            </h3>
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Collection ID</label>
                        <p class="text-gray-900 font-semibold">#{{ $collection->id }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Amount</label>
                        <p class="text-gray-900 font-semibold text-green-600">KES {{ number_format($collection->amount, 2) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Payment Method</label>
                        <p class="text-gray-900">{{ $collection->payment_method }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Payment Date</label>
                        <p class="text-gray-900">{{ $collection->paid_at ? \Carbon\Carbon::parse($collection->paid_at)->format('F d, Y') : 'N/A' }}</p>
                    </div>
                    @if($collection->reference)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Reference Number</label>
                        <p class="text-gray-900">{{ $collection->reference }}</p>
                    </div>
                    @endif
                    @if($collection->receipt_url)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Receipt</label>
                        <a href="{{ $collection->receipt_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-external-link-alt"></i> View Receipt
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-500"></i> Additional Information
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Loan</label>
                    <p class="text-gray-900 font-semibold">Loan #{{ $collection->loan_id }}</p>
                    @if($collection->loan)
                    <a href="{{ route('loans.show', $collection->loan) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        View Loan Details
                    </a>
                    @endif
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Received By</label>
                    <p class="text-gray-900">{{ $collection->receiver->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Recorded At</label>
                    <p class="text-gray-900">{{ $collection->created_at->format('F d, Y h:i A') }}</p>
                </div>
                @if($collection->updated_at != $collection->created_at)
                <div>
                    <label class="text-sm font-medium text-gray-500">Last Updated</label>
                    <p class="text-gray-900">{{ $collection->updated_at->format('F d, Y h:i A') }}</p>
                </div>
                @endif
            </div>
        </div>

        @if($collection->loan && $collection->loan->client)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-user text-purple-500"></i> Client Information
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Client Name</label>
                    <p class="text-gray-900 font-semibold">{{ $collection->loan->client->full_name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Phone</label>
                    <p class="text-gray-900">{{ $collection->loan->client->phone }}</p>
                </div>
                @if($collection->loan->client->email)
                <div>
                    <label class="text-sm font-medium text-gray-500">Email</label>
                    <p class="text-gray-900">{{ $collection->loan->client->email }}</p>
                </div>
                @endif
                <div>
                    <a href="{{ route('clients.show', $collection->loan->client) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        View Client Details
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

