<?php

declare(strict_types=1);

namespace App\Data\Payment;

class PaymentRowCSV
{
    public function __construct(
        /**
         * date_format:YdmHis
         */
        public string $payment_date,
        public string $payer_name,
        public string $payer_surname,
        public float $amount,
        public string $description,
        public ?string $national_security_number = null,
        public ?string $payment_reference = null,
    ) {
    }
}
