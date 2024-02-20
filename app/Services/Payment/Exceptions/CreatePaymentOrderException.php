<?php
declare(strict_types=1);


namespace App\Services\Payment\Exceptions;

class CreatePaymentOrderException extends \Exception
{
    public static function paymentNotDefine(): self
    {
        return new self('Payment is not defined.');
    }
}
