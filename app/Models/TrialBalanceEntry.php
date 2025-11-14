<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrialBalanceEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'trial_balance_id',
        'account_id',
        'account_name',
        'debit',
        'credit',
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
    ];

    public function trialBalance(): BelongsTo
    {
        return $this->belongsTo(TrialBalance::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}

