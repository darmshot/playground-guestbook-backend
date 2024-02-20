<?php

namespace App\Models;

use App\Enums\Payment\LoanState;
use App\Models\Scopes\LoanActiveScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 * @property string $customer_id
 * @property string $reference
 * @property string $state
 * @property string $amount_issued
 * @property string $amount_to_pay
 * @property string $created_at
 * @property string $updated_at
 *
 * @property float $remains
 *
 * @property Payment[] $payments
 */
class Loan extends Model
{
    use HasFactory;
    use HasUuids;

    protected $casts = [
        'state' => LoanState::class,
        'amount_issued' => 'decimal:2',
        'amount_to_pay' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new LoanActiveScope);
    }

    /*-------------------------------------------------
     *  Attributes
     * ------------------------------------------------
     */

    protected function remains(): Attribute
    {
        return Attribute::make(function () {
            if (empty($this->payments_sum_amount)) {
                $this->loadSum('payments', 'amount');
            }

            return $this->amount_to_pay - $this->payments_sum_amount;
        })->shouldCache();
    }

    /*-------------------------------------------------
     *  Relations
     * ------------------------------------------------
     */

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class, 'loan_id');
    }
}
