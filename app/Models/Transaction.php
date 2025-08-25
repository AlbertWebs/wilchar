<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'account_id', 'loan_id', 'amount', 'type', 'description', 'reference'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
