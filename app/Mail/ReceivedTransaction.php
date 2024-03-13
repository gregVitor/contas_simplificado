<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceivedTransaction extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $user;
    private $amount;


    public function __construct(
        $user,
        $amount
    ) {
        $this->user = $user;
        $this->amount = $amount;
    }

    public function build()
    {
        $objEmail = [
            'name'          => $this->user->name,
            'email'     => $this->user->email,
            'amount' =>  $this->amount
        ];

        $address = env('MAIL_FROM_DEFAULT');
        $name    = env('APP_NAME');
        $subject = 'Transferência recebida!';

        return $this->view('email.received-transaction')
            ->from($address, $name)
            ->subject($subject)
            ->with($objEmail);
    }
}
