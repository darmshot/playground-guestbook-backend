<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Data\Payment\PayPayload;

interface PaymentContract
{
    public function pay(PayPayload $payload): PayPayload;
}
