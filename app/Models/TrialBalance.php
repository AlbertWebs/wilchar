<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrialBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_start',
        'period_end',
        'total_debits',
        'total_credits',
        'snapshot',
        'generated_by',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_debits' => 'decimal:2',
        'total_credits' => 'decimal:2',
        'snapshot' => 'array',
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(TrialBalanceEntry::class);
    }

    public function preparer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}

