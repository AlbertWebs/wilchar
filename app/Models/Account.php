<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'name',
        'type',
        'balance',
        'description',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function balances()
    {
        return $this->hasMany(AccountBalance::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
