<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class LoanApplication extends Model
{
    protected $fillable = [
        'application_number',
        'client_id',
        'loan_type',
        'amount',
        'amount_approved',
        'interest_rate',
        'duration_months',
        'status',
        'approval_stage',
        'purpose',
        'supporting_documents',
        'created_by',
        'loan_officer_id',
        'credit_officer_id',
        'director_id',
        'background_check_status',
        'background_check_notes',
        'rejection_reason',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_approved' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($loanApplication) {
            if (empty($loanApplication->application_number)) {
                $loanApplication->application_number = 'LA-' . strtoupper(Str::random(10));
            }
        });
    }

    /**
     * Get the client that owns this application
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the user who created this application
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the loan officer assigned to this application
     */
    public function loanOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'loan_officer_id');
    }

    /**
     * Get the credit officer assigned to this application
     */
    public function creditOfficer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'credit_officer_id');
    }

    /**
     * Get the director assigned to this application
     */
    public function director(): BelongsTo
    {
        return $this->belongsTo(User::class, 'director_id');
    }

    /**
     * Get all KYC documents for this application
     */
    public function kycDocuments(): HasMany
    {
        return $this->hasMany(KycDocument::class);
    }

    /**
     * Get all approvals for this application
     */
    public function approvals(): HasMany
    {
        return $this->hasMany(LoanApproval::class, 'loan_application_id');
    }

    /**
     * Get disbursements for this application
     */
    public function disbursements(): HasMany
    {
        return $this->hasMany(Disbursement::class, 'loan_application_id');
    }

    /**
     * Get the loan created from this application (if approved)
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    /**
     * Check if application is at loan officer stage
     */
    public function isAtLoanOfficerStage(): bool
    {
        return $this->approval_stage === 'loan_officer';
    }

    /**
     * Check if application is at credit officer stage
     */
    public function isAtCreditOfficerStage(): bool
    {
        return $this->approval_stage === 'credit_officer';
    }

    /**
     * Check if application is at director stage
     */
    public function isAtDirectorStage(): bool
    {
        return $this->approval_stage === 'director';
    }

    /**
     * Check if background check is passed
     */
    public function backgroundCheckPassed(): bool
    {
        return $this->background_check_status === 'passed';
    }

    /**
     * Check if application is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved' && $this->approval_stage === 'completed';
    }

    /**
     * Check if application is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Move to next approval stage
     */
    public function moveToNextStage(): void
    {
        $stages = ['loan_officer', 'credit_officer', 'director', 'completed'];
        $currentIndex = array_search($this->approval_stage, $stages);
        
        if ($currentIndex !== false && $currentIndex < count($stages) - 1) {
            $this->approval_stage = $stages[$currentIndex + 1];
            $this->save();
        }
    }

    /**
     * Get current approval level display name
     */
    public function getCurrentStageDisplayAttribute(): string
    {
        return match($this->approval_stage) {
            'loan_officer' => 'Loan Officer Review',
            'credit_officer' => 'Credit Officer Review',
            'director' => 'Director Approval',
            'completed' => 'Approval Completed',
            default => 'Unknown'
        };
    }
}

