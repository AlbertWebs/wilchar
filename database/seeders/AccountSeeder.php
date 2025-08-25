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
        $account = Account::create([
            'name' => 'Wilchar Bank Account',
            'type' => 'Bank',
            'balance' => 1000000,
            'description' => 'Main operating account'
        ]);

        // Sample transaction
        Transaction::create([
            'account_id' => $account->id,
            'amount' => 50000,
            'type' => 'credit',
            'description' => 'Initial loan fund deposit',
            'reference' => 'INIT123'
        ]);
    }
}
