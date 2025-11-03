@extends('adminlte::page')

@section('title', 'User Reports')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">User / Staff Reports</h1>
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
            <div class="text-2xl font-bold text-blue-600">{{ $userStats['total_users'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Total Users</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-green-600">{{ $userStats['active_users'] }}</div>
            <div class="text-sm text-gray-600 mt-1">Active Users</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-purple-600">{{ $userStats['by_role']->sum('count') }}</div>
            <div class="text-sm text-gray-600 mt-1">Users with Roles</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-2xl font-bold text-orange-600">{{ $userStats['by_role']->count() }}</div>
            <div class="text-sm text-gray-600 mt-1">Role Types</div>
        </div>
    </div>

    <!-- Users by Role -->
    @if($userStats['by_role']->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Users by Role</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($userStats['by_role'] as $role)
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-gray-700">{{ $role->role_name }}</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $role->count }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow">
        <form method="GET" action="{{ route('reports.users') }}" class="flex flex-wrap gap-4 items-end">
            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">All Roles</option>
                    @foreach($userStats['by_role'] as $role)
                    <option value="{{ $role->role_name }}" {{ request('role') == $role->role_name ? 'selected' : '' }}>
                        {{ $role->role_name }}
                    </option>
                    @endforeach
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
                <a href="{{ route('reports.users') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg ml-2">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approvals</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Disbursements</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Repayments</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registered</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @foreach($user->roles as $role)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                {{ $role->name }}
                            </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $user->approvals_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $user->disbursements_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $user->repayments_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No users found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

