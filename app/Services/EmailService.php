<?php

namespace App\Services;

use App\Mail\CreateAccount as MailCreateAccount;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailService extends Mail
{

    /**
     * Send email deposit
     *
     * @param User $user
     * @param object $deposit
     * @return void
     */
    public function sendEmailRegisterUser(
        User   $user
    ) {
        return Mail::to($user->email)->send(new MailCreateAccount($user));
    }
}
