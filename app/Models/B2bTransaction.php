<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class B2bTransaction extends Model
{
    protected $fillable = [
        'initiator_name',
        'command_id',
        'amount',
        'party_a',
        'party_b',
        'account_reference',
        'remarks',
        'queue_timeout_url',
        'result_url',
        'originator_conversation_id',
        'conversation_id',
        'transaction_receipt',
        'result_code',
        'result_desc',
        'transaction_amount',
        'transaction_date',
        'status',
        'callback_data',
        'initiated_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_amount' => 'decimal:2',
        'callback_data' => 'array',
        'result_code' => 'integer',
    ];

    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
