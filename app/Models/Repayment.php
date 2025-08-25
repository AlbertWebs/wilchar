<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    protected $fillable = [
        'loan_id', 'amount', 'payment_method', 'paid_at', 'reference', 'received_by', 'receipt_url'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
