<?php

namespace App\Repositories;

use App\Models\BankAccount;

class BankAccountRepository
{
    public function __construct(
        private readonly BankAccount $bankAccount
    ) {
    }

    public function create(
        array $bankAccount
    ) {
        return $this->bankAccount->create($bankAccount);
    }

    public function getByUserId(int $userId): ?BankAccount
    {
        return $this->bankAccount
            ->where('user_id', $userId)
            ->first();
    }

    public function getByFiscalDocument(string $fiscalDocument)
    {
        return $this->bankAccount
            ->select('bank_accounts.*')
            ->join('users', 'users.id', 'bank_accounts.user_id')
            ->where('users.fiscal_document', $fiscalDocument)
            ->first();
    }
}
