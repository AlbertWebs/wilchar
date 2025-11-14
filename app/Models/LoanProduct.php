<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class LoanProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'min_amount',
        'max_amount',
        'min_duration_months',
        'max_duration_months',
        'base_interest_rate',
        'interest_rate_per_month',
        'processing_fee_rate',
        'late_fee_rate',
        'is_active',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'base_interest_rate' => 'decimal:2',
        'interest_rate_per_month' => 'decimal:2',
        'processing_fee_rate' => 'decimal:2',
        'late_fee_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (LoanProduct $product): void {
            if (empty($product->code)) {
                $product->code = Str::upper(Str::slug($product->name, '_')) . '_' . Str::random(4);
            }
        });
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function calculateInterest(int $months, float $amount): float
    {
        $baseRate = (float) $this->base_interest_rate;
        $monthlyRate = (float) $this->interest_rate_per_month;
        $effectiveRate = $baseRate + ($monthlyRate * max($months - 1, 0));

        return round(($effectiveRate / 100) * $amount, 2);
    }

    public function calculateProcessingFee(float $amount): float
    {
        return round(($this->processing_fee_rate / 100) * $amount, 2);
    }
}

