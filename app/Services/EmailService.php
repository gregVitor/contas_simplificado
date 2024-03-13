<?php

namespace App\Services;

use App\Mail\CreateAccount as MailCreateAccount;
use App\Mail\ReceivedTransaction as MailReceivedTransaction;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailService extends Mail
{

    public function sendEmailRegisterUser(
        User   $user
    ) {
        if ($this->valideHostEmailOn()) {
            return Mail::to($user->email)->send(new MailCreateAccount($user));
        }
    }

    public function sendEmailReceivedTransaction(
        User   $user,
        float $amount
    ) {
        if ($this->valideHostEmailOn()) {
            return Mail::to($user->email)->send(new MailReceivedTransaction($user, $amount));
        }
    }

    private function valideHostEmailOn()
    {
        if (!env('MAIL_HOST')) {
            return false;
        }

        return true;
    }
}
