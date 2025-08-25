<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanApproval extends Model
{
    protected $fillable = [
        'loan_id', 'approved_by', 'level', 'comment', 'status', 'approved_at'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
