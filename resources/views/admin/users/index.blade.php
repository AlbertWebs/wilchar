@extends('layouts.admin', ['title' => 'Users'])

@section('header')
    Team Management
@endsection

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-base font-semibold text-slate-900">User Directory</p>
                <p class="text-sm text-slate-500">Manage system users, assign roles and control platform access.</p>
            </div>
            <a
                href="{{ route('users.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                New User
            </a>
        </div>

        <x-admin.section title="Users" description="All active accounts with their role assignments and contact details.">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-4 py-3 text-left">User</th>
                            <th class="px-4 py-3 text-left">Roles</th>
                            <th class="px-4 py-3 text-left">Contact</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-400">Joined {{ $user->created_at->diffForHumans() }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($user->roles as $role)
                                            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                                {{ \Illuminate\Support\Str::headline($role->name) }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-slate-400">No role assigned</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-slate-600">
                                    <p>{{ $user->email }}</p>
                                    <p class="text-xs text-slate-400">{{ $user->phone ?? '—' }}</p>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('users.edit', $user) }}" class="rounded-xl border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                            Edit
                                        </a>
                                        <form
                                            method="POST"
                                            action="{{ route('users.destroy', $user) }}"
                                            class="inline-flex"
                                            x-data
                                            @submit.prevent="Admin.confirmAction({ title: 'Delete User?', text: 'This removes their access.', icon: 'warning', confirmButtonText: 'Delete' }).then(confirmed => { if (confirmed) $el.submit(); })"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-xl bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-600 hover:bg-rose-100">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500">
                                    No users found. Create the first user to get started.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </x-admin.section>
    </div>
@endsection

