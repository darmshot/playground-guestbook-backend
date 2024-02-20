<?php
declare(strict_types=1);


namespace App\Services\Payment\Pay\Tasks;

use App\Data\Payment\PayPayload;
use App\Services\Payment\Interfaces\PayTask;

class NotificationTask implements PayTask
{

    public function handle(PayPayload $payload, \Closure $next): mixed
    {
        //

        return $next($payload);
    }
}
