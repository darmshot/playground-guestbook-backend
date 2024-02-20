<?php

declare(strict_types=1);

namespace App\Services\Payment\Exceptions;

class CreatePaymentException extends \Exception
{
    public static function loanNotDefine(): self
    {
        return new self('Loan is not defined.');
    }
}
