@extends('adminlte::page')

@section('title', 'M-Pesa Account Balance')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">M-Pesa Account Balance</h1>
        <div class="flex gap-2">
            <form action="{{ route('mpesa.account-balance.store') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                    <i class="fas fa-sync"></i> Check Balance
                </button>
            </form>
            <a href="{{ route('mpesa.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid space-y-4">
    <!-- Latest Balance Card -->
    @if($latestBalance)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
            <i class="fas fa-wallet text-red-500"></i> Latest Account Balance
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                <div class="text-sm opacity-90 mb-1">Working Account</div>
                <div class="text-2xl font-bold">KES {{ number_format($latestBalance->working_account_balance ?? 0, 2) }}</div>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                <div class="text-sm opacity-90 mb-1">Utility Account</div>
                <div class="text-2xl font-bold">KES {{ number_format($latestBalance->utility_account_balance ?? 0, 2) }}</div>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                <div class="text-sm opacity-90 mb-1">Charges Paid Account</div>
                <div class="text-2xl font-bold">KES {{ number_format($latestBalance->charges_paid_account_balance ?? 0, 2) }}</div>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-500">
            <p>Last updated: {{ $latestBalance->account_balance_time ?? $latestBalance->updated_at->format('F d, Y h:i A') }}</p>
        </div>
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <div class="flex items-center gap-2">
            <i class="fas fa-exclamation-triangle text-yellow-500"></i>
            <p class="text-yellow-800">No balance information available. Click "Check Balance" to request current balance.</p>
        </div>
    </div>
    @endif

    <!-- Balance History -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-semibold">Balance History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Working Account</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utility Account</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Charges Paid</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($balances as $balance)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">#{{ $balance->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">KES {{ number_format($balance->working_account_balance ?? 0, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">KES {{ number_format($balance->utility_account_balance ?? 0, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold">KES {{ number_format($balance->charges_paid_account_balance ?? 0, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $balance->status === 'success' ? 'bg-green-100 text-green-800' : 
                                   ($balance->status === 'failed' ? 'bg-red-100 text-red-800' :
                                   'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($balance->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $balance->requester->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $balance->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No balance history found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($balances->hasPages())
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            {{ $balances->links() }}
        </div>
        @endif
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

