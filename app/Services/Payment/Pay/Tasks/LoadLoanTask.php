<?php

declare(strict_types=1);

namespace App\Services\Payment\Pay\Tasks;

use App\Data\Payment\PayPayload;
use App\Models\Loan;
use App\Services\Payment\Interfaces\PayTask;

class LoadLoanTask implements PayTask
{
    public function handle(PayPayload $payload, \Closure $next): mixed
    {
        /** @var Loan $loan */
        $loan = Loan::query()->withSum('payments', 'amount')->where('reference', $payload->reference)->first();

        $payload->loan = $loan;

        return $next($payload);
    }
}
