<?php
declare(strict_types=1);


namespace App\Services\Payment\Pay\Tasks;

use App\Data\Payment\PayPayload;
use App\Enums\Payment\LoanState;
use App\Enums\Payment\PaymentStatus;
use App\Models\Loan;
use App\Models\Payment;
use App\Services\Payment\Exceptions\CloseLoanException;
use App\Services\Payment\Interfaces\PayTask;

class CloseLoanTask implements PayTask
{

    public function handle(PayPayload $payload, \Closure $next): mixed
    {
        if (empty($payload->loan)) {
            CloseLoanException::loanNotDefine();
        }


        if ($payload->loan->remains > 0) {
            return $next($payload);
        }


        $payload->loan->state = LoanState::PAID;
        $payload->loan->save();

        return $next($payload);
    }
}
