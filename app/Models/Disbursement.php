<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    protected $fillable = [
        'loan_id', 'disbursed_by', 'amount', 'method', 'disbursement_date', 'reference', 'status'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function disburser()
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }
}
