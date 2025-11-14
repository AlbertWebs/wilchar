<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Liability extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'creditor',
        'amount',
        'outstanding_balance',
        'issued_on',
        'due_date',
        'status',
        'notes',
        'team_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'issued_on' => 'date',
        'due_date' => 'date',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}

