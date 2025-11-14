@php
    $selectedPermissions = collect(old('permissions', $rolePermissions ?? []))->map(fn($value) => (int) $value)->all();
@endphp

<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2">
        <div>
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Role Name</label>
            <input
                type="text"
                name="name"
                value="{{ old('name', $role->name ?? '') }}"
                class="mt-1 w-full rounded-xl border-slate-200"
                placeholder="e.g. Loan Officer"
                required
            >
            <p class="mt-1 text-xs text-slate-400">Use descriptive names. They will be slugged automatically.</p>
        </div>
        <div>
            <label class="text-xs font-semibold uppercase tracking-wide text-slate-500">Description (optional)</label>
            <input
                type="text"
                name="description"
                value="{{ old('description', $role->description ?? '') }}"
                class="mt-1 w-full rounded-xl border-slate-200"
                placeholder="Short summary of this role"
            >
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white">
        <div class="border-b border-slate-100 px-5 py-4">
            <p class="text-sm font-semibold text-slate-900">Permissions Matrix</p>
            <p class="text-xs text-slate-500">Check the operations this role should be able to perform.</p>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($permissions as $module => $modulePermissions)
                <div class="px-5 py-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-slate-800">{{ \Illuminate\Support\Str::headline($module) }}</p>
                        <span class="text-xs uppercase tracking-wide text-slate-400">{{ count($modulePermissions) }} permission(s)</span>
                    </div>
                    <div class="mt-3 grid gap-2 md:grid-cols-2">
                        @foreach($modulePermissions as $permission)
                            <label class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50/50 px-3 py-2 text-sm text-slate-700">
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->id }}"
                                    class="mt-1 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                    @checked(in_array($permission->id, $selectedPermissions, true))
                                >
                                <div>
                                    <span class="font-semibold">{{ \Illuminate\Support\Str::headline($permission->name) }}</span>
                                    @if($permission->description ?? false)
                                        <p class="text-xs text-slate-500">{{ $permission->description }}</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="px-5 py-4 text-sm text-slate-500">
                    No permissions configured yet. Seed permissions before creating roles.
                </div>
            @endforelse
        </div>
    </div>
</div>

