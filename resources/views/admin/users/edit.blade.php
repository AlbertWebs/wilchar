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
                        <select name="roles[]" multiple class="mt-1 w-full rounded-xl border-slate-200">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @selected(collect(old('roles', $userRoleIds))->contains($role->id))>
                                    {{ \Illuminate\Support\Str::headline($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-slate-400">Hold CTRL / CMD to select multiple roles.</p>
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

