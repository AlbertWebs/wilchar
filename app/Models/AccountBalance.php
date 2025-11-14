<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'balance_date',
        'opening_balance',
        'closing_balance',
        'credits',
        'debits',
    ];

    protected $casts = [
        'balance_date' => 'date',
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'credits' => 'decimal:2',
        'debits' => 'decimal:2',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}

