@extends('adminlte::page')

@section('title', 'Client Details')

@section('content_header')
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">{{ $client->full_name }}</h1>
            <p class="text-sm text-gray-500 mt-1">Client Code: {{ $client->client_code }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('clients.edit', $client) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('clients.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid space-y-4">
    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="text-xl font-semibold mt-1">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $client->status === 'active' ? 'bg-green-100 text-green-800' : 
                               ($client->status === 'blacklisted' ? 'bg-red-100 text-red-800' :
                               'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($client->status) }}
                        </span>
                    </p>
                </div>
                <i class="fas fa-info-circle text-2xl text-blue-500"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">KYC Status</p>
                    <p class="text-xl font-semibold mt-1">
                        @if($client->kyc_completed)
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle"></i> Completed
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                        @endif
                    </p>
                </div>
                <i class="fas fa-file-check text-2xl text-purple-500"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Loan Applications</p>
                    <p class="text-xl font-semibold mt-1">{{ $client->loanApplications->count() }}</p>
                </div>
                <i class="fas fa-file-alt text-2xl text-green-500"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Personal Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-user text-blue-500"></i> Personal Information
            </h3>
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">First Name</label>
                        <p class="text-gray-900 font-semibold">{{ $client->first_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Last Name</label>
                        <p class="text-gray-900">{{ $client->last_name }}</p>
                    </div>
                    @if($client->middle_name)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Middle Name</label>
                        <p class="text-gray-900">{{ $client->middle_name }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="text-sm font-medium text-gray-500">ID Number</label>
                        <p class="text-gray-900">{{ $client->id_number }}</p>
                    </div>
                    @if($client->date_of_birth)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Date of Birth</label>
                        <p class="text-gray-900">{{ $client->date_of_birth->format('F d, Y') }}</p>
                    </div>
                    @endif
                    @if($client->gender)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Gender</label>
                        <p class="text-gray-900">{{ $client->gender }}</p>
                    </div>
                    @endif
                    @if($client->nationality)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nationality</label>
                        <p class="text-gray-900">{{ $client->nationality }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-phone text-green-500"></i> Contact Information
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Phone</label>
                    <p class="text-gray-900 font-semibold">{{ $client->phone }}</p>
                </div>
                @if($client->alternate_phone)
                <div>
                    <label class="text-sm font-medium text-gray-500">Alternate Phone</label>
                    <p class="text-gray-900">{{ $client->alternate_phone }}</p>
                </div>
                @endif
                @if($client->mpesa_phone)
                <div>
                    <label class="text-sm font-medium text-gray-500">M-Pesa Phone</label>
                    <p class="text-gray-900">{{ $client->mpesa_phone }}</p>
                </div>
                @endif
                @if($client->email)
                <div>
                    <label class="text-sm font-medium text-gray-500">Email</label>
                    <p class="text-gray-900">{{ $client->email }}</p>
                </div>
                @endif
                @if($client->address)
                <div>
                    <label class="text-sm font-medium text-gray-500">Address</label>
                    <p class="text-gray-900">{{ $client->address }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Business Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-briefcase text-purple-500"></i> Business Information
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Business Name</label>
                    <p class="text-gray-900 font-semibold">{{ $client->business_name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Business Type</label>
                    <p class="text-gray-900">{{ $client->business_type }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Location</label>
                    <p class="text-gray-900">{{ $client->location }}</p>
                </div>
                @if($client->occupation)
                <div>
                    <label class="text-sm font-medium text-gray-500">Occupation</label>
                    <p class="text-gray-900">{{ $client->occupation }}</p>
                </div>
                @endif
                @if($client->employer)
                <div>
                    <label class="text-sm font-medium text-gray-500">Employer</label>
                    <p class="text-gray-900">{{ $client->employer }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-orange-500"></i> Additional Information
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Created By</label>
                    <p class="text-gray-900">{{ $client->created_by }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Created At</label>
                    <p class="text-gray-900">{{ $client->created_at->format('F d, Y h:i A') }}</p>
                </div>
                @if($client->kyc_completed_at)
                <div>
                    <label class="text-sm font-medium text-gray-500">KYC Completed At</label>
                    <p class="text-gray-900">{{ $client->kyc_completed_at->format('F d, Y h:i A') }}</p>
                </div>
                @endif
                @if($client->credit_score)
                <div>
                    <label class="text-sm font-medium text-gray-500">Credit Score</label>
                    <p class="text-gray-900 font-semibold">{{ $client->credit_score }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

