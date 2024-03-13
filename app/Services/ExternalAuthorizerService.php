<?php

namespace App\Services;

class ExternalAuthorizerService
{

    public function __construct()
    {
    }

    public function checkExternalAuthorizerTranfer()
    {
        $response = curRequest("https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc", 'GET');

        if (empty($response)) {
            abort(500, "Erro ao requisitar!");
        }

        $data = json_decode($response);

        return $data;
    }

    public function checkExternalAuthorizerSendEmail()
    {
        $response = curRequest("https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6", 'GET');

        if (empty($response)) {
            abort(500, "Erro ao requisitar!");
        }

        $data = json_decode($response);

        return $data;
    }
}
