<?php
declare(strict_types=1);


namespace App\Services\Payment\Pay\Tasks;

use App\Data\Payment\PayPayload;
use App\Enums\Payment\LoanState;
use App\Enums\Payment\PaymentStatus;
use App\Models\Loan;
use App\Models\Payment;
use App\Services\Payment\Exceptions\CreatePaymentException;
use App\Services\Payment\Interfaces\PayTask;

class CreatePaymentTask implements PayTask
{

    /**
     * @throws CreatePaymentException
     */
    public function handle(PayPayload $payload, \Closure $next): mixed
    {
        if (empty($payload->loan)) {
            throw CreatePaymentException::loanNotDefine();
        }


        $payment = new Payment();
        $payment->payment_date = $payload->payment_date;
        $payment->payer_name = $payload->firstname;
        $payment->payer_surname = $payload->lastname;
        $payment->amount = $payload->amount;
        $payment->national_security_number = $payload->national_security_number;
        $payment->description = $payload->description;
        $payment->payment_reference = $payload?->payment_reference;
        $payment->ref_id = $payload?->ref_id;

        if ($payload->amount === $payload->loan->remains) {
            $payload->loan->state = LoanState::PAID;
            $payment->status = PaymentStatus::ASSIGNED;
        } elseif ($payload->amount > $payload->loan->remains) {
            $payload->loan->state = LoanState::PAID;
            $payment->status = PaymentStatus::PARTIALLY_ASSIGNED;
            // Write Overage
            $payload->overage = $payload->amount - $payload->loan->remains;
        } else {
            $payment->status = PaymentStatus::ASSIGNED;
        }


        $payload->loan->payments()->save($payment);
        $payload->loan->refresh();

        $payload->payment = $payment;

        return $next($payload);
    }
}
