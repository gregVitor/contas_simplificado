<?php

namespace App\Validators\Rules;
use Illuminate\Contracts\Validation\Rule;

class FiscalDocumentLength implements Rule
{
    public function passes($attribute, $value)
    {
        $length = strlen($value);
        return ($length == 11 || $length == 14);
    }

    public function message()
    {
        return 'O campo :attribute deve ter 11 ou 14 dígitos.';
    }
}
