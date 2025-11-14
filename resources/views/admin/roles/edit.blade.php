@extends('layouts.admin', ['title' => 'Edit Role'])

@section('header')
    Edit Role
@endsection

@section('content')
    <div class="space-y-6">
        <x-admin.section title="Update Role" description="Adjust the role name, description or tweak the permissions to match your current policies.">
            <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                @include('admin.roles.partials.form', [
                    'role' => $role,
                    'permissions' => $permissions,
                    'rolePermissions' => $rolePermissions,
                    'mode' => 'edit',
                ])
                <div class="flex items-center justify-between gap-3">
                    <a href="{{ route('admin.roles.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                        ← Back to roles
                    </a>
                    <div class="flex items-center gap-3">
                        <button type="submit" class="rounded-xl bg-emerald-500 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                            Update Role
                        </button>
                    </div>
                </div>
            </form>
        </x-admin.section>
    </div>
@endsection

