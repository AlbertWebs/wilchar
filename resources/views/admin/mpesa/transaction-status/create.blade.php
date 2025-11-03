@extends('adminlte::page')

@section('title', 'Query Transaction Status')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Query Transaction Status</h1>
        <a href="{{ route('mpesa.transaction-status.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <form action="{{ route('mpesa.transaction-status.store') }}" method="POST" class="space-y-4">
        @csrf

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-search text-indigo-500"></i> Transaction Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Transaction ID <span class="text-red-500">*</span></label>
                    <input type="text" name="transaction_id" required
                           value="{{ old('transaction_id') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter M-Pesa transaction ID">
                    @error('transaction_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-500 mt-1">Enter the M-Pesa transaction ID you want to query</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                    <input type="text" name="remarks" value="{{ old('remarks') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Optional remarks">
                    @error('remarks')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                <div>
                    <h4 class="font-medium text-blue-900 mb-1">Transaction Status Query Information</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Enter the M-Pesa transaction ID you want to query</li>
                        <li>• The system will query M-Pesa for the transaction status</li>
                        <li>• Results will be updated automatically via callback</li>
                        <li>• Transaction ID format: Usually starts with QMT or similar</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-end gap-4">
                <a href="{{ route('mpesa.transaction-status.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium">
                    <i class="fas fa-search"></i> Query Transaction Status
                </button>
            </div>
        </div>
    </form>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

