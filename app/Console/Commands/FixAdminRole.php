<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class FixAdminRole extends Command
{
    protected $signature = 'fix:admin-role {email?}';
    protected $description = 'Fix Admin role assignment for user';

    public function handle()
    {
        $email = $this->argument('email') ?? 'admin@nurusmesolution.co.ke';
        
        // Fix role names to match middleware expectations
        $roleMappings = [
            'admin' => 'Admin',
            'loan officer' => 'Loan Officer',
            'credit officer' => 'Credit Officer',
            'director' => 'Director',
            'accountant' => 'Accountant',
            'collections' => 'Collections',
        ];

        foreach ($roleMappings as $oldName => $newName) {
            $role = Role::where('name', $oldName)->first();
            if ($role && $role->name !== $newName) {
                $this->info("Updating role: {$oldName} -> {$newName}");
                $role->name = $newName;
                $role->save();
            }
        }

        // Get or create Admin role
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $this->info("Admin role exists: " . $adminRole->name);

        // Assign Admin role to user
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        if (!$user->hasRole('Admin')) {
            $user->assignRole($adminRole);
            $this->info("Assigned Admin role to {$user->email}");
        } else {
            $this->info("User {$user->email} already has Admin role");
        }

        $this->info("Done! User roles: " . $user->roles->pluck('name')->implode(', '));
        return 0;
    }
}

