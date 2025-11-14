<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'created_by',
    ];

    protected static function booted(): void
    {
        static::creating(function (Team $team): void {
            if (empty($team->slug)) {
                $team->slug = Str::slug($team->name) . '-' . Str::random(4);
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function loanOfficers(): BelongsToMany
    {
        return $this->members()->wherePivot('role', 'loan_officer');
    }

    public function collectionOfficers(): BelongsToMany
    {
        return $this->members()->wherePivot('role', 'collection_officer');
    }

    public function financeMembers(): BelongsToMany
    {
        return $this->members()->wherePivot('role', 'finance');
    }

    public function loanApplications(): HasMany
    {
        return $this->hasMany(LoanApplication::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}

