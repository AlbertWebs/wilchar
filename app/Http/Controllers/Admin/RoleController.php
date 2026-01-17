<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index()
    {
        $roles = Role::withCount('permissions', 'users')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $permissions = $this->getPermissionsGrouped();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $roleData = [
            'name' => $validated['name'], // Preserve role name as entered (not slug)
            'guard_name' => 'web',
        ];

        if (isset($validated['description'])) {
            $roleData['description'] = $validated['description'];
        }

        $role = Role::create($roleData);

        // Assign permissions
        if (isset($validated['permissions']) && is_array($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully!');
    }

    /**
     * Show the form for editing a role
     */
    public function edit(Role $role)
    {
        $permissions = $this->getPermissionsGrouped();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update a role
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $roleData = [
            'name' => $validated['name'], // Preserve role name as entered (not slug)
        ];

        if (isset($validated['description'])) {
            $roleData['description'] = $validated['description'];
        }

        $role->update($roleData);

        // Sync permissions
        if (isset($validated['permissions']) && is_array($validated['permissions']) && count($validated['permissions']) > 0) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->syncPermissions($permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully!');
    }

    /**
     * Remove a role
     */
    public function destroy(Role $role)
    {
        // Prevent deletion of Admin role
        if ($role->name === 'Admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete the Admin role!');
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete role that has assigned users!');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully!');
    }

    /**
     * Get permissions grouped by module
     */
    private function getPermissionsGrouped()
    {
        $permissions = Permission::orderBy('name')->get();
        
        $grouped = [];
        foreach ($permissions as $permission) {
            // Extract module from permission name (e.g., 'clients.view' -> 'clients')
            $parts = explode('.', $permission->name);
            $module = $parts[0] ?? 'other';
            
            if (!isset($grouped[$module])) {
                $grouped[$module] = [];
            }
            
            $grouped[$module][] = $permission;
        }
        
        // Sort modules
        ksort($grouped);
        
        return $grouped;
    }
}