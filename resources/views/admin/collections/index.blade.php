@extends('adminlte::page')

@section('title', 'Collections')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Collections</h1>
        <a href="{{ route('collections.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
            <i class="fas fa-plus"></i> New Collection
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
                    <div class="text-2xl font-bold text-green-600">KES {{ number_format($stats['total_collected'] ?? 0, 2) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Collected</div>
                </div>
                <i class="fas fa-hand-holding-usd text-3xl text-green-500 opacity-50"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-blue-600">KES {{ number_format($stats['this_month'] ?? 0, 2) }}</div>
                    <div class="text-sm text-gray-600 mt-1">This Month</div>
                </div>
                <i class="fas fa-calendar-alt text-3xl text-blue-500 opacity-50"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['total_count'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Payments</div>
                </div>
                <i class="fas fa-list text-3xl text-purple-500 opacity-50"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-orange-600">{{ $stats['by_payment_method']->count() ?? 0 }}</div>
                    <div class="text-sm text-gray-600 mt-1">Payment Methods</div>
                </div>
                <i class="fas fa-credit-card text-3xl text-orange-500 opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Payment Methods Summary -->
    @if($stats['by_payment_method'] && $stats['by_payment_method']->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Collections by Payment Method</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($stats['by_payment_method'] as $method)
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-700">{{ ucfirst($method->payment_method) }}</span>
                    <span class="text-xl font-bold text-green-600">KES {{ number_format($method->total, 2) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow">
        <form method="GET" action="{{ route('collections.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Client name, Phone, Reference..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Methods</option>
                    <option value="Mpesa" {{ request('payment_method') == 'Mpesa' ? 'selected' : '' }}>M-Pesa</option>
                    <option value="Bank" {{ request('payment_method') == 'Bank' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="Cash" {{ request('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Cheque" {{ request('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('collections.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg ml-2">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Collections Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Collection ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Received By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($repayments as $repayment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-semibold text-gray-900">#{{ $repayment->id }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">Loan #{{ $repayment->loan_id }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $repayment->loan->client->full_name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $repayment->loan->client->phone ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">KES {{ number_format($repayment->amount, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ $repayment->payment_method }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $repayment->reference ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $repayment->receiver->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $repayment->paid_at ? \Carbon\Carbon::parse($repayment->paid_at)->format('M d, Y') : $repayment->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('collections.show', $repayment) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('collections.edit', $repayment) }}" 
                                   class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('collections.destroy', $repayment) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Are you sure you want to delete this collection?')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                            <div class="py-8">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                                <p class="text-lg">No collections found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($repayments->hasPages())
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            {{ $repayments->links() }}
        </div>
        @endif
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

