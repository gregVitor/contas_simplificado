<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'bank_account_id', 'amount', 'type', 'description'
    ];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
}
