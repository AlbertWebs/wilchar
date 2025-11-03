<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanApproval extends Model
{
    protected $fillable = [
        'loan_application_id',
        'approved_by',
        'approval_level',
        'previous_level',
        'is_current_level',
        'comment',
        'rejection_reason',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'is_current_level' => 'boolean',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the loan application for this approval
     */
    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class, 'loan_application_id');
    }

    /**
     * Get the user who made this approval
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Check if approval is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if approval is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if approval is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get approval level display name
     */
    public function getLevelDisplayAttribute(): string
    {
        return match($this->approval_level) {
            'loan_officer' => 'Loan Officer',
            'credit_officer' => 'Credit Officer',
            'director' => 'Director',
            default => 'Unknown'
        };
    }
}

