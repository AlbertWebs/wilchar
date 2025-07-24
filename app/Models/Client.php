<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function loanApplications()
    {
        return $this->hasMany(LoanApplication::class);
    }

}
