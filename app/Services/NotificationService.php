<?php

namespace App\Services;

use App\Models\Email;

class NotificationService
{
    public function triggerWarning(Email $email){
        $this->sendToSMS($email);
        $this->sendToAppDesktop($email);
    }

    private function sendToSMS(Email $email){
        //envia notioficacao via SMS
    }

    private function sendToAppDesktop(Email $email){
        //envia notioficacao via desktop
    }
}