@extends('adminlte::page')

@section('title', 'Create Disbursement')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Create Disbursement</h1>
        <a href="{{ route('loan-applications.show', $loanApplication) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <form action="{{ route('disbursements.store', $loanApplication) }}" method="POST" class="space-y-4">
        @csrf

        <!-- Application Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-file-alt text-blue-500"></i> Application Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Application Number</label>
                    <p class="text-gray-900 font-semibold">{{ $loanApplication->application_number }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                    <p class="text-gray-900 font-semibold">{{ $loanApplication->client->full_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount Requested</label>
                    <p class="text-gray-900">KES {{ number_format($loanApplication->amount, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount Approved</label>
                    <p class="text-gray-900 font-semibold text-green-600">KES {{ number_format($loanApplication->amount_approved ?? $loanApplication->amount, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Disbursement Details -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-money-bill-wave text-green-500"></i> Disbursement Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Disbursement Amount <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" step="0.01" min="1" required
                           value="{{ old('amount', $loanApplication->amount_approved ?? $loanApplication->amount) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter amount">
                    @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-500 mt-1">Maximum: KES {{ number_format($loanApplication->amount_approved ?? $loanApplication->amount, 2) }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recipient Phone (M-Pesa) <span class="text-red-500">*</span></label>
                    <input type="text" name="recipient_phone" required
                           value="{{ old('recipient_phone', $loanApplication->client->mpesa_phone ?? $loanApplication->client->phone ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="254712345678" pattern="^254[0-9]{9}$">
                    @error('recipient_phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-500 mt-1">Format: 254712345678 (must start with 254)</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                    <textarea name="remarks" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Optional remarks for the disbursement...">{{ old('remarks') }}</textarea>
                    @error('remarks')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Info Alert -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                <div>
                    <h4 class="font-medium text-blue-900 mb-1">Important Information</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• The disbursement will be processed via M-Pesa B2C</li>
                        <li>• Ensure the recipient phone number is correct and active</li>
                        <li>• The client will receive an M-Pesa notification</li>
                        <li>• Transaction receipt will be recorded automatically upon confirmation</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-end gap-4">
                <a href="{{ route('loan-applications.show', $loanApplication) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Cancel
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium">
                    <i class="fas fa-paper-plane"></i> Initiate Disbursement
                </button>
            </div>
        </div>
    </form>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

