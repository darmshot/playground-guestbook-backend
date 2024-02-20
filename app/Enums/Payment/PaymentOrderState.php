<?php

namespace App\Enums\Payment;

enum PaymentOrderState: string
{
    case ACTIVE = 'ACTIVE';
    case PAID = 'PAID';
}
