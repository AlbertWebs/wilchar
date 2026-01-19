@extends('layouts.admin', ['title' => 'Admin Dashboard'])

@section('content')
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .dashboard-card {
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }
        
        .dashboard-card:nth-child(1) { animation-delay: 0.1s; }
        .dashboard-card:nth-child(2) { animation-delay: 0.2s; }
        .dashboard-card:nth-child(3) { animation-delay: 0.3s; }
        .dashboard-card:nth-child(4) { animation-delay: 0.4s; }
        
        .stat-card-gradient-1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        
        .stat-card-gradient-2 {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
        }
        
        .stat-card-gradient-3 {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
        }
        
        .stat-card-gradient-4 {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            border: none;
        }
        
        .stat-card-gradient-5 {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            border: none;
        }
        
        .stat-card-gradient-6 {
            background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
            border: none;
        }
        
        .stat-card-gradient-7 {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            border: none;
        }
        
        .stat-card-gradient-8 {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            border: none;
        }
        
        .stat-card-icon-wrapper {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card-white {
            background: white;
            border: 1px solid #e2e8f0;
        }
        
        .pending-approval-card {
            transition: all 0.3s ease;
            border-left: 4px solid;
        }
        
        .pending-approval-card:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .pending-approval-card.loan-officer { border-left-color: #3b82f6; }
        .pending-approval-card.credit-officer { border-left-color: #8b5cf6; }
        .pending-approval-card.finance-officer { border-left-color: #f59e0b; }
        .pending-approval-card.director { border-left-color: #ef4444; }
        
        .team-performance-card {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border: 1px solid rgba(102, 126, 234, 0.2);
            transition: all 0.3s ease;
        }
        
        .team-performance-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.15);
            border-color: rgba(102, 126, 234, 0.4);
        }
        
        .financial-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .financial-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }
        
        .financial-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
        }
        
        .chart-container {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 16px;
            padding: 1.5rem;
        }
    </style>
    
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="rounded-2xl bg-gradient-to-r from-purple-600 via-pink-600 to-red-500 p-6 text-white shadow-lg mb-6">
            <h1 class="text-2xl font-bold mb-2">Welcome Back, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-purple-100">Here's what's happening with your business today.</p>
        </div>
        
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="dashboard-card">
                <x-admin.stat-card
                    title="Active Clients"
                    :value="number_format($totalClients)"
                    bg="stat-card-gradient-1"
                    text-color="text-white"
                >
                    <x-slot:icon>
                        <div class="stat-card-icon-wrapper flex h-12 w-12 items-center justify-center rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 018 17h8a4 4 0 013.879 2.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                            </svg>
                        </div>
                    </x-slot:icon>
                </x-admin.stat-card>
            </div>
            <div class="dashboard-card">
                <x-admin.stat-card
                    title="Loan Applications"
                    :value="number_format($loanApplications)"
                    bg="stat-card-gradient-2"
                    text-color="text-white"
                >
                    <x-slot:icon>
                        <div class="stat-card-icon-wrapper flex h-12 w-12 items-center justify-center rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-6 4h6M5 7h14M5 11h14M5 15h14M5 19h14" />
                            </svg>
                        </div>
                    </x-slot:icon>
                </x-admin.stat-card>
            </div>
            <div class="dashboard-card">
                <x-admin.stat-card
                    title="Approved Loans"
                    :value="number_format($approvedLoans)"
                    :trend="$approvalGrowth"
                    trend-label="vs last month"
                    bg="stat-card-gradient-3"
                    text-color="text-white"
                >
                    <x-slot:icon>
                        <div class="stat-card-icon-wrapper flex h-12 w-12 items-center justify-center rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12l6 6L20 6" />
                            </svg>
                        </div>
                    </x-slot:icon>
                </x-admin.stat-card>
            </div>
            <div class="dashboard-card">
                <x-admin.stat-card
                    title="Disbursed Amount"
                    :value="'KES ' . number_format($totalDisbursed, 2)"
                    bg="stat-card-gradient-4"
                    text-color="text-white"
                >
                    <x-slot:icon>
                        <div class="stat-card-icon-wrapper flex h-12 w-12 items-center justify-center rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.886 0-3.628.93-4.748 2.401L3 12l4.252 1.599C8.372 15.07 10.114 16 12 16s3.628-.93 4.748-2.401L21 12l-4.252-1.599C15.628 8.93 13.886 8 12 8z" />
                            </svg>
                        </div>
                    </x-slot:icon>
                </x-admin.stat-card>
            </div>
        </div>
        
        <!-- Additional Stats Row -->
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="dashboard-card">
                <x-admin.stat-card
                    title="Pending Approvals"
                    :value="number_format($pendingApprovals)"
                    bg="stat-card-gradient-5"
                    text-color="text-white"
                >
                    <x-slot:icon>
                        <div class="stat-card-icon-wrapper flex h-12 w-12 items-center justify-center rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </x-slot:icon>
                </x-admin.stat-card>
            </div>
            <div class="dashboard-card">
                <x-admin.stat-card
                    title="Total Collections"
                    :value="'KES ' . number_format($totalCollections, 2)"
                    bg="stat-card-gradient-6"
                    text-color="text-white"
                >
                    <x-slot:icon>
                        <div class="stat-card-icon-wrapper flex h-12 w-12 items-center justify-center rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </x-slot:icon>
                </x-admin.stat-card>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <x-admin.section class="lg:col-span-2" title="Disbursements vs Collections" description="Monthly M-PESA movements">
                <div class="chart-container">
                    <canvas id="disbursementCollectionChart" class="h-72 w-full"></canvas>
                </div>
            </x-admin.section>

            <x-admin.section title="Pending Approvals" description="Applications awaiting action">
                <div class="space-y-3">
                    @foreach($pendingApprovalBreakdown as $stage => $count)
                        <div class="pending-approval-card {{ str_replace('_', '-', $stage) }} flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-4 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ 
                                    $stage === 'loan_officer' ? 'bg-blue-100 text-blue-600' : 
                                    ($stage === 'credit_officer' ? 'bg-purple-100 text-purple-600' : 
                                    ($stage === 'finance_officer' ? 'bg-amber-100 text-amber-600' : 'bg-red-100 text-red-600')) 
                                }}">
                                    @if($stage === 'loan_officer')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    @elseif($stage === 'credit_officer')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($stage === 'finance_officer')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ ucfirst(str_replace('_', ' ', $stage)) }}</p>
                                    <p class="text-xs text-slate-500">In queue</p>
                                </div>
                            </div>
                            <span class="text-2xl font-bold {{ 
                                $stage === 'loan_officer' ? 'text-blue-600' : 
                                ($stage === 'credit_officer' ? 'text-purple-600' : 
                                ($stage === 'finance_officer' ? 'text-amber-600' : 'text-red-600')) 
                            }}">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </x-admin.section>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <x-admin.section title="Team Performance" description="Onboarding vs Disbursements by team">
                <div class="space-y-3">
                    @foreach($teamStats as $index => $team)
                        <div class="team-performance-card flex items-center justify-between rounded-xl px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br {{ 
                                    $index % 4 === 0 ? 'from-blue-500 to-blue-600' : 
                                    ($index % 4 === 1 ? 'from-purple-500 to-purple-600' : 
                                    ($index % 4 === 2 ? 'from-pink-500 to-pink-600' : 'from-indigo-500 to-indigo-600')) 
                                }} text-white font-bold text-lg shadow-lg">
                                    {{ substr($team['name'], 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $team['name'] }}</p>
                                    <p class="text-xs text-slate-600">
                                        {{ $team['onboardings'] }} onboardings · {{ $team['disbursements'] }} disbursements
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-lg font-bold text-emerald-600">
                                    {{ number_format($team['collection_rate'], 1) }}%
                                </span>
                                <p class="text-xs text-slate-500">collection</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-admin.section>

            <x-admin.section class="xl:col-span-2" title="Overdue Loans" description="Loans requiring collection & recovery">
                <div class="overflow-hidden rounded-xl border-2 border-red-100 bg-gradient-to-br from-red-50 to-pink-50 shadow-lg">
                    <table class="min-w-full divide-y divide-red-100">
                        <thead class="bg-gradient-to-r from-red-500 to-pink-500">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-white">Client</th>
                                <th class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-white">Loan</th>
                                <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wide text-white">Outstanding</th>
                                <th class="px-4 py-3 text-right text-xs font-bold uppercase tracking-wide text-white">Days Overdue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-100 bg-white text-sm">
                            @forelse($overdueLoans as $loan)
                                <tr class="transition-colors hover:bg-red-50">
                                    <td class="px-4 py-3">
                                        <p class="font-bold text-slate-800">{{ $loan->client->full_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $loan->client->phone }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800">
                                            {{ $loan->loan_type }}
                                        </span>
                                        <span class="ml-2 text-slate-600">{{ $loan->term_months }} months</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-bold text-red-700">
                                            KES {{ number_format($loan->outstanding_balance, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="inline-flex items-center rounded-full {{ 
                                            $loan->days_overdue > 30 ? 'bg-red-500 text-white' : 
                                            ($loan->days_overdue > 14 ? 'bg-orange-500 text-white' : 'bg-yellow-500 text-white')
                                        }} px-3 py-1 text-sm font-bold">
                                            {{ $loan->days_overdue }} days
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="h-16 w-16 text-green-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-sm font-semibold text-green-600">No overdue loans. Great job! 🎉</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-admin.section>
        </div>

        <x-admin.section title="Financial Snapshot" description="Expense, assets, liabilities overview">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="financial-card rounded-2xl border-2 border-red-200 bg-gradient-to-br from-red-500 to-pink-500 p-6 text-white shadow-xl">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold uppercase tracking-wide text-white/90">Monthly Expenses</p>
                        <svg class="h-6 w-6 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <p class="mt-2 text-3xl font-bold">KES {{ number_format($financialSummary['expenses'], 2) }}</p>
                </div>
                <div class="financial-card rounded-2xl border-2 border-emerald-200 bg-gradient-to-br from-emerald-500 to-teal-500 p-6 text-white shadow-xl">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold uppercase tracking-wide text-white/90">Assets Value</p>
                        <svg class="h-6 w-6 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <p class="mt-2 text-3xl font-bold">KES {{ number_format($financialSummary['assets'], 2) }}</p>
                </div>
                <div class="financial-card rounded-2xl border-2 border-amber-200 bg-gradient-to-br from-amber-500 to-orange-500 p-6 text-white shadow-xl">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold uppercase tracking-wide text-white/90">Liabilities</p>
                        <svg class="h-6 w-6 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="mt-2 text-3xl font-bold">KES {{ number_format($financialSummary['liabilities'], 2) }}</p>
                </div>
                <div class="financial-card rounded-2xl border-2 border-purple-200 bg-gradient-to-br from-purple-500 to-indigo-500 p-6 text-white shadow-xl">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-bold uppercase tracking-wide text-white/90">Shareholder Contributions</p>
                        <svg class="h-6 w-6 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <p class="mt-2 text-3xl font-bold">KES {{ number_format($financialSummary['shareholder_contributions'], 2) }}</p>
                </div>
            </div>
        </x-admin.section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('disbursementCollectionChart');
            if (!ctx) return;

            new ChartJS(ctx, {
                type: 'line',
                data: {
                    labels: @json($months),
                    datasets: [
                        {
                            label: 'Disbursements',
                            data: @json(array_values($disbursements)),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16,185,129,0.2)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                        },
                        {
                            label: 'Collections',
                            data: @json(array_values($collections)),
                            borderColor: '#8b5cf6',
                            backgroundColor: 'rgba(139,92,246,0.2)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 3,
                            pointBackgroundColor: '#8b5cf6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                        },
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: value => `KES ${Number(value).toLocaleString()}`,
                            },
                        },
                    },
                },
            });
        });
    </script>
@endpush

