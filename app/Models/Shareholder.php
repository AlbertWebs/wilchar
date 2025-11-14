<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shareholder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'shares_owned',
        'notes',
    ];

    protected $casts = [
        'shares_owned' => 'decimal:2',
    ];

    public function contributions(): HasMany
    {
        return $this->hasMany(ShareholderContribution::class);
    }

    public function getTotalContributionsAttribute(): float
    {
        return (float) $this->contributions()->sum('amount');
    }
}

