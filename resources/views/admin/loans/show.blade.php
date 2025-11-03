@extends('adminlte::page')

@section('title', 'Loan Details')

@section('content_header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">Loan #{{ $loan->id }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $loan->created_at->format('F d, Y h:i A') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('loans.edit', $loan) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('loans.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
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
                            {{ $loan->status === 'approved' ? 'bg-green-100 text-green-800' : 
                               ($loan->status === 'disbursed' ? 'bg-blue-100 text-blue-800' :
                               ($loan->status === 'closed' ? 'bg-gray-100 text-gray-800' :
                               ($loan->status === 'rejected' ? 'bg-red-100 text-red-800' :
                               'bg-yellow-100 text-yellow-800'))) }}">
                            {{ ucfirst($loan->status) }}
                        </span>
                    </p>
                </div>
                <i class="fas fa-info-circle text-2xl text-blue-500"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Amount Requested</p>
                    <p class="text-xl font-semibold mt-1">KES {{ number_format($loan->amount_requested, 2) }}</p>
                </div>
                <i class="fas fa-money-bill-wave text-2xl text-green-500"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Amount Approved</p>
                    <p class="text-xl font-semibold mt-1">
                        {{ $loan->amount_approved ? 'KES ' . number_format($loan->amount_approved, 2) : 'Pending' }}
                    </p>
                </div>
                <i class="fas fa-check-circle text-2xl text-green-500"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Term</p>
                    <p class="text-xl font-semibold mt-1">{{ $loan->term_months }} months</p>
                </div>
                <i class="fas fa-calendar text-2xl text-purple-500"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Loan Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-file-alt text-blue-500"></i> Loan Details
            </h3>
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Loan Type</label>
                        <p class="text-gray-900 font-semibold">{{ ucfirst(str_replace('_', ' ', $loan->loan_type)) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Interest Rate</label>
                        <p class="text-gray-900">{{ number_format($loan->interest_rate, 2) }}%</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Repayment Frequency</label>
                        <p class="text-gray-900">{{ ucfirst($loan->repayment_frequency) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Created At</label>
                        <p class="text-gray-900">{{ $loan->created_at->format('F d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Client Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-user text-green-500"></i> Client Information
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Name</label>
                    <p class="text-gray-900 font-semibold">{{ $loan->client->full_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Phone</label>
                    <p class="text-gray-900">{{ $loan->client->phone ?? 'N/A' }}</p>
                </div>
                @if($loan->client && $loan->client->email)
                <div>
                    <label class="text-sm font-medium text-gray-500">Email</label>
                    <p class="text-gray-900">{{ $loan->client->email }}</p>
                </div>
                @endif
                @if($loan->client)
                <div>
                    <a href="{{ route('clients.show', $loan->client) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-eye"></i> View Client Details
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Disbursements -->
        @if($loan->disbursements && $loan->disbursements->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-money-bill-wave text-green-500"></i> Disbursements
            </h3>
            <div class="space-y-2">
                @foreach($loan->disbursements as $disbursement)
                <div class="border rounded-lg p-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold">KES {{ number_format($disbursement->amount, 2) }}</p>
                            <p class="text-sm text-gray-500">{{ $disbursement->created_at->format('M d, Y') }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded
                            {{ $disbursement->status === 'success' ? 'bg-green-100 text-green-800' : 
                               ($disbursement->status === 'failed' ? 'bg-red-100 text-red-800' :
                               'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($disbursement->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Repayments -->
        @if($loan->repayments && $loan->repayments->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-hand-holding-usd text-purple-500"></i> Repayments
            </h3>
            <div class="space-y-2">
                @foreach($loan->repayments->take(5) as $repayment)
                <div class="border rounded-lg p-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold">KES {{ number_format($repayment->amount, 2) }}</p>
                            <p class="text-sm text-gray-500">{{ $repayment->created_at->format('M d, Y') }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800">
                            Paid
                        </span>
                    </div>
                </div>
                @endforeach
                @if($loan->repayments->count() > 5)
                <p class="text-sm text-gray-500 text-center">... and {{ $loan->repayments->count() - 5 }} more</p>
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

