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
        return Mail::to($user->email)->send(new MailCreateAccount($user));
    }

    public function sendEmailReceivedTransaction(
        User   $user,
        float $amount
    ) {
        return Mail::to($user->email)->send(new MailReceivedTransaction($user, $amount));
    }
}
