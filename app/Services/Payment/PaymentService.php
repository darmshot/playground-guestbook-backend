<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Contracts\PaymentContract;
use App\Services\Payment\Pay\PaymentProcess;

class PaymentService implements PaymentContract
{
    public function pay(\App\Data\Payment\PayPayload $payload): \App\Data\Payment\PayPayload
    {
        return PaymentProcess::make()->pay($payload);
    }
}
