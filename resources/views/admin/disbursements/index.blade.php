@extends('layouts.admin', ['title' => 'Disbursements'])

@section('header')
    Disbursements
@endsection

@section('content')
    <x-admin.section title="Disbursement Queue" description="Track M-PESA payouts and manual releases.">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Loan App</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($disbursements as $disbursement)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-4 py-4">
                                <p class="font-semibold text-slate-900">#{{ $disbursement->loanApplication->application_number ?? 'N/A' }}</p>
                                <p class="text-xs text-slate-500">{{ optional($disbursement->loanApplication)->loan_type }}</p>
                            </td>
                            <td class="px-4 py-4 text-slate-700">
                                {{ optional($disbursement->loanApplication?->client)->full_name ?? 'Unknown' }}
                                <div class="text-xs text-slate-500">{{ optional($disbursement->loanApplication?->client)->phone }}</div>
                            </td>
                            <td class="px-4 py-4 text-slate-700">
                                <span class="font-semibold text-slate-900">KES {{ number_format($disbursement->amount, 2) }}</span>
                                <p class="text-xs text-slate-500">{{ strtoupper($disbursement->method ?? 'manual') }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                    @class([
                                        'bg-emerald-100 text-emerald-700' => $disbursement->status === 'success',
                                        'bg-amber-100 text-amber-700' => $disbursement->status === 'pending',
                                        'bg-rose-100 text-rose-600' => $disbursement->status === 'failed',
                                    ])
                                ">
                                    {{ ucfirst($disbursement->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('disbursements.show', $disbursement) }}" class="rounded-lg border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">No disbursements queued.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                {{ $disbursements->links() }}
            </div>
        </div>
    </x-admin.section>
@endsection

