@extends('adminlte::page')

@section('title', 'Financial Reports')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Financial Reports</h1>
        <a href="{{ route('reports.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left"></i> Back to Reports
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid space-y-4">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-green-600">KES {{ number_format($financials['total_disbursed'] ?? 0, 2) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Disbursed</div>
                </div>
                <i class="fas fa-money-bill-wave text-3xl text-green-500 opacity-50"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-blue-600">KES {{ number_format($financials['total_collections'] ?? 0, 2) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Collections</div>
                </div>
                <i class="fas fa-hand-holding-usd text-3xl text-blue-500 opacity-50"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-yellow-600">KES {{ number_format($financials['pending_disbursements'] ?? 0, 2) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Pending Disbursements</div>
                </div>
                <i class="fas fa-clock text-3xl text-yellow-500 opacity-50"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-red-600">KES {{ number_format($financials['overdue_amount'] ?? 0, 2) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Overdue Amount</div>
                </div>
                <i class="fas fa-exclamation-triangle text-3xl text-red-500 opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Net Revenue Card -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm opacity-90">Net Revenue (Collections - Disbursements)</div>
                <div class="text-3xl font-bold mt-2">
                    KES {{ number_format(($financials['total_collections'] ?? 0) - ($financials['total_disbursed'] ?? 0), 2) }}
                </div>
            </div>
            <i class="fas fa-chart-line text-5xl opacity-30"></i>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-white p-4 rounded-lg shadow">
        <form method="GET" action="{{ route('reports.financial') }}" class="flex flex-wrap gap-4 items-end">
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-filter"></i> Apply Filter
                </button>
                <a href="{{ route('reports.financial') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg ml-2">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions Table -->
    @if($transactions && $transactions->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold">Transactions</h3>
            <p class="text-sm text-gray-500">Showing transactions from {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Account</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">#{{ $transaction->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $transaction->type === 'credit' ? 'bg-green-100 text-green-800' : 
                                   ($transaction->type === 'debit' ? 'bg-red-100 text-red-800' :
                                   'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($transaction->type ?? 'N/A') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">KES {{ number_format($transaction->amount ?? 0, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $transaction->account->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $transaction->loan ? 'Loan #' . $transaction->loan->id : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaction->created_at->format('M d, Y h:i A') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
        <p class="text-lg text-gray-500">No transactions found for the selected period</p>
    </div>
    @endif
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

