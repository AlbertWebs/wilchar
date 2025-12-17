<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'two_factor_code',
        'two_factor_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_expires_at' => 'datetime',
        ];
    }

    /**
     * Note: hasRole() and roles() are provided by Spatie Permission's HasRoles trait
     * No need for custom implementation
     */

    public function approvals()
    {
        return $this->hasMany(LoanApproval::class, 'approved_by');
    }

    public function disbursements()
    {
        return $this->hasMany(Disbursement::class, 'disbursed_by');
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class, 'received_by');
    }

    public function performanceLogs()
    {
        return $this->hasMany(PerformanceLog::class, 'logged_by');
    }
}
