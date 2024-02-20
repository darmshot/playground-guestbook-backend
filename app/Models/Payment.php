<?php

namespace App\Models;

use App\Enums\Payment\PaymentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

/**
 * @property int $id
 * @property Date $payment_date
 * @property string $payer_name
 * @property string $payer_surname
 * @property float $amount
 * @property string $national_security_number
 * @property PaymentStatus $status
 * @property string $description
 * @property string $payment_reference
 * @property string $ref_id
 * @property Date $created_at
 * @property Date $updated_at
 * @property PaymentOrder $paymentOrder
 */
class Payment extends Model
{
    use HasFactory;
    use HasUuids;

    protected $casts = [
        'status' => PaymentStatus::class,
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /*-------------------------------------------------
     *  Relations
     * ------------------------------------------------
     */
    public function paymentOrder(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PaymentOrder::class, 'payment_id');
    }

    public function loan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }
}
