<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'purchase_date',
        'purchase_price',
        'current_value',
        'depreciation_method',
        'useful_life_months',
        'residual_value',
        'monthly_depreciation',
        'notes',
        'assigned_team_id',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
        'current_value' => 'decimal:2',
        'residual_value' => 'decimal:2',
        'monthly_depreciation' => 'decimal:2',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'assigned_team_id');
    }

    public function calculateDepreciationForDate(Carbon $date): float
    {
        if ($this->depreciation_method === 'none' || !$this->useful_life_months) {
            return 0.0;
        }

        $monthsInUse = $this->purchase_date
            ? max(0, $this->purchase_date->diffInMonths($date))
            : 0;

        return round(min($monthsInUse, $this->useful_life_months) * (float) $this->monthly_depreciation, 2);
    }
}

