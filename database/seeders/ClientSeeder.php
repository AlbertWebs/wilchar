<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\User;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loanOfficer = User::whereHas('roles', function($q) {
            $q->where('name', 'Loan Officer');
        })->first();

        $admin = User::whereHas('roles', function($q) {
            $q->where('name', 'Admin');
        })->first();

        $createdBy = $loanOfficer ?? $admin ?? User::first();

        $clients = [
            [
                'first_name' => 'John',
                'last_name' => 'Mwangi',
                'middle_name' => 'Kamau',
                'id_number' => '3000123456789',
                'phone' => '254712345678',
                'mpesa_phone' => '254712345678',
                'email' => 'john.mwangi@example.com',
                'address' => '123 Main Street, Nairobi',
                'date_of_birth' => '1985-05-15',
                'gender' => 'Male',
                'nationality' => 'Kenyan',
                'business_name' => 'Mwangi Enterprises',
                'business_type' => 'Retail',
                'location' => 'Nairobi CBD',
                'occupation' => 'Business Owner',
                'status' => 'active',
                'kyc_completed' => true,
                'kyc_completed_at' => now(),
                'credit_score' => 750,
                'created_by' => $createdBy->name ?? 'System',
                'created_by_user_id' => $createdBy->id ?? null,
            ],
            [
                'first_name' => 'Mary',
                'last_name' => 'Wanjiru',
                'middle_name' => 'Njeri',
                'id_number' => '3000234567890',
                'phone' => '254723456789',
                'mpesa_phone' => '254723456789',
                'email' => 'mary.wanjiru@example.com',
                'address' => '456 Market Street, Kisumu',
                'date_of_birth' => '1990-08-22',
                'gender' => 'Female',
                'nationality' => 'Kenyan',
                'business_name' => 'Wanjiru Fashion',
                'business_type' => 'Fashion Retail',
                'location' => 'Kisumu',
                'occupation' => 'Entrepreneur',
                'status' => 'active',
                'kyc_completed' => true,
                'kyc_completed_at' => now(),
                'credit_score' => 680,
                'created_by' => $createdBy->name ?? 'System',
                'created_by_user_id' => $createdBy->id ?? null,
            ],
            [
                'first_name' => 'Peter',
                'last_name' => 'Ochieng',
                'middle_name' => 'Otieno',
                'id_number' => '3000345678901',
                'phone' => '254734567890',
                'mpesa_phone' => '254734567890',
                'email' => 'peter.ochieng@example.com',
                'address' => '789 Business Park, Mombasa',
                'date_of_birth' => '1988-03-10',
                'gender' => 'Male',
                'nationality' => 'Kenyan',
                'business_name' => 'Ochieng Trading',
                'business_type' => 'Wholesale',
                'location' => 'Mombasa',
                'occupation' => 'Trader',
                'status' => 'active',
                'kyc_completed' => false,
                'credit_score' => 720,
                'created_by' => $createdBy->name ?? 'System',
                'created_by_user_id' => $createdBy->id ?? null,
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Akinyi',
                'middle_name' => 'Achieng',
                'id_number' => '3000456789012',
                'phone' => '254745678901',
                'mpesa_phone' => '254745678901',
                'email' => 'jane.akinyi@example.com',
                'address' => '321 Industrial Area, Nakuru',
                'date_of_birth' => '1992-11-05',
                'gender' => 'Female',
                'nationality' => 'Kenyan',
                'business_name' => 'Akinyi Foods',
                'business_type' => 'Food & Beverage',
                'location' => 'Nakuru',
                'occupation' => 'Restaurant Owner',
                'status' => 'active',
                'kyc_completed' => true,
                'kyc_completed_at' => now(),
                'credit_score' => 690,
                'created_by' => $createdBy->name ?? 'System',
                'created_by_user_id' => $createdBy->id ?? null,
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Kipchoge',
                'middle_name' => 'Kiptoo',
                'id_number' => '3000567890123',
                'phone' => '254756789012',
                'mpesa_phone' => '254756789012',
                'email' => 'david.kipchoge@example.com',
                'address' => '654 Farm Road, Eldoret',
                'date_of_birth' => '1987-07-18',
                'gender' => 'Male',
                'nationality' => 'Kenyan',
                'business_name' => 'Kipchoge Farm Supplies',
                'business_type' => 'Agriculture',
                'location' => 'Eldoret',
                'occupation' => 'Farmer',
                'status' => 'active',
                'kyc_completed' => true,
                'kyc_completed_at' => now(),
                'credit_score' => 710,
                'created_by' => $createdBy->name ?? 'System',
                'created_by_user_id' => $createdBy->id ?? null,
            ],
        ];

        foreach ($clients as $clientData) {
            Client::updateOrCreate(
                ['id_number' => $clientData['id_number']],
                $clientData
            );
        }
    }
}
