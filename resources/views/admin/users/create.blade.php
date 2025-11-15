@extends('layouts.admin', ['title' => 'Create User'])

@section('header')
    Create User
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Account Details" description="Create a user account and assign system roles.">
            <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="mt-1 w-full rounded-xl border-slate-200" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-xl border-slate-200" required>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="2547XXXXXXXX">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Password</label>
                        <input type="password" name="password" class="mt-1 w-full rounded-xl border-slate-200" required>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="mt-1 w-full rounded-xl border-slate-200" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Assign Roles</label>
                        <div class="mt-2 grid gap-2 rounded-xl border border-slate-200 p-3">
                            @foreach($roles as $role)
                                <label class="flex items-center gap-3 text-sm text-slate-700">
                                    <input
                                        type="checkbox"
                                        name="roles[]"
                                        value="{{ $role->id }}"
                                        class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                        @checked(collect(old('roles', []))->contains($role->id))
                                    >
                                    <span>{{ \Illuminate\Support\Str::headline($role->name) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('users.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        Create User
                    </button>
                </div>
            </form>
        </x-admin.section>
    </div>
@endsection

