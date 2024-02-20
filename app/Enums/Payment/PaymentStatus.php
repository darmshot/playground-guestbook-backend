<?php

namespace App\Enums\Payment;

enum PaymentStatus:string
{
case ASSIGNED = 'ASSIGNED';
case PARTIALLY_ASSIGNED = 'PARTIALLY_ASSIGNED';
}
