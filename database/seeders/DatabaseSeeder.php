<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in correct order
        $this->call([
            UsersSeeder::class,        // Create users and roles first
            SiteSettingSeeder::class,  // Create site settings
            ClientSeeder::class,       // Then create clients
            AccountSeeder::class,      // Create accounts
            LoanSeeder::class,         // Finally create loan applications and loans
        ]);
    }
}
