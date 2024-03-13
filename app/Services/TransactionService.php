<?php

namespace App\Services;

use App\Enums\ExternalAuthorizerTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Enums\UserTypeEnum;
use App\Repositories\BankAccountRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(
        private readonly TransactionRepository $transactionRepository,
        private readonly BankAccountRepository $bankAccountRepository,
        private readonly ExternalAuthorizerService $externalAuthorizerService,
        private readonly EmailService $emailService
    ) {
    }

    public function create(
        object $user,
        object $request
    ): void {
        if ($user->type == UserTypeEnum::SHOPKEEPER->value) {
            abort(403, 'Essa conta não permite esse tipo de operação');
        }

        $bankAcount = $this->bankAccountRepository->getByUserId($user->id);

        if (!$bankAcount || $bankAcount->balance < $request->amount) {
            abort(403, 'O saldo atual não é suficiente para transferência.');
        }

        $payeeBankAcount = $this->bankAccountRepository->getByFiscalDocument($request->payee);

        if (!$payeeBankAcount) {
            abort(422, 'Conta para transferência não existe.');
        }

        $transactionSent = [
            'bank_account_id' => $bankAcount->id,
            'origin_bank_account_id' => $bankAcount->id,
            'destiny_bank_account_id' => $payeeBankAcount->id,
            'amount' => $request->amount,
            'type' => TransactionTypeEnum::SENT,
            'description' => $request->description
        ];

        $transactionReceived = [
            'bank_account_id' => $payeeBankAcount->id,
            'origin_bank_account_id' => $bankAcount->id,
            'destiny_bank_account_id' => $payeeBankAcount->id,
            'amount' => $request->amount,
            'type' => TransactionTypeEnum::RECEIVED,
            'description' => $request->description
        ];

        DB::transaction(function () use ($bankAcount, $payeeBankAcount, $transactionSent, $transactionReceived, $request) {

            $externalAuthorizer = $this->externalAuthorizerService->checkExternalAuthorizerTranfer();

            if ($externalAuthorizer->message != ExternalAuthorizerTypeEnum::AUTHORIZED->value) {
                abort(422, 'Transferência indisponivel neste momento. Tente mais tarde');
            }

            $bankAcount->balance -=  $request->amount;
            $bankAcount->save();

            $payeeBankAcount->balance +=  $request->amount;
            $payeeBankAcount->save();

            $this->transactionRepository->create($transactionReceived);
            $this->transactionRepository->create($transactionSent);
        });


        $externalAuthorizerNotification = $this->externalAuthorizerService->checkExternalAuthorizerSendEmail();

        if (!$externalAuthorizerNotification->message) {
            abort(202, 'Transferência realizada com sucesso! Porem serviço de notificação esta indisponivel no momento.');
        }

        $this->emailService->sendEmailReceivedTransaction($user, $request->amount);
        return;
    }
}
