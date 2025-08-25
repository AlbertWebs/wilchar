<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // $roles = Role::pluck('id', 'name');

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
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    // 'role_id' => $roles[$userData['role']] ?? null,
                ]
            );
        }
    }
}
