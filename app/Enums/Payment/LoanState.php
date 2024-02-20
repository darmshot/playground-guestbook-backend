<?php

namespace App\Enums\Payment;

enum LoanState: string
{
    case ACTIVE = 'ACTIVE';
    case PAID = 'PAID';
}
