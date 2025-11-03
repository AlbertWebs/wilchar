<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Create roles if they don't exist
        $roles = [
            'Admin',
            'Loan Officer',
            'Credit Officer',
            'Director',
            'Accountant',
            'Collections',
        ];

        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            // Ensure role name is exactly as specified (case-sensitive)
            if ($role->name !== $roleName) {
                $role->name = $roleName;
                $role->save();
            }
        }

        // Define users with their roles
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@nurusmesolution.co.ke',
                'password' => Hash::make('password'),
                'role' => 'Admin',
            ],
            [
                'name' => 'Loan Officer',
                'email' => 'officer@nurusmesolution.co.ke',
                'password' => Hash::make('password'),
                'role' => 'Loan Officer',
            ],
            [
                'name' => 'Credit Officer',
                'email' => 'credit@nurusmesolution.co.ke',
                'password' => Hash::make('password'),
                'role' => 'Credit Officer',
            ],
            [
                'name' => 'Director',
                'email' => 'director@nurusmesolution.co.ke',
                'password' => Hash::make('password'),
                'role' => 'Director',
            ],
            [
                'name' => 'Accountant',
                'email' => 'accountant@nurusmesolution.co.ke',
                'password' => Hash::make('password'),
                'role' => 'Accountant',
            ],
            [
                'name' => 'Collections Agent',
                'email' => 'collections@nurusmesolution.co.ke',
                'password' => Hash::make('password'),
                'role' => 'Collections',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                ]
            );

            // Assign role using Spatie Permission
            $role = Role::where('name', $userData['role'])->first();
            if ($role && !$user->hasRole($userData['role'])) {
                $user->assignRole($role);
            }
        }
    }
}
