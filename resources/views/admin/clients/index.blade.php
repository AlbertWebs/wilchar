@extends('layouts.admin', ['title' => 'Clients'])

@section('header')
    Clients Directory
@endsection

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-base font-semibold text-slate-900">Clients</p>
                <p class="text-sm text-slate-500">Search, filter and manage your borrower profiles.</p>
            </div>
            <a
                href="{{ route('clients.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                New Client
            </a>
        </div>

        <x-admin.section title="All Clients">
            <div class="flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                <form method="GET" class="flex flex-wrap items-center gap-3">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search name, phone, ID..."
                        class="rounded-xl border-slate-200 bg-white px-3 py-2 text-sm text-slate-700"
                    >
                    <select name="status" class="rounded-xl border-slate-200 bg-white px-3 py-2 text-sm text-slate-700">
                        <option value="">Status: All</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                        <option value="blacklisted" @selected(request('status') === 'blacklisted')>Blacklisted</option>
                    </select>
                    <button type="submit" class="rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                        Filter
                    </button>
                </form>
            </div>

            <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Client</th>
                            <th class="px-4 py-3 text-left">Contact</th>
                            <th class="px-4 py-3 text-left">Business</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($clients as $client)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-slate-900">{{ $client->full_name ?? "{$client->first_name} {$client->last_name}" }}</p>
                                    <p class="text-xs text-slate-400">ID {{ $client->id_number }}</p>
                                </td>
                                <td class="px-4 py-4 text-slate-600">
                                    <p>{{ $client->phone }}</p>
                                    <p class="text-xs text-slate-400">{{ $client->email ?? 'No email' }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-slate-900">{{ $client->business_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $client->business_type }} · {{ $client->location }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                                        {{ $client->status === 'active' ? 'bg-emerald-50 text-emerald-600' : ($client->status === 'blacklisted' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600') }}">
                                        {{ ucfirst($client->status ?? 'inactive') }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('clients.show', $client) }}" class="rounded-xl border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                            View
                                        </a>
                                        <a href="{{ route('clients.edit', $client) }}" class="rounded-xl border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">
                                    No clients found. Create one to get started.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $clients->withQueryString()->links() }}
            </div>
        </x-admin.section>
    </div>
@endsection

