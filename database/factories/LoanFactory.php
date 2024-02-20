<?php

namespace Database\Factories;

use App\Enums\Payment\LoanState;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => fn() => 'LN' . rand(10_000_000, 99_999_999),
            'state' => LoanState::ACTIVE,
            'amount_issued' => $issued = rand(100, 3000),
            'amount_to_pay' => rand($issued + 20, 3020),
        ];
    }
}
