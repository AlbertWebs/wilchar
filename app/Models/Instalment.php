<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Instalment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'due_date',
        'principal_amount',
        'interest_amount',
        'total_amount',
        'amount_paid',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'principal_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function markAsPaid(float $amount): void
    {
        $this->amount_paid = $amount;
        $this->status = 'paid';
        $this->paid_at = now();
        $this->save();
    }
}

