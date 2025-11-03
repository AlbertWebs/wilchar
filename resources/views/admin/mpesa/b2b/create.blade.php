@extends('adminlte::page')

@section('title', 'Initiate B2B Transaction')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Initiate B2B Transaction</h1>
        <a href="{{ route('mpesa.b2b.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <form action="{{ route('mpesa.b2b.store') }}" method="POST" class="space-y-4">
        @csrf

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <i class="fas fa-exchange-alt text-purple-500"></i> B2B Transaction Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Receiver Shortcode (Party B) <span class="text-red-500">*</span></label>
                    <input type="text" name="party_b" required
                           value="{{ old('party_b') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter receiver shortcode">
                    @error('party_b')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" step="0.01" min="1" required
                           value="{{ old('amount') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Enter amount">
                    @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Account Reference</label>
                    <input type="text" name="account_reference" value="{{ old('account_reference') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Optional reference">
                    @error('account_reference')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                    <input type="text" name="remarks" value="{{ old('remarks') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Transaction remarks">
                    @error('remarks')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                <div>
                    <h4 class="font-medium text-blue-900 mb-1">B2B Transaction Information</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• This will send money from your shortcode to another business shortcode</li>
                        <li>• Transaction status will be updated automatically via callback</li>
                        <li>• Ensure the receiver shortcode is correct</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-end gap-4">
                <a href="{{ route('mpesa.b2b.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Cancel
                </a>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium">
                    <i class="fas fa-paper-plane"></i> Initiate B2B Transaction
                </button>
            </div>
        </div>
    </form>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

