<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disbursement extends Model
{
    protected $fillable = [
        'loan_application_id',
        'disbursed_by',
        'amount',
        'method',
        'disbursement_date',
        'reference',
        'status',
        'mpesa_request_id',
        'mpesa_response_code',
        'mpesa_response_description',
        'mpesa_result_code',
        'mpesa_result_description',
        'mpesa_originator_conversation_id',
        'mpesa_conversation_id',
        'recipient_phone',
        'transaction_receipt',
        'transaction_amount',
        'mpesa_callback_data',
        'retry_count',
        'last_retry_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_amount' => 'decimal:2',
        'disbursement_date' => 'date',
        'mpesa_callback_data' => 'array',
        'retry_count' => 'integer',
        'last_retry_at' => 'datetime',
    ];

    /**
     * Get the loan application for this disbursement
     */
    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class, 'loan_application_id');
    }

    /**
     * Get the user who made this disbursement
     */
    public function disburser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }

    /**
     * Check if disbursement is successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Check if disbursement is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if disbursement failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if M-Pesa transaction was successful
     */
    public function isMpesaSuccessful(): bool
    {
        return $this->mpesa_result_code === '0' || $this->status === 'success';
    }

    /**
     * Mark as successful
     */
    public function markAsSuccessful(): void
    {
        $this->status = 'success';
        $this->save();
    }

    /**
     * Mark as failed
     */
    public function markAsFailed(?string $reason = null): void
    {
        $this->status = 'failed';
        if ($reason) {
            $this->mpesa_result_description = $reason;
        }
        $this->save();
    }

    /**
     * Increment retry count
     */
    public function incrementRetry(): void
    {
        $this->retry_count++;
        $this->last_retry_at = now();
        $this->save();
    }
}

