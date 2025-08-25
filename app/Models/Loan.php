<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\LoanApproval;
use App\Models\Disbursement;
use App\Models\Repayment;
use App\Models\Transaction;
use App\Models\PerformanceLog;
use App\Models\Client;

class Loan extends Model
{
    protected $fillable = [
        'client_id', 'loan_type', 'amount_requested', 'amount_approved',
        'term_months', 'interest_rate', 'repayment_frequency', 'status'
    ];
    public function client()
    {
        return $this->belongsTo(Client::class); 
    }
    public function approvals()
    {
        return $this->hasMany(LoanApproval::class);
    }

    public function disbursements()
    {
        return $this->hasMany(Disbursement::class);
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function performanceLogs()
    {
        return $this->hasMany(PerformanceLog::class);
    }

}
