<?php

namespace App\Enums;

enum TransactionTypeEnum: string
{
    case RECEIVED = 'received';
    case SENT = 'sent';
}