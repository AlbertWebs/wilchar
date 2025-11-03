<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MpesaTransactionStatus extends Model
{
    protected $fillable = [
        'initiator_name',
        'command_id',
        'transaction_id',
        'party_a',
        'identifier_type',
        'queue_timeout_url',
        'result_url',
        'remarks',
        'occasion',
        'originator_conversation_id',
        'conversation_id',
        'result_code',
        'result_desc',
        'transaction_amount',
        'transaction_date',
        'transaction_type',
        'receipt_number',
        'status',
        'response_data',
        'requested_by',
    ];

    protected $casts = [
        'transaction_amount' => 'decimal:2',
        'response_data' => 'array',
        'result_code' => 'integer',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function isFound(): bool
    {
        return $this->status === 'found';
    }

    public function isNotFound(): bool
    {
        return $this->status === 'not_found';
    }
}
