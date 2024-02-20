<?php

declare(strict_types=1);

namespace App\Services\Payment\Pay;

use App\Data\Payment\PayPayload;
use App\Services\Payment\Pay\Tasks\CloseLoanTask;
use App\Services\Payment\Pay\Tasks\CreatePaymentOrderTask;
use App\Services\Payment\Pay\Tasks\CreatePaymentTask;
use App\Services\Payment\Pay\Tasks\LoadLoanTask;
use App\Services\Payment\Pay\Tasks\NotificationTask;
use Illuminate\Support\Facades\Pipeline;

class PaymentProcess
{
    protected array $tasks = [
        LoadLoanTask::class,
        CreatePaymentTask::class,
        CreatePaymentOrderTask::class,
        CloseLoanTask::class,
        NotificationTask::class,
    ];

    public function pay(PayPayload $payload)
    {
        return Pipeline::send($payload)->through($this->tasks)->thenReturn();
    }

    public static function make(): static
    {
        return new self();
    }
}
