<?php
declare(strict_types=1);


namespace App\Data\Payment;

class PaymentForm
{
    public function __construct(
        public string  $firstname,
        public string  $lastname,
        public string  $payment_date,
        public float  $amount,
        public string  $description,
        public string $ref_id,
    )
    {
    }


}
