@extends('layouts.admin', ['title' => 'Create Role'])

@section('header')
    Create Role
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Role Details" description="Define the role name, add a short description and select the permissions it should control.">
            <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-6">
                @csrf
                @include('admin.roles.partials.form', [
                    'role' => null,
                    'permissions' => $permissions,
                    'rolePermissions' => [],
                    'mode' => 'create',
                ])
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.roles.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Cancel
                    </a>
                    <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        Save Role
                    </button>
                </div>
            </form>
        </x-admin.section>
    </div>
@endsection

