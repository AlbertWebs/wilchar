<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Transaction;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create main operating account
        $bankAccount = Account::updateOrCreate(
            ['name' => 'Wilchar Bank Account'],
            [
                'type' => 'bank',
                'balance' => 10000000, // 10 million
                'description' => 'Main operating account'
            ]
        );

        // Create cash account
        $cashAccount = Account::updateOrCreate(
            ['name' => 'Cash Account'],
            [
                'type' => 'cash',
                'balance' => 500000, // 500k
                'description' => 'Petty cash account'
            ]
        );

        // Create M-Pesa account
        $mpesaAccount = Account::updateOrCreate(
            ['name' => 'M-Pesa Account'],
            [
                'type' => 'mobile_money',
                'balance' => 200000, // 200k
                'description' => 'M-Pesa mobile money account'
            ]
        );

        // Create sample initial transactions
        if (!Transaction::where('reference', 'INIT001')->exists()) {
            Transaction::create([
                'account_id' => $bankAccount->id,
                'amount' => 1000000,
                'type' => 'credit',
                'description' => 'Initial capital deposit',
                'reference' => 'INIT001'
            ]);

            $bankAccount->increment('balance', 1000000);
        }

        if (!Transaction::where('reference', 'INIT002')->exists()) {
            Transaction::create([
                'account_id' => $cashAccount->id,
                'amount' => 500000,
                'type' => 'credit',
                'description' => 'Initial cash allocation',
                'reference' => 'INIT002'
            ]);

            $cashAccount->increment('balance', 500000);
        }
    }
}
