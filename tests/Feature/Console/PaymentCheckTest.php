<?php
declare(strict_types=1);


namespace Tests\Feature\Console;

use App\Models\Customer;
use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentCheckTest extends TestCase
{
    use RefreshDatabase;

    public function test_success_code()
    {
        $customer = Customer::factory()->create();
        $reference = [
            'LN93898517',
            'LN81051660',
            'LN94079201',
            'LN23350644',
            'LN80344936',
            'LN66032063',
            'LN76973642',
        ];
        Loan::factory()->createMany(array_map(fn($item) => [
            'reference' => $item,
            'customer_id' => $customer
        ], $reference));

        $this->artisan('payment:check --file=/payments_valid_test.csv')->assertExitCode(0);

    }

    public function test_reference_is_not_exist_not_valid_code()
    {
        $customer = Customer::factory()->create();
        $reference = [
            'LN81051660',
            'LN94079201',
            'LN23350644',
            'LN80344936',
            'LN66032063',
            'LN76973642',
        ];
        Loan::factory()->createMany(array_map(fn($item) => [
            'reference' => $item,
            'customer_id' => $customer
        ], $reference));

        $this->artisan('payment:check --file=/payments_valid_test.csv')->assertExitCode(3);

    }


    public function test_duplicate_entity_code()
    {
        $customer = Customer::factory()->create();
        $reference = [
            'LN93898517',
            'LN81051660',
            'LN94079201',
            'LN23350644',
            'LN80344936',
            'LN66032063',
            'LN76973642',
        ];
        Loan::factory()->createMany(array_map(fn($item) => [
            'reference' => $item,
            'customer_id' => $customer
        ], $reference));

        $this->artisan('payment:check --file=/payments_not_valid_duplicate_entity_test.csv --first-fail')->assertExitCode(1);

    }
}
