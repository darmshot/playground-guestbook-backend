<?php

namespace App\Services\Payment\Interfaces;

use App\Data\Payment\PayPayload;

interface PayTask
{
    public function handle(PayPayload $payload, \Closure $next): mixed;
}
