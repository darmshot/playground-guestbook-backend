<?php
declare(strict_types=1);


namespace App\Data\Payment;

use App\Models\Loan;
use App\Models\Payment;
use App\Services\Payment\Helpers\ReferenceExtractor;
use Illuminate\Support\Carbon;

class PayPayload
{
    public function __construct(
        public string   $firstname,
        public string   $lastname,
        /**
         * Example: LN22345678
         * @var string
         */
        public string   $reference,
        public float    $amount,
        public Carbon   $payment_date,
        public ?string  $ref_id = null,
        public ?string  $payment_reference = null,
        /**
         * Example: 1234567890
         * @var string|null
         */
        public ?string  $national_security_number = null,
        public ?Payment $payment = null,
        public ?Loan    $loan = null,
        public ?string  $description = null,
        public float    $overage = 0
    )
    {
    }

    public static function fromPaymentForm(PaymentForm $paymentForm): static
    {
        $reference = ReferenceExtractor::make()->from($paymentForm->description)->get();

        $paymentDate = Carbon::createFromTimeString($paymentForm->payment_date);

        return new self(
            firstname: $paymentForm->firstname,
            lastname: $paymentForm->lastname,
            reference: $reference,
            amount: $paymentForm->amount,
            payment_date: $paymentDate,
            ref_id: $paymentForm->ref_id,
            description: $paymentForm->description
        );
    }


    public static function fromPaymentRowCSV(PaymentRowCSV $paymentRowCSV): static
    {
        $reference = ReferenceExtractor::make()->from($paymentRowCSV->description)->get();

        $paymentDate = Carbon::createFromFormat('YdmHis',$paymentRowCSV->payment_date);

        return new self(
            firstname: $paymentRowCSV->payer_name,
            lastname: $paymentRowCSV->payer_surname,
            reference: $reference,
            amount: $paymentRowCSV->amount,
            payment_date: $paymentDate,
            payment_reference: $paymentRowCSV->payment_reference,
            description: $paymentRowCSV->description
        );
    }
}
