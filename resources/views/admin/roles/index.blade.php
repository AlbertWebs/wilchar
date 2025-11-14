@extends('layouts.admin', ['title' => 'Roles & Permissions'])

@section('header')
    Roles & Permissions
@endsection

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-base font-semibold text-slate-900">Role Directory</p>
                <p class="text-sm text-slate-500">Create roles, assign fine-grained permissions and keep your back-office secure.</p>
            </div>
            <a
                href="{{ route('admin.roles.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                New Role
            </a>
        </div>

        <x-admin.section title="Existing Roles" description="Every role lists how many teammates are assigned and how many permissions are bundled.">
            <div class="grid gap-4 lg:grid-cols-2">
                @forelse($roles as $role)
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <p class="text-base font-semibold text-slate-900">{{ \Illuminate\Support\Str::headline($role->name) }}</p>
                                <p class="text-xs text-slate-500">
                                    {{ $role->description ?? 'No description provided for this role.' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="rounded-full bg-emerald-50 px-3 py-1 font-semibold text-emerald-600">
                                    {{ $role->permissions_count }} Permissions
                                </span>
                                <span class="rounded-full bg-indigo-50 px-3 py-1 font-semibold text-indigo-600">
                                    {{ $role->users_count }} Members
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center gap-3">
                            <a href="{{ route('admin.roles.edit', $role) }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                                Edit
                            </a>
                            @if($role->name !== 'Admin')
                                <form
                                    method="POST"
                                    action="{{ route('admin.roles.destroy', $role) }}"
                                    class="inline-flex"
                                    x-data
                                    @submit.prevent="Admin.confirmAction({ title: 'Delete Role?', text: 'This action cannot be undone.', icon: 'warning', confirmButtonText: 'Delete' }).then(confirmed => { if (confirmed) $el.submit(); })"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-xl bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-100">
                                        Delete
                                    </button>
                                </form>
                            @else
                                <span class="text-xs font-semibold text-amber-500">Admin role is protected</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-sm text-slate-500">
                        No roles set up yet. Start by creating the Admin, Loan Officer or Collections roles.
                    </div>
                @endforelse
            </div>
        </x-admin.section>
    </div>
@endsection

