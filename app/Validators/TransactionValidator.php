<?php

namespace App\Validators;

class TransactionValidator extends Validator
{
    public function create(array $data)
    {
        $rules = [
            'amount' => 'required|numeric',
            'payee' => 'required|int',
            'payer' => 'required|int',
            'description' => 'string'
        ];

        return $this->validate($data, $rules);
    }
}
