@extends('adminlte::page')

@section('title', 'M-Pesa Operations')

@section('content_header')
    <h1 class="text-2xl font-bold">M-Pesa Operations Dashboard</h1>
@stop

@section('content')
<div class="container-fluid space-y-6">
    <!-- M-Pesa Operations Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- STK Push -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-lg font-semibold">STK Push</h3>
                        <p class="text-green-100 text-sm mt-1">Lipa na M-Pesa Online</p>
                    </div>
                    <i class="fas fa-shopping-cart text-white text-4xl opacity-75"></i>
                </div>
            </div>
            <div class="p-6">
                <a href="{{ route('mpesa.stk-push.index') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg transition">
                    <i class="fas fa-arrow-right mr-2"></i> Manage STK Push
                </a>
            </div>
        </div>

        <!-- C2B Transactions -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-lg font-semibold">C2B Transactions</h3>
                        <p class="text-blue-100 text-sm mt-1">Customer to Business</p>
                    </div>
                    <i class="fas fa-arrow-down text-white text-4xl opacity-75"></i>
                </div>
            </div>
            <div class="p-6">
                <a href="{{ route('mpesa.c2b.index') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition">
                    <i class="fas fa-arrow-right mr-2"></i> View C2B Transactions
                </a>
            </div>
        </div>

        <!-- B2B Transactions -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-lg font-semibold">B2B Transactions</h3>
                        <p class="text-purple-100 text-sm mt-1">Business to Business</p>
                    </div>
                    <i class="fas fa-exchange-alt text-white text-4xl opacity-75"></i>
                </div>
            </div>
            <div class="p-6">
                <a href="{{ route('mpesa.b2b.index') }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-2 px-4 rounded-lg transition">
                    <i class="fas fa-arrow-right mr-2"></i> Manage B2B
                </a>
            </div>
        </div>

        <!-- B2C Disbursements -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-lg font-semibold">B2C Disbursements</h3>
                        <p class="text-yellow-100 text-sm mt-1">Business to Customer</p>
                    </div>
                    <i class="fas fa-arrow-up text-white text-4xl opacity-75"></i>
                </div>
            </div>
            <div class="p-6">
                <a href="{{ route('mpesa.b2c.index') }}" class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white text-center py-2 px-4 rounded-lg transition">
                    <i class="fas fa-arrow-right mr-2"></i> Manage B2C
                </a>
            </div>
        </div>

        <!-- Account Balance -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="bg-gradient-to-r from-red-500 to-red-600 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-lg font-semibold">Account Balance</h3>
                        <p class="text-red-100 text-sm mt-1">Check M-Pesa Balance</p>
                    </div>
                    <i class="fas fa-wallet text-white text-4xl opacity-75"></i>
                </div>
            </div>
            <div class="p-6">
                <a href="{{ route('mpesa.account-balance.index') }}" class="block w-full bg-red-600 hover:bg-red-700 text-white text-center py-2 px-4 rounded-lg transition">
                    <i class="fas fa-arrow-right mr-2"></i> Check Balance
                </a>
            </div>
        </div>

        <!-- Transaction Status -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-lg font-semibold">Transaction Status</h3>
                        <p class="text-indigo-100 text-sm mt-1">Query Transaction</p>
                    </div>
                    <i class="fas fa-search text-white text-4xl opacity-75"></i>
                </div>
            </div>
            <div class="p-6">
                <a href="{{ route('mpesa.transaction-status.index') }}" class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-4 rounded-lg transition">
                    <i class="fas fa-arrow-right mr-2"></i> Query Status
                </a>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

