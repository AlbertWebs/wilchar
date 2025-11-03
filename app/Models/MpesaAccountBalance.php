<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MpesaAccountBalance extends Model
{
    protected $fillable = [
        'initiator_name',
        'command_id',
        'party_a',
        'identifier_type',
        'queue_timeout_url',
        'result_url',
        'originator_conversation_id',
        'conversation_id',
        'result_code',
        'result_desc',
        'working_account_balance',
        'utility_account_balance',
        'charges_paid_account_balance',
        'account_balance_time',
        'status',
        'response_data',
        'requested_by',
    ];

    protected $casts = [
        'working_account_balance' => 'decimal:2',
        'utility_account_balance' => 'decimal:2',
        'charges_paid_account_balance' => 'decimal:2',
        'response_data' => 'array',
        'result_code' => 'integer',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }
}
