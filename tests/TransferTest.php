<?php

use App\Models\BankAccount;

class TransferTest extends TestCase
{
    public function testTransferSuccess()
    {
        $tokenResponse = $this->generateToken();

        $bankAccount = BankAccount::query()
            ->select('bank_accounts.*')
            ->join('users', 'users.id', 'bank_accounts.user_id')
            ->where('users.email', $tokenResponse->email)
            ->first();

        $bankAccount->balance = 1000;
        $bankAccount->save();

        $user = $this->createUser();

        $response = $this->post(
            'api/v1/transaction',
            [
                "amount" => 100.0,
                "payee" => $user->fiscal_document,
                "description" => "teste"

            ],
            [
                'Authorization' => 'Bearer ' . $tokenResponse->token
            ]
        );

        $response->assertResponseStatus(200);
    }

    public function testTransferFailedWithoutBalance()
    {
        $tokenResponse = $this->generateToken();

        $bankAccount = BankAccount::query()
            ->select('bank_accounts.*')
            ->join('users', 'users.id', 'bank_accounts.user_id')
            ->where('users.email', $tokenResponse->email)
            ->first();

        $user = $this->createUser();

        $response = $this->post(
            'api/v1/transaction',
            [
                "amount" => 100.0,
                "payee" => $user->fiscal_document,
                "description" => "teste"

            ],
            [
                'Authorization' => 'Bearer ' . $tokenResponse->token
            ]
        );

        $response->assertResponseStatus(403);
        $jsonReponse = json_decode($this->response->getContent());
        $this->assertEquals($jsonReponse->message, 'O saldo atual não é suficiente para transferência.');
    }

    public function testTransferFailedDoesNotExistAccount()
    {
        $tokenResponse = $this->generateToken();

        $bankAccount = BankAccount::query()
            ->select('bank_accounts.*')
            ->join('users', 'users.id', 'bank_accounts.user_id')
            ->where('users.email', $tokenResponse->email)
            ->first();

        $bankAccount->balance = 1000;
        $bankAccount->save();

        $response = $this->post(
            'api/v1/transaction',
            [
                "amount" => 100.0,
                "payee" => $this->generateRandomNumber(11),
                "description" => "teste"

            ],
            [
                'Authorization' => 'Bearer ' . $tokenResponse->token
            ]
        );

        $response->assertResponseStatus(422);
        $jsonReponse = json_decode($this->response->getContent());
        $this->assertEquals($jsonReponse->message, 'Conta para transferência não existe.');
    }

    public function testTransferFailedAccountSk()
    {
        $tokenResponse = $this->generateTokenShopkeeper();

        $bankAccount = BankAccount::query()
            ->select('bank_accounts.*')
            ->join('users', 'users.id', 'bank_accounts.user_id')
            ->where('users.email', $tokenResponse->email)
            ->first();

        $bankAccount->balance = 1000;
        $bankAccount->save();

        $response = $this->post(
            'api/v1/transaction',
            [
                "amount" => 100.0,
                "payee" => $this->generateRandomNumber(11),
                "description" => "teste"

            ],
            [
                'Authorization' => 'Bearer ' . $tokenResponse->token
            ]
        );

        $response->assertResponseStatus(403);
        $jsonReponse = json_decode($this->response->getContent());
        $this->assertEquals($jsonReponse->message, 'Essa conta não permite esse tipo de operação');
    }
}
