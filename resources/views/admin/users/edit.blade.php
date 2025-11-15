@extends('layouts.admin', ['title' => 'Edit User'])

@section('header')
    Edit User
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Update Account" description="Change user details and adjust their role assignments.">
            <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full rounded-xl border-slate-200" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full rounded-xl border-slate-200" required>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="2547XXXXXXXX">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">New Password (optional)</label>
                        <input type="password" name="password" class="mt-1 w-full rounded-xl border-slate-200" placeholder="Leave blank to keep current">
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="mt-1 w-full rounded-xl border-slate-200" placeholder="Confirm new password">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Assign Roles</label>
                        <div class="mt-2 grid gap-2 rounded-xl border border-slate-200 p-3">
                            @php
                                $selectedRoles = collect(old('roles', $userRoleIds))->map(fn($id) => (int) $id)->all();
                            @endphp
                           
                            @foreach($roles as $role)
                                <label class="flex items-center gap-3 text-sm text-slate-700">
                                    <input
                                        type="checkbox"
                                        name="roles[]"
                                        value="{{ $role->id }}"
                                        class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                        @checked(in_array($role->id, $selectedRoles, true))
                                    >
                                    <span>{{ \Illuminate\Support\Str::headline($role->name) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3">
                    <a href="{{ route('users.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                        ← Back to users
                    </a>
                    <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        Update User
                    </button>
                </div>
            </form>
        </x-admin.section>
    </div>
@endsection

