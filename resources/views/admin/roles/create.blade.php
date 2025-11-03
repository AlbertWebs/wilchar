@extends('adminlte::page')

@section('title', 'Create Role')

@section('content_header')
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Create New Role</h1>
        <a href="{{ route('admin.roles.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf

        <div class="bg-white rounded-lg shadow p-6 mb-4">
            <h3 class="text-lg font-semibold mb-4">Role Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="e.g., Manager, Supervisor">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-500 mt-1">Role name will be converted to lowercase with hyphens</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input type="text" name="description" value="{{ old('description') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                           placeholder="Brief description of the role">
                    @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Permissions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center justify-between">
                <span>Assign Permissions</span>
                <div class="flex gap-2">
                    <button type="button" onclick="selectAll()" class="text-xs bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded">
                        Select All
                    </button>
                    <button type="button" onclick="deselectAll()" class="text-xs bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded">
                        Deselect All
                    </button>
                </div>
            </h3>

            <div class="space-y-6">
                @foreach($permissions as $module => $modulePermissions)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-800 mb-3 capitalize flex items-center gap-2">
                        <i class="fas fa-folder text-blue-500"></i>
                        {{ ucwords(str_replace('-', ' ', $module)) }}
                        <span class="text-xs text-gray-500 font-normal">
                            ({{ count($modulePermissions) }} permissions)
                        </span>
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($modulePermissions as $permission)
                        <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                   class="permission-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">
                                {{ ucwords(str_replace('.', ' ', $permission->name)) }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.roles.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                    <i class="fas fa-save"></i> Create Role
                </button>
            </div>
        </div>
    </form>
</div>
@stop

@section('css')
@vite(['resources/css/app.css'])
@stop

@section('js')
<script>
    function selectAll() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
    }

    function deselectAll() {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
    }
</script>
@stop

