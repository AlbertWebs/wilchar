<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalEmailLog extends Model
{
    protected $fillable = [
        'loan_application_id',
        'sent_by',
        'sent_count',
        'total_recipients',
        'recipients',
        'errors',
        'sent_at',
    ];

    protected $casts = [
        'recipients' => 'array',
        'errors' => 'array',
        'sent_at' => 'datetime',
    ];

    public function loanApplication(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}

