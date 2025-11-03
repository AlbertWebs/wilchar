@extends('adminlte::page')

@section('title', 'Transaction Status Query')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Transaction Status Query</h1>
        <div class="flex gap-2">
            <a href="{{ route('mpesa.transaction-status.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                <i class="fas fa-plus"></i> Query Status
            </a>
            <a href="{{ route('mpesa.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid space-y-4">
    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow">
        <form method="GET" action="{{ route('mpesa.transaction-status.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="found" {{ request('status') == 'found' ? 'selected' : '' }}>Found</option>
                    <option value="not_found" {{ request('status') == 'not_found' ? 'selected' : '' }}>Not Found</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Transaction ID</label>
                <input type="text" name="transaction_id" value="{{ request('transaction_id') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="Enter transaction ID">
            </div>
            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('mpesa.transaction-status.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg ml-2">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Transaction Status Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receipt Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Result Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($statuses as $status)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">#{{ $status->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm">{{ $status->transaction_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $status->receipt_number ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">KES {{ number_format($status->transaction_amount ?? 0, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $status->transaction_date ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $status->status === 'found' ? 'bg-green-100 text-green-800' : 
                                   ($status->status === 'not_found' ? 'bg-yellow-100 text-yellow-800' :
                                   ($status->status === 'failed' ? 'bg-red-100 text-red-800' :
                                   'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst(str_replace('_', ' ', $status->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $status->result_code ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $status->requester->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $status->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">No transaction status queries found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($statuses->hasPages())
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            {{ $statuses->links() }}
        </div>
        @endif
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

