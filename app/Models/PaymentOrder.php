<?php

namespace App\Models;

use App\Enums\Payment\PaymentOrderState;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $uuid
 * @property PaymentOrderState $state
 * @property float $amount
 * @property string $payment_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class PaymentOrder extends Model
{
    use HasFactory;
    use HasUuids;

    protected $casts = [
        'state' => PaymentOrderState::class,
        'amount' => 'decimal:2'
    ];
}
