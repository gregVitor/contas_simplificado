<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreateAccount extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $user;
    private $deposit;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        $user
    ) {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $objEmail = [
            'name'          => $this->user->name,
            'email'     => $this->user->email
        ];

        $address = env('MAIL_FROM_DEFAULT');
        $name    = env('APP_NAME');
        $subject = 'Bem vindo a sua agenda';

        return $this->view('email.create-account')
            ->from($address, $name)
            ->subject($subject)
            ->with($objEmail);
    }
}
