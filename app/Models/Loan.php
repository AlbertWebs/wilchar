<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'loan_application_id',
        'loan_product_id',
        'team_id',
        'loan_type',
        'amount_requested',
        'amount_approved',
        'interest_amount',
        'total_amount',
        'outstanding_balance',
        'term_months',
        'interest_rate',
        'repayment_frequency',
        'status',
        'collection_officer_id',
        'recovery_officer_id',
        'finance_officer_id',
        'processing_fee',
        'late_fee_accrued',
        'next_due_date',
    ];

    protected $casts = [
        'amount_requested' => 'decimal:2',
        'amount_approved' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'processing_fee' => 'decimal:2',
        'late_fee_accrued' => 'decimal:2',
        'next_due_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class, 'loan_application_id');
    }

    public function loanProduct(): BelongsTo
    {
        return $this->belongsTo(LoanProduct::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function collectionOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collection_officer_id');
    }

    public function recoveryOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recovery_officer_id');
    }

    public function financeOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finance_officer_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(LoanApproval::class);
    }

    public function disbursements(): HasMany
    {
        return $this->hasMany(Disbursement::class, 'loan_application_id', 'loan_application_id');
    }

    public function repayments(): HasMany
    {
        return $this->hasMany(Repayment::class);
    }

    public function instalments(): HasMany
    {
        return $this->hasMany(Instalment::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function performanceLogs(): HasMany
    {
        return $this->hasMany(PerformanceLog::class);
    }
}
