<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Client;
use App\Models\Role;
use App\Models\User;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $officer = User::where('role_id', Role::where('name', 'Loan Officer')->first()->id)->first();

        for ($i = 1; $i <= 5; $i++) {
            Client::create([
                'first_name' => 'Client ' . $i,
                'id_number' => '3000' . $i,
                'phone' => '07123' . rand(10000, 99999),
                'email' => 'client' . $i . '@example.com',
                'address' => 'Nairobi',
                'business_name' => 'Biz ' . $i,
                'business_type' => 'Retail',
                'location' => 'Town ' . $i, 
                'created_by' => 1,
                'status' => 'active',
            ]);
        }
    }
}
