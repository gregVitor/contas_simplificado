<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    public function __construct(
        private readonly Transaction $transaction
    ) {
    }

    public function create(
        array $transaction
    ) {
        return $this->transaction->create($transaction);
    }
}
