<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShareholderContribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'shareholder_id',
        'contribution_date',
        'amount',
        'reference',
        'description',
    ];

    protected $casts = [
        'contribution_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function shareholder(): BelongsTo
    {
        return $this->belongsTo(Shareholder::class);
    }
}

