<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceLog extends Model
{
    protected $fillable = [
        'loan_id', 'metric_type', 'value', 'logged_by', 'notes'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function logger()
    {
        return $this->belongsTo(User::class, 'logged_by');
    }
}
