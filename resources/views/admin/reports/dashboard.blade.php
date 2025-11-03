@extends('adminlte::page')

@section('title', 'Reports Dashboard')

@section('content_header')
    <h1 class="text-2xl font-bold">Reports Dashboard</h1>
@stop

@section('content')
<div class="container-fluid space-y-6">
    <!-- Report Categories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Clients Reports -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-lg font-semibold">Client Reports</h3>
                        <p class="text-blue-100 text-sm mt-1">View client statistics and analytics</p>
                    </div>
                    <i class="fas fa-users text-white text-4xl opacity-75"></i>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b">
                        <span class="text-gray-600">Total Clients</span>
                        <span class="font-semibold text-gray-900">{{ $stats['active_clients'] ?? 0 }}</span>
                    </div>
                    <a href="{{ route('reports.clients') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition">
                        <i class="fas fa-chart-bar mr-2"></i> View Client Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Loans Reports -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-lg font-semibold">Loan Reports</h3>
                        <p class="text-green-100 text-sm mt-1">Analyze loan performance</p>
                    </div>
                    <i class="fas fa-file-invoice-dollar text-white text-4xl opacity-75"></i>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b">
                        <span class="text-gray-600">Total Loans</span>
                        <span class="font-semibold text-gray-900">{{ $stats['disbursed_loans'] ?? 0 }}</span>
                    </div>
                    <a href="{{ route('reports.loans') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg transition">
                        <i class="fas fa-chart-line mr-2"></i> View Loan Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Users Reports -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-lg font-semibold">User Reports</h3>
                        <p class="text-purple-100 text-sm mt-1">Staff performance & activity</p>
                    </div>
                    <i class="fas fa-user-shield text-white text-4xl opacity-75"></i>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b">
                        <span class="text-gray-600">Total Users</span>
                        <span class="font-semibold text-gray-900">-</span>
                    </div>
                    <a href="{{ route('reports.users') }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-2 px-4 rounded-lg transition">
                        <i class="fas fa-chart-pie mr-2"></i> View User Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Loan Applications Reports -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-lg font-semibold">Loan Applications</h3>
                        <p class="text-yellow-100 text-sm mt-1">Application tracking & analytics</p>
                    </div>
                    <i class="fas fa-file-alt text-white text-4xl opacity-75"></i>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b">
                        <span class="text-gray-600">Total Applications</span>
                        <span class="font-semibold text-gray-900">{{ $stats['total_applications'] ?? 0 }}</span>
                    </div>
                    <a href="{{ route('reports.loan-applications') }}" class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white text-center py-2 px-4 rounded-lg transition">
                        <i class="fas fa-list-alt mr-2"></i> View Applications Report
                    </a>
                </div>
            </div>
        </div>

        <!-- Financial Reports -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="bg-gradient-to-r from-red-500 to-red-600 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-lg font-semibold">Financial Reports</h3>
                        <p class="text-red-100 text-sm mt-1">Revenue & disbursement analysis</p>
                    </div>
                    <i class="fas fa-money-bill-wave text-white text-4xl opacity-75"></i>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b">
                        <span class="text-gray-600">Total Disbursed</span>
                        <span class="font-semibold text-gray-900">KES {{ number_format($stats['total_disbursed'] ?? 0, 2) }}</span>
                    </div>
                    <a href="{{ route('reports.financial') }}" class="block w-full bg-red-600 hover:bg-red-700 text-white text-center py-2 px-4 rounded-lg transition">
                        <i class="fas fa-chart-area mr-2"></i> View Financial Reports
                    </a>
                </div>
            </div>
        </div>

        <!-- Disbursements Reports -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-lg font-semibold">Disbursements</h3>
                        <p class="text-indigo-100 text-sm mt-1">Disbursement tracking & history</p>
                    </div>
                    <i class="fas fa-hand-holding-usd text-white text-4xl opacity-75"></i>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b">
                        <span class="text-gray-600">Success Rate</span>
                        <span class="font-semibold text-gray-900">-</span>
                    </div>
                    <a href="{{ route('reports.disbursements') }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-4 rounded-lg transition">
                        <i class="fas fa-chart-bar mr-2"></i> View Disbursements Report
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Statistics -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Quick Statistics</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total_applications'] ?? 0 }}</div>
                <div class="text-sm text-gray-600 mt-1">Total Applications</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">{{ $stats['approved_applications'] ?? 0 }}</div>
                <div class="text-sm text-gray-600 mt-1">Approved</div>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_approvals'] ?? 0 }}</div>
                <div class="text-sm text-gray-600 mt-1">Pending Approvals</div>
            </div>
            <div class="text-center p-4 bg-red-50 rounded-lg">
                <div class="text-2xl font-bold text-red-600">{{ $stats['rejected_applications'] ?? 0 }}</div>
                <div class="text-sm text-gray-600 mt-1">Rejected</div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

