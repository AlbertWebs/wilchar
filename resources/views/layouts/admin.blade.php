<!DOCTYPE html>
@php
    use Illuminate\Support\Str;
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- PWA Meta Tags -->
    <meta name="application-name" content="Wilchar LMS">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Wilchar LMS">
    <meta name="description" content="Comprehensive loan management system with flexible repayment options, M-Pesa integration, and excellent customer service.">
    <meta name="format-detection" content="telephone=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="msapplication-TileColor" content="#10b981">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="theme-color" content="#10b981">
    
    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/icons/icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="167x167" href="/icons/icon-192x192.png">
    
    <!-- Manifest -->
    <link rel="manifest" href="/manifest.json">

    <title>{{ $title ?? config('app.name', 'Admin Panel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/admin.js', 'resources/js/app.js'])

    <script>
        window.csrfToken = '{{ csrf_token() }}';
    </script>
    @stack('head')
</head>
<body class="h-full font-sans antialiased text-slate-900">
    <div
        x-data="{ sidebarOpen: window.innerWidth >= 1024 }"
        class="flex h-screen overflow-hidden bg-slate-100"
        style="height: 100vh;"
    >
        <!-- Sidebar -->
        <aside
            x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col overflow-y-auto bg-slate-900 text-slate-100 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0"
            x-cloak
        >
            <div class="flex items-center gap-2 px-6 py-5">
                <button
                    class="rounded-full bg-slate-800 p-2 text-slate-300 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 lg:hidden"
                    @click="sidebarOpen = false"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-lg font-semibold text-emerald-50">AD</span>
                    <div>
                        <p class="text-base font-semibold text-white">Admin Dashboard</p>
                        <p class="text-xs text-slate-400">{{ config('app.name') }}</p>
                    </div>
                </a>
            </div>
            <nav class="flex-1 px-4 pb-6">
                <p class="px-2 pt-4 text-xs font-semibold uppercase tracking-wide text-slate-500">Overview</p>
                <ul class="mt-3 space-y-1 text-sm">
                    @can('dashboard.view')
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-emerald-400 group-hover:text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    @endcan
                    @can('clients.view')
                    <li>
                        <a href="{{ route('admin.clients.index') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('admin.clients.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-purple-400 group-hover:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Client Management</span>
                        </a>
                    </li>
                    @endcan
                    @can('loan-applications.view')
                    <li>
                        <a href="{{ route('loan-applications.index') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('loan-applications.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-indigo-400 group-hover:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 3h-9A1.5 1.5 0 006 4.5v15L12 16l6 3.5v-15A1.5 1.5 0 0016.5 3z" />
                            </svg>
                            <span>Loan Applications</span>
                        </a>
                    </li>
                    @endcan
                    @can('loans.view')
                    <li>
                        <a href="{{ route('loans.index') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('loans.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-amber-400 group-hover:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.886 0-3.628.93-4.748 2.401L3 12l4.252 1.599C8.372 15.07 10.114 16 12 16s3.628-.93 4.748-2.401L21 12l-4.252-1.599C15.628 8.93 13.886 8 12 8z" />
                            </svg>
                            <span>Loans Portfolio</span>
                        </a>
                    </li>
                    @endcan
                    @can('disbursements.view')
                    <li>
                        <a href="{{ route('disbursements.index') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('disbursements.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-rose-400 group-hover:text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v8m4-4H8" />
                            </svg>
                            <span>Disbursements</span>
                        </a>
                    </li>
                    @endcan
                    @can('collections.view')
                    <li>
                        <a href="{{ route('collections.index') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('collections.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-cyan-400 group-hover:text-cyan-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h8m-8 5h16" />
                            </svg>
                            <span>Collections & Recovery</span>
                        </a>
                    </li>
                    @endcan
                </ul>

                <p class="px-2 pt-6 text-xs font-semibold uppercase tracking-wide text-slate-500">Organization</p>
                <ul class="mt-3 space-y-1 text-sm">
                    @can('roles.view')
                    <li>
                        <a href="{{ route('admin.roles.index') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('admin.roles.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-fuchsia-400 group-hover:text-fuchsia-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A4 4 0 018 17h8a4 4 0 013.879 2.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                            </svg>
                            <span>Roles & Permissions</span>
                        </a>
                    </li>
                    @endcan
                    @can('users.view')
                    <li>
                        <a href="{{ route('users.index') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('users.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-teal-400 group-hover:text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7a4 4 0 118 0 4 4 0 01-8 0zm12 13a8 8 0 10-16 0" />
                            </svg>
                            <span>Team Management</span>
                        </a>
                    </li>
                    @endcan
                    @can('reports.view')
                    <li>
                        <a href="{{ route('reports.dashboard') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('reports.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-lime-400 group-hover:text-lime-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h10m-10 6h16" />
                            </svg>
                            <span>Reports</span>
                        </a>
                    </li>
                    @endcan
                    @can('site-settings.view')
                    <li>
                        <a href="{{ route('admin.site-settings.edit') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('admin.site-settings.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-slate-400 group-hover:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 4.5l1-1 1 1m8 8l1 1-1 1m-15-8l-1 1 1 1m8 8l-1 1 1 1M12 9v3l2 2" />
                            </svg>
                            <span>System Settings</span>
                        </a>
                    </li>
                    @endcan
                </ul>

                <p class="px-2 pt-6 text-xs font-semibold uppercase tracking-wide text-slate-500">Website</p>
                <ul class="mt-3 space-y-1 text-sm">
                    <li>
                        <a href="{{ route('admin.website.pages.index') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('admin.website.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-blue-400 group-hover:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Pages</span>
                        </a>
                    </li>
                </ul>

                <p class="px-2 pt-6 text-xs font-semibold uppercase tracking-wide text-slate-500">Financial</p>
                <ul class="mt-3 space-y-1 text-sm">
                    @can('loan-products.view')
                    <li>
                        <a href="{{ route('loan-products.index') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('loan-products.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-sky-400 group-hover:text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c1.657 0 3-1.343 3-3S13.657 2 12 2 9 3.343 9 5s1.343 3 3 3zM5 21h14l-3-8H8l-3 8z" />
                            </svg>
                            <span>Loan Products</span>
                        </a>
                    </li>
                    @endcan
                    @can('disbursements.view')
                    @if(auth()->user()->hasAnyRole(['Admin', 'Finance', 'Director']))
                        <li>
                            <a href="{{ route('finance-disbursements.index') }}"
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('finance-disbursements.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                <svg class="h-5 w-5 shrink-0 text-emerald-400 group-hover:text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v8m4-4H8m10 9H6a2 2 0 01-2-2V7.5A1.5 1.5 0 015.5 6h13A1.5 1.5 0 0120 7.5V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Finance Desk</span>
                            </a>
                        </li>
                    @endif
                    @endcan
                    @can('collections.view')
                    @if(auth()->user()->hasAnyRole(['Admin', 'Finance', 'Director', 'Collection Officer']))
                        <li>
                            <a href="{{ route('payments.index') }}"
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('payments.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                <svg class="h-5 w-5 shrink-0 text-cyan-400 group-hover:text-cyan-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a5 5 0 00-10 0v2M5 10h14l-1 11H6L5 10zm6 4v4m4-4v4" />
                                </svg>
                                <span>Client Payments</span>
                            </a>
                        </li>
                    @endif
                    @endcan
                    @can('mpesa.view')
                        <li>
                            <a href="{{ route('mpesa.dashboard') }}"
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('mpesa.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                <svg class="h-5 w-5 shrink-0 text-emerald-400 group-hover:text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.886 0-3.628.93-4.748 2.401L3 12l4.252 1.599C8.372 15.07 10.114 16 12 16s3.628-.93 4.748-2.401L21 12l-4.252-1.599C15.628 8.93 13.886 8 12 8z" />
                                </svg>
                                <span>M-Pesa Overview</span>
                            </a>
                        </li>
                        @can('mpesa.stk-push')
                        <li>
                            <a href="{{ route('mpesa.stk-push.index') }}"
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 pl-10 transition hover:bg-slate-800/60 {{ request()->routeIs('mpesa.stk-push.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                <svg class="h-4 w-4 shrink-0 text-emerald-300 group-hover:text-emerald-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m6-6H6" />
                                </svg>
                                <span>STK Push</span>
                            </a>
                        </li>
                        @endcan
                        @can('mpesa.c2b')
                        <li>
                            <a href="{{ route('mpesa.c2b.index') }}"
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 pl-10 transition hover:bg-slate-800/60 {{ request()->routeIs('mpesa.c2b.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                <svg class="h-4 w-4 shrink-0 text-sky-300 group-hover:text-sky-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h8m-8 5h16" />
                                </svg>
                                <span>C2B Collections</span>
                            </a>
                        </li>
                        @endcan
                        @can('mpesa.b2b')
                        <li>
                            <a href="{{ route('mpesa.b2b.index') }}"
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 pl-10 transition hover:bg-slate-800/60 {{ request()->routeIs('mpesa.b2b.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                <svg class="h-4 w-4 shrink-0 text-indigo-300 group-hover:text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h10m-8 4h6M5 6l2-3h10l2 3" />
                                </svg>
                                <span>B2B Payments</span>
                            </a>
                        </li>
                        @endcan
                        @can('mpesa.b2c')
                        <li>
                            <a href="{{ route('mpesa.b2c.index') }}"
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 pl-10 transition hover:bg-slate-800/60 {{ request()->routeIs('mpesa.b2c.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                <svg class="h-4 w-4 shrink-0 text-amber-300 group-hover:text-amber-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v8m4-4H8" />
                                </svg>
                                <span>B2C Disbursements</span>
                            </a>
                        </li>
                        @endcan
                        @can('mpesa.account-balance')
                        <li>
                            <a href="{{ route('mpesa.account-balance.index') }}"
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 pl-10 transition hover:bg-slate-800/60 {{ request()->routeIs('mpesa.account-balance.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                <svg class="h-4 w-4 shrink-0 text-slate-300 group-hover:text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c1.5-1 3-1 4 0s1 3 0 4-2.5 1-4 2-2 3 0 4 3 1 4 0M12 3v18" />
                                </svg>
                                <span>Account Balance</span>
                            </a>
                        </li>
                        @endcan
                        @can('mpesa.transaction-status')
                        <li>
                            <a href="{{ route('mpesa.transaction-status.index') }}"
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 pl-10 transition hover:bg-slate-800/60 {{ request()->routeIs('mpesa.transaction-status.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                                <svg class="h-4 w-4 shrink-0 text-rose-300 group-hover:text-rose-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Transaction Status</span>
                            </a>
                        </li>
                        @endcan
                    @endcan
                    
                    @if(config('app.sandbox_mode') || app()->environment('local'))
                        <li>
                            <a href="{{ route('sandbox.purge.index') }}"
                               class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-rose-900/40 {{ request()->routeIs('sandbox.*') ? 'bg-rose-900/40 text-white' : 'text-rose-200' }}">
                                <svg class="h-5 w-5 shrink-0 text-rose-400 group-hover:text-rose-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v8m4-4H8m11 11H5a2 2 0 01-2-2V7.5A1.5 1.5 0 014.5 6h15A1.5 1.5 0 0121 7.5V21a2 2 0 01-2 2z" />
                                </svg>
                                <span>Sandbox Purge</span>
                            </a>
                        </li>
                    @endif
                    
                    @can('teams.view')
                    <li>
                        <a href="{{ route('teams.index') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('teams.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-amber-400 group-hover:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5V4H2v16h5m10 0h-6a3 3 0 11-6 0h12a3 3 0 11-6 0" />
                            </svg>
                            <span>Teams</span>
                        </a>
                    </li>
                    @endcan
                    @can('expenses.view')
                    <li>
                        <a href="{{ route('expenses.index') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('expenses.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-orange-400 group-hover:text-orange-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9h18M3 15h18M9 3v18" />
                            </svg>
                            <span>Expense Management</span>
                        </a>
                    </li>
                    @endcan
                    @can('assets.view')
                    <li>
                        <a href="{{ route('assets.index') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('assets.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-emerald-400 group-hover:text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M6 10v8m12-8v8M3 18h18" />
                            </svg>
                            <span>Assets</span>
                        </a>
                    </li>
                    @endcan
                    @can('liabilities.view')
                    <li>
                        <a href="{{ route('liabilities.index') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('liabilities.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-red-400 group-hover:text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l4 2" />
                            </svg>
                            <span>Liabilities</span>
                        </a>
                    </li>
                    @endcan
                    @can('shareholders.view')
                    <li>
                        <a href="{{ route('shareholders.index') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('shareholders.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-purple-400 group-hover:text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h4m-4 4h10M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" />
                            </svg>
                            <span>Shareholders</span>
                        </a>
                    </li>
                    @endcan
                    @can('trial-balances.view')
                    <li>
                        <a href="{{ route('trial-balances.index') }}"
                           class="group flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-800/60 {{ request()->routeIs('trial-balances.*') ? 'bg-slate-800 text-white' : 'text-slate-300' }}">
                            <svg class="h-5 w-5 shrink-0 text-yellow-400 group-hover:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-6h6v6m-7-8h8l-4-4-4 4z" />
                            </svg>
                            <span>Trial Balance</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </nav>

            <div class="border-t border-slate-800/80 px-4 py-4">
                <div class="flex items-center gap-3 rounded-lg bg-slate-800/40 p-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-700 text-sm font-semibold">
                        {{ strtoupper(Str::substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400">{{ auth()->user()->roles->pluck('name')->implode(', ') }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full bg-slate-700 p-2 text-slate-300 transition hover:bg-slate-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12H3m0 0l4-4m-4 4l4 4m12-8v8" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Content -->
        <div class="flex flex-1 flex-col">
            <header class="sticky top-0 z-30 flex items-center justify-between border-b border-slate-200 bg-white/70 px-6 py-4 backdrop-blur supports-backdrop-blur:bg-white/60">
                <div class="flex items-center gap-3">
                    <button
                        class="rounded-full border border-slate-200 bg-white p-2 text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 lg:hidden"
                        @click="sidebarOpen = true"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8h16M4 16h16" />
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-semibold text-slate-900">
                            @hasSection('header')
                                @yield('header')
                            @else
                                {{ $header ?? 'Dashboard' }}
                            @endif
                        </h1>
                        @isset($breadcrumbs)
                            <nav class="mt-1 flex items-center text-xs text-slate-500">
                                {!! $breadcrumbs !!}
                            </nav>
                        @endisset
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button
                        x-on:click="$dispatch('toggle-theme')"
                        class="hidden rounded-full border border-slate-200 bg-white p-2 text-slate-600 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m8-9h1M3 12H2m15.364-6.364l.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>
                    <div class="relative" x-data="{ open:false }" @keydown.escape.window="open=false">
                        <button @click="open = !open" class="flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <span>{{ auth()->user()->name }}</span>
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div
                            x-cloak
                            x-show="open"
                            x-transition
                            @click.away="open = false"
                            class="absolute right-0 z-40 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl"
                        >
                            <div class="px-4 py-3 text-sm">
                                <p class="font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="border-t border-slate-100">
                                <a
                                    href="{{ route('admin.profile.edit') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 transition hover:bg-slate-50"
                                >
                                    <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A4 4 0 018 17h8a4 4 0 013.879 2.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                                    </svg>
                                    My Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-rose-600 transition hover:bg-rose-50">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12H3m0 0l4-4m-4 4l4 4m12-8v8" />
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
                    @yield('content', $slot ?? '')
                </div>
            </main>
        </div>
    </div>

    <!-- Modal -->
    <div
        x-data
        x-cloak
        x-show="$store.modal && $store.modal.open"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 px-4 py-10 backdrop-blur"
    >
        <div
            x-transition
            x-bind:class="{
                'max-w-lg': $store.modal && $store.modal.size === 'md',
                'max-w-2xl': $store.modal && $store.modal.size === 'lg',
                'max-w-4xl': $store.modal && $store.modal.size === 'xl',
            }"
            class="w-full overflow-hidden rounded-2xl bg-white shadow-2xl"
        >
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-slate-900" x-text="$store.modal && $store.modal.title ? $store.modal.title : ''"></h2>
                <button class="rounded-full bg-slate-100 p-2 text-slate-500 hover:bg-slate-200" @click="$store.modal && $store.modal.close()">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="relative max-h-[70vh] overflow-y-auto px-6 py-5 text-sm text-slate-700" x-data>
                <template x-if="$store.modal && $store.modal.loading">
                    <div class="absolute inset-0 z-10 flex items-center justify-center bg-white/80">
                        <svg class="h-8 w-8 animate-spin text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </div>
                </template>
                <div
                    data-modal-body
                    x-effect="
                        if ($store.modal) {
                            $el.innerHTML = $store.modal.body || '';
                            if ($store.modal.body && window.Alpine?.initTree) {
                                queueMicrotask(() => window.Alpine.initTree($el));
                            }
                        }
                    "
                ></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const success = @json(session('success'));
            const error = @json(session('error'));
            const permissionError = @json(session('permission_error'));

            if (success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: success,
                    timer: 2800,
                    showConfirmButton: false,
                });
            }

            if (error && !permissionError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error,
                });
            }

            // Show permission error popup with detailed information
            if (permissionError) {
                const requiredRoles = permissionError.required_roles || [];
                const userRoles = permissionError.user_roles || [];
                
                let html = `<div class="text-left">
                    <p class="mb-4 text-slate-700">${permissionError.message || 'You do not have permission to access this resource.'}</p>`;
                
                if (requiredRoles.length > 0) {
                    html += `<div class="mb-3">
                        <p class="font-semibold text-slate-900 mb-2">Required Role(s):</p>
                        <div class="flex flex-wrap gap-2">`;
                    requiredRoles.forEach(role => {
                        html += `<span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800">${role}</span>`;
                    });
                    html += `</div></div>`;
                }
                
                if (userRoles.length > 0) {
                    html += `<div class="mb-3">
                        <p class="font-semibold text-slate-900 mb-2">Your Current Role(s):</p>
                        <div class="flex flex-wrap gap-2">`;
                    userRoles.forEach(role => {
                        html += `<span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">${role}</span>`;
                    });
                    html += `</div></div>`;
                } else {
                    html += `<div class="mb-3">
                        <p class="text-sm text-slate-600">You currently have no assigned roles.</p>
                    </div>`;
                }
                
                html += `<p class="mt-4 text-sm text-slate-600">Please contact your administrator to request the necessary permissions.</p></div>`;
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Access Denied',
                    html: html,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#10b981',
                    width: '500px'
                });
            }
        });

        // Handle AJAX 403 errors globally
        document.addEventListener('DOMContentLoaded', () => {
            // Intercept fetch requests
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                return originalFetch.apply(this, args)
                    .then(response => {
                        if (response.status === 403) {
                            return response.json().then(data => {
                                const requiredRoles = data.required_roles || [];
                                const userRoles = data.user_roles || [];
                                
                                let html = `<div class="text-left">
                                    <p class="mb-4 text-slate-700">${data.message || 'You do not have permission to perform this action.'}</p>`;
                                
                                if (requiredRoles.length > 0) {
                                    html += `<div class="mb-3">
                                        <p class="font-semibold text-slate-900 mb-2">Required Role(s):</p>
                                        <div class="flex flex-wrap gap-2">`;
                                    requiredRoles.forEach(role => {
                                        html += `<span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800">${role}</span>`;
                                    });
                                    html += `</div></div>`;
                                }
                                
                                if (userRoles.length > 0) {
                                    html += `<div class="mb-3">
                                        <p class="font-semibold text-slate-900 mb-2">Your Current Role(s):</p>
                                        <div class="flex flex-wrap gap-2">`;
                                    userRoles.forEach(role => {
                                        html += `<span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">${role}</span>`;
                                    });
                                    html += `</div></div>`;
                                } else {
                                    html += `<div class="mb-3">
                                        <p class="text-sm text-slate-600">You currently have no assigned roles.</p>
                                    </div>`;
                                }
                                
                                html += `<p class="mt-4 text-sm text-slate-600">Please contact your administrator to request the necessary permissions.</p></div>`;
                                
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Access Denied',
                                    html: html,
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#10b981',
                                    width: '500px'
                                });
                                
                                throw new Error(data.message || 'Forbidden');
                            });
                        }
                        return response;
                    });
            };

            // Intercept jQuery AJAX requests (if jQuery is used)
            if (typeof jQuery !== 'undefined') {
                $(document).ajaxError(function(event, xhr) {
                    if (xhr.status === 403) {
                        const data = xhr.responseJSON || {};
                        const requiredRoles = data.required_roles || [];
                        const userRoles = data.user_roles || [];
                        
                        let html = `<div class="text-left">
                            <p class="mb-4 text-slate-700">${data.message || 'You do not have permission to perform this action.'}</p>`;
                        
                        if (requiredRoles.length > 0) {
                            html += `<div class="mb-3">
                                <p class="font-semibold text-slate-900 mb-2">Required Role(s):</p>
                                <div class="flex flex-wrap gap-2">`;
                            requiredRoles.forEach(role => {
                                html += `<span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800">${role}</span>`;
                            });
                            html += `</div></div>`;
                        }
                        
                        if (userRoles.length > 0) {
                            html += `<div class="mb-3">
                                <p class="font-semibold text-slate-900 mb-2">Your Current Role(s):</p>
                                <div class="flex flex-wrap gap-2">`;
                            userRoles.forEach(role => {
                                html += `<span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">${role}</span>`;
                            });
                            html += `</div></div>`;
                        } else {
                            html += `<div class="mb-3">
                                <p class="text-sm text-slate-600">You currently have no assigned roles.</p>
                            </div>`;
                        }
                        
                        html += `<p class="mt-4 text-sm text-slate-600">Please contact your administrator to request the necessary permissions.</p></div>`;
                        
                        Swal.fire({
                            icon: 'warning',
                            title: 'Access Denied',
                            html: html,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#10b981',
                            width: '500px'
                        });
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>

