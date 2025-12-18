@extends('layouts.admin', ['title' => 'M-Pesa'])

@section('header')
    M-Pesa
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section
            title="M-Pesa Operations"
            description="Manage STK Push, C2B collections, B2B payments, B2C disbursements, account balances and transaction status."
        >
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <a href="{{ route('mpesa.stk-push.index') }}"
                   class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-emerald-400 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Collections</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900">STK Push (Lipa na M-Pesa)</p>
                        </div>
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 8c-1.886 0-3.628.93-4.748 2.401L3 12l4.252 1.599C8.372 15.07 10.114 16 12 16s3.628-.93 4.748-2.401L21 12l-4.252-1.599C15.628 8.93 13.886 8 12 8z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-xs text-slate-500">
                        Initiate STK Push requests and review STK payments linked to loans.
                    </p>
                </a>

                <a href="{{ route('mpesa.c2b.index') }}"
                   class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-emerald-400 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Collections</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900">C2B PayBill / Till</p>
                        </div>
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-sky-100 text-sky-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 6v12m6-6H6"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-xs text-slate-500">
                        Monitor incoming C2B payments and how they map to loan accounts.
                    </p>
                </a>

                <a href="{{ route('mpesa.b2b.index') }}"
                   class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-emerald-400 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Payments</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900">B2B Payments</p>
                        </div>
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M3 10h18M7 15h10m-8 4h6M5 6l2-3h10l2 3"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-xs text-slate-500">
                        Send funds between M-Pesa shortcodes and review B2B transaction status.
                    </p>
                </a>

                <a href="{{ route('mpesa.b2c.index') }}"
                   class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-emerald-400 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Disbursements</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900">B2C (Loan Payouts)</p>
                        </div>
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 8v8m0 0l-3-3m3 3l3-3M4 7h16"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-xs text-slate-500">
                        Disburse approved loans to client phones via M-Pesa B2C and track status.
                    </p>
                </a>

                <a href="{{ route('mpesa.account-balance.index') }}"
                   class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-emerald-400 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Reconciliation</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900">Account Balance</p>
                        </div>
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-slate-900 text-slate-50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 8c-1.5-1-3-1-4 0s-1 3 0 4 2.5 1 4 2 2 3 0 4-3 1-4 0M12 3v18"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-xs text-slate-500">
                        Request and view your M-Pesa paybill working, utility and charges account balances.
                    </p>
                </a>

                <a href="{{ route('mpesa.transaction-status.index') }}"
                   class="flex flex-col justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm hover:border-emerald-400 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-slate-500">Support</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900">Transaction Status</p>
                        </div>
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-xs text-slate-500">
                        Query the status of specific M-Pesa transactions by receipt or transaction ID.
                    </p>
                </a>
            </div>
        </x-admin.section>
    </div>
@endsection


