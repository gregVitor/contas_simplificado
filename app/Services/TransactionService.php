<?php

namespace App\Services;

use App\Enums\TransactionTypeEnum;
use App\Models\BankAccount;
use App\Repositories\BankAccountRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository,
        private readonly BankAccountRepository $bankAccountRepository
    ) {
    }

    public function create(
        object $user,
        object $request
    ) {
        $bankAcount = $this->bankAccountRepository->getByUserId($user->id);

        if (!$bankAcount || $bankAcount->balance < $request->amount) {
            abort(403, 'O saldo atual não é suficiente para transferência.');
        }

        $payee = $this->bankAccountRepository->getByFiscalDocument($request->payee);

        if (!$payee) {
            abort(422, 'Conta para transferência nao existe.');
        }

        $transactionSent = [
            'bank_account_id' => $bankAcount->id,
            'origin_bank_account_id' => $bankAcount->id,
            'destiny_bank_account_id' => $payee->id,
            'amount' => $request->amount,
            'type' => TransactionTypeEnum::SENT,
            'description' => $request->description
        ];

        $transactionReceived = [
            'bank_account_id' => $payee->id,
            'origin_bank_account_id' => $bankAcount->id,
            'destiny_bank_account_id' => $payee->id,
            'amount' => $request->amount,
            'type' => TransactionTypeEnum::RECEIVED,
            'description' => $request->description
        ];


        DB::transaction(function () use ($bankAcount, $transactionSent, $transactionReceived, $request) {
            $bankAcount->balance -=  $request->amount;
            $bankAcount->save();

            $this->transactionRepository->create($transactionReceived);
            return $this->transactionRepository->create($transactionSent);
        });
    }
}
