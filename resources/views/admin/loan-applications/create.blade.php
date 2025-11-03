@extends('adminlte::page')

@section('title', 'New Loan Application')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Create Loan Application</h1>
        <a href="{{ route('loan-applications.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <form action="{{ route('loan-applications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-500"></i> Basic Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Client <span class="text-red-500">*</span></label>
                    <select name="client_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->full_name }} - {{ $client->phone }}</option>
                        @endforeach
                    </select>
                    @error('client_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Loan Type <span class="text-red-500">*</span></label>
                    <select name="loan_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Loan Type</option>
                        <option value="business">Business Loan</option>
                        <option value="personal">Personal Loan</option>
                        <option value="agriculture">Agriculture Loan</option>
                        <option value="emergency">Emergency Loan</option>
                        <option value="education">Education Loan</option>
                    </select>
                    @error('loan_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount Requested <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" step="0.01" min="1000" required
                           value="{{ old('amount') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter amount">
                    @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Interest Rate (%) <span class="text-red-500">*</span></label>
                    <input type="number" name="interest_rate" step="0.01" min="0" max="100" required
                           value="{{ old('interest_rate', 12.0) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter interest rate">
                    @error('interest_rate')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Duration (Months) <span class="text-red-500">*</span></label>
                    <input type="number" name="duration_months" min="1" max="120" required
                           value="{{ old('duration_months') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter duration in months">
                    @error('duration_months')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                    <textarea name="purpose" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                              placeholder="Describe the purpose of this loan...">{{ old('purpose') }}</textarea>
                    @error('purpose')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- KYC Documents -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-file-upload text-purple-500"></i> KYC Documents
            </h3>
            <div id="documents-container" class="space-y-4">
                <div class="document-item border border-gray-300 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Document Type</label>
                            <select name="document_types[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="id">ID/Passport</option>
                                <option value="passport">Passport</option>
                                <option value="selfie">Selfie</option>
                                <option value="proof_of_address">Proof of Address</option>
                                <option value="business_license">Business License</option>
                                <option value="bank_statement">Bank Statement</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Document File</label>
                            <input type="file" name="documents[]" accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" onclick="addDocument()" class="mt-4 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus"></i> Add Another Document
            </button>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-end gap-4">
                <a href="{{ route('loan-applications.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Cancel
                </a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium">
                    <i class="fas fa-save"></i> Submit Application
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function addDocument() {
    const container = document.getElementById('documents-container');
    const newItem = document.createElement('div');
    newItem.className = 'document-item border border-gray-300 rounded-lg p-4';
    newItem.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Document Type</label>
                <select name="document_types[]" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="id">ID/Passport</option>
                    <option value="passport">Passport</option>
                    <option value="selfie">Selfie</option>
                    <option value="proof_of_address">Proof of Address</option>
                    <option value="business_license">Business License</option>
                    <option value="bank_statement">Bank Statement</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Document File</label>
                <div class="flex gap-2">
                    <input type="file" name="documents[]" accept=".pdf,.jpg,.jpeg,.png"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <button type="button" onclick="this.closest('.document-item').remove()" 
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.appendChild(newItem);
}
</script>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop
