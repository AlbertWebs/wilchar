@extends('adminlte::page')

@section('title', 'Disbursements')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Disbursements</h1>
    </div>
@stop

@section('content')
<div class="container-fluid space-y-4">
    <!-- Statistics Cards -->
    @php
        $totalDisbursed = \App\Models\Disbursement::where('status', 'success')->sum('amount');
        $pendingDisbursed = \App\Models\Disbursement::where('status', 'pending')->sum('amount');
        $failedDisbursed = \App\Models\Disbursement::where('status', 'failed')->count();
        $totalCount = \App\Models\Disbursement::count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-green-600">KES {{ number_format($totalDisbursed, 2) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Disbursed</div>
                </div>
                <i class="fas fa-money-bill-wave text-3xl text-green-500 opacity-50"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-yellow-600">KES {{ number_format($pendingDisbursed, 2) }}</div>
                    <div class="text-sm text-gray-600 mt-1">Pending</div>
                </div>
                <i class="fas fa-clock text-3xl text-yellow-500 opacity-50"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-red-600">{{ $failedDisbursed }}</div>
                    <div class="text-sm text-gray-600 mt-1">Failed</div>
                </div>
                <i class="fas fa-times-circle text-3xl text-red-500 opacity-50"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-blue-600">{{ $totalCount }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Records</div>
                </div>
                <i class="fas fa-list text-3xl text-blue-500 opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow">
        <form method="GET" action="{{ route('disbursements.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Method</label>
                <select name="method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">All Methods</option>
                    <option value="mpesa_b2c" {{ request('method') == 'mpesa_b2c' ? 'selected' : '' }}>M-Pesa B2C</option>
                    <option value="bank" {{ request('method') == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('disbursements.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg ml-2">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Disbursements Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disbursement ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Application #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disbursed By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($disbursements as $disbursement)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-semibold text-gray-900">#{{ $disbursement->id }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-900">{{ $disbursement->loanApplication->application_number ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $disbursement->loanApplication->client->full_name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $disbursement->loanApplication->client->phone ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">KES {{ number_format($disbursement->amount ?? 0, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst(str_replace('_', ' ', $disbursement->method ?? 'N/A')) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $disbursement->status === 'success' ? 'bg-green-100 text-green-800' : 
                                   ($disbursement->status === 'failed' ? 'bg-red-100 text-red-800' :
                                   'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($disbursement->status ?? 'Pending') }}
                            </span>
                            @if($disbursement->status === 'pending')
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-spinner fa-spin"></i> Processing...
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $disbursement->recipient_phone ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $disbursement->disburser->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $disbursement->disbursement_date ? \Carbon\Carbon::parse($disbursement->disbursement_date)->format('M d, Y') : 
                               $disbursement->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('disbursements.show', $disbursement) }}" 
                                   class="text-blue-600 hover:text-blue-900" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($disbursement->status === 'failed')
                                <form action="{{ route('disbursements.retry', $disbursement) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900" 
                                            title="Retry" onclick="return confirm('Are you sure you want to retry this disbursement?')">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                            <div class="py-8">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                                <p class="text-lg">No disbursements found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($disbursements->hasPages())
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            {{ $disbursements->links() }}
        </div>
        @endif
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

