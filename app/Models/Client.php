<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Client extends Model
{
    protected $fillable = [
        'client_code',
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'gender',
        'nationality',
        'id_number',
        'photo',
        'business_name',
        'business_type',
        'location',
        'created_by',
        'created_by_user_id',
        'phone',
        'alternate_phone',
        'mpesa_phone',
        'email',
        'address',
        'occupation',
        'employer',
        'status',
        'kyc_completed',
        'kyc_completed_at',
        'credit_score',
        'credit_score_updated_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'kyc_completed' => 'boolean',
        'kyc_completed_at' => 'datetime',
        'credit_score' => 'integer',
        'credit_score_updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            if (empty($client->client_code)) {
                $client->client_code = 'CL-' . strtoupper(Str::random(8));
            }
        });
    }

    /**
     * Get all loans for this client
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get all loan applications for this client
     */
    public function loanApplications(): HasMany
    {
        return $this->hasMany(LoanApplication::class);
    }

    /**
     * Get the user who created this client
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute(): string
    {
        $parts = array_filter([$this->first_name, $this->middle_name, $this->last_name]);
        return implode(' ', $parts);
    }

    /**
     * Check if client is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if client is blacklisted
     */
    public function isBlacklisted(): bool
    {
        return $this->status === 'blacklisted';
    }

    /**
     * Mark KYC as completed
     */
    public function markKycCompleted(): void
    {
        $this->kyc_completed = true;
        $this->kyc_completed_at = now();
        $this->save();
    }
}

