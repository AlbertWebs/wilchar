@extends('adminlte::page')

@section('title', 'Pending Approvals')

@section('content_header')
    <h1 class="text-2xl font-bold">Pending Approvals</h1>
    <p class="text-sm text-gray-500 mt-1">Review and approve loan applications</p>
@stop

@section('content')
<div class="container-fluid space-y-4">
    @if($applications->count() > 0)
        <div class="grid grid-cols-1 gap-4">
            @foreach($applications as $application)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-4 mb-3">
                                <h3 class="text-lg font-semibold">{{ $application->application_number }}</h3>
                                <span class="px-2 py-1 text-xs font-medium rounded
                                    {{ $application->approval_stage === 'loan_officer' ? 'bg-yellow-100 text-yellow-800' :
                                       ($application->approval_stage === 'credit_officer' ? 'bg-blue-100 text-blue-800' :
                                       'bg-purple-100 text-purple-800') }}">
                                    {{ $application->current_stage_display }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <label class="text-gray-500">Client</label>
                                    <p class="font-medium">{{ $application->client->full_name }}</p>
                                </div>
                                <div>
                                    <label class="text-gray-500">Amount</label>
                                    <p class="font-medium">KES {{ number_format($application->amount, 2) }}</p>
                                </div>
                                <div>
                                    <label class="text-gray-500">Duration</label>
                                    <p class="font-medium">{{ $application->duration_months }} months</p>
                                </div>
                                <div>
                                    <label class="text-gray-500">Applied</label>
                                    <p class="font-medium">{{ $application->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="ml-4 flex gap-2">
                            <a href="{{ route('approvals.show', $application) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                                <i class="fas fa-eye"></i> Review
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($applications->hasPages())
        <div class="mt-4">
            {{ $applications->links() }}
        </div>
        @endif
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-check-circle text-6xl text-green-500 mb-4"></i>
            <h3 class="text-xl font-semibold mb-2">No Pending Approvals</h3>
            <p class="text-gray-500">All applications have been reviewed.</p>
        </div>
    @endif
</div>
@stop


