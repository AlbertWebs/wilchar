@extends('adminlte::page')

@section('title', 'Loan Reports')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Loan Reports</h1>
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
            <div class="text-2xl font-bold text-blue-600">{{ $loanStats['total_loans'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Total Loans</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-green-600">{{ $loanStats['disbursed_loans'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Disbursed</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-purple-600">KES {{ number_format($loanStats['total_amount_disbursed'], 2) }}</div>
            <div class="text-sm text-gray-600 mt-1">Total Disbursed</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-orange-600">KES {{ number_format($loanStats['total_amount_pending'], 2) }}</div>
            <div class="text-sm text-gray-600 mt-1">Pending Amount</div>
        </div>
    </div>

    <!-- Loans by Type -->
    @if($loanStats['by_type']->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Loans by Type</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($loanStats['by_type'] as $type)
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-700">{{ ucfirst(str_replace('_', ' ', $type->loan_type)) }}</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $type->count }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow">
        <form method="GET" action="{{ route('reports.loans') }}" class="flex flex-wrap gap-4 items-end">
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="disbursed" {{ request('status') == 'disbursed' ? 'selected' : '' }}>Disbursed</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Loan Type</label>
                <select name="loan_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">All Types</option>
                    <option value="business" {{ request('loan_type') == 'business' ? 'selected' : '' }}>Business</option>
                    <option value="personal" {{ request('loan_type') == 'personal' ? 'selected' : '' }}>Personal</option>
                    <option value="agriculture" {{ request('loan_type') == 'agriculture' ? 'selected' : '' }}>Agriculture</option>
                    <option value="emergency" {{ request('loan_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                    <option value="education" {{ request('loan_type') == 'education' ? 'selected' : '' }}>Education</option>
                </select>
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('reports.loans') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg ml-2">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Loans Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loan ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Term</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Interest</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($loans as $loan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">#{{ $loan->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $loan->client->full_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst(str_replace('_', ' ', $loan->loan_type)) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">KES {{ number_format($loan->amount_requested, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $loan->term_months }} months</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($loan->interest_rate, 2) }}%</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $loan->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($loan->status === 'disbursed' ? 'bg-blue-100 text-blue-800' :
                                   ($loan->status === 'closed' ? 'bg-gray-100 text-gray-800' :
                                   'bg-yellow-100 text-yellow-800')) }}">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $loan->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">No loans found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($loans->hasPages())
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            {{ $loans->links() }}
        </div>
        @endif
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

