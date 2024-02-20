<?php

declare(strict_types=1);

namespace App\Services\Payment\Pay\Tasks;

use App\Data\Payment\PayPayload;
use App\Enums\Payment\PaymentOrderState;
use App\Models\PaymentOrder;
use App\Services\Payment\Exceptions\CreatePaymentOrderException;
use App\Services\Payment\Interfaces\PayTask;

class CreatePaymentOrderTask implements PayTask
{
    /**
     * @throws CreatePaymentOrderException
     */
    public function handle(PayPayload $payload, \Closure $next): mixed
    {
        if ($payload->overage <= 0) {
            return $next($payload);
        }

        if (empty($payload->payment)) {
            throw CreatePaymentOrderException::paymentNotDefine();
        }

        $paymentOrder = new PaymentOrder();

        $paymentOrder->amount = $payload->overage;
        $paymentOrder->state = PaymentOrderState::ACTIVE;

        $payload->payment->paymentOrder()->save($paymentOrder);
        $payload->payment->refresh();

        return $next($payload);
    }
}
