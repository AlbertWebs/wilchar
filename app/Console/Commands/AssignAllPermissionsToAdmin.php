<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignAllPermissionsToAdmin extends Command
{
    protected $signature = 'permissions:assign-all-to-admin';
    protected $description = 'Assign all permissions to Admin role';

    public function handle()
    {
        $adminRole = Role::where('name', 'Admin')->first();
        
        if (!$adminRole) {
            $this->error('Admin role not found!');
            return 1;
        }

        $permissions = Permission::all();
        
        if ($permissions->isEmpty()) {
            $this->error('No permissions found! Please run: php artisan db:seed --class=PermissionSeeder');
            return 1;
        }

        $adminRole->syncPermissions($permissions);
        
        $this->info("Successfully assigned {$permissions->count()} permissions to Admin role.");
        $this->info("Admin role now has access to all features including Legal Pages.");
        
        return 0;
    }
}
