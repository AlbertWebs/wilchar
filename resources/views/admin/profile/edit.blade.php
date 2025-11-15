@extends('layouts.admin', ['title' => 'My Profile'])

@section('header')
    My Profile
@endsection

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <x-admin.section title="Account Details" description="Update how your name and email appear across the admin portal.">
            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-6 max-w-3xl">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full rounded-xl border-slate-200 @error('name') border-rose-400 focus:border-rose-400 focus:ring-rose-300 @enderror" required>
                        @error('name')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full rounded-xl border-slate-200 @error('email') border-rose-400 focus:border-rose-400 focus:ring-rose-300 @enderror" required>
                        @error('email')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Phone (optional)</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 w-full rounded-xl border-slate-200 @error('phone') border-rose-400 focus:border-rose-400 focus:ring-rose-300 @enderror">
                        @error('phone')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Update Password</p>
                    <p class="text-xs text-slate-400">Leave blank to keep existing password.</p>
                    <div class="mt-4 grid gap-4 md:grid-cols-3">
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Current Password</label>
                            <input type="password" name="current_password" class="mt-1 w-full rounded-xl border-slate-200 @error('current_password') border-rose-400 focus:border-rose-400 focus:ring-rose-300 @enderror" autocomplete="current-password">
                            @error('current_password')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">New Password</label>
                            <input type="password" name="password" class="mt-1 w-full rounded-xl border-slate-200 @error('password') border-rose-400 focus:border-rose-400 focus:ring-rose-300 @enderror" autocomplete="new-password">
                            @error('password')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="mt-1 w-full rounded-xl border-slate-200" autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        Save Changes
                    </button>
                    <a href="{{ url()->previous() }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Cancel</a>
                </div>
            </form>
        </x-admin.section>
    </div>
@endsection

