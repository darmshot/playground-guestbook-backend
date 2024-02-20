<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Enums\Payment\LoanState;
use App\Models\Customer;
use App\Models\Loan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Api\PaymentController;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_store_payment(): void
    {
        $customer = Customer::factory()->create();


        // equal

        $reference = 'LN'.rand(10_000_000,99_999_999);

        Loan::factory()->create([
            'customer_id' => $customer,
            'reference'=> $reference,
            'state' => LoanState::ACTIVE,
            'amount_issued' => 100,
            'amount_to_pay' => 150,
        ]);

        $response = $this->postJson(action([PaymentController::class, 'store']), [
            'firstname' => 'Lorem',
            'lastname' => 'Ipsum',
            'payment_date' => '2022-12-12T15:19:21+00:00',
            'amount' => '150.00',
            'description' => "Lorem ipsum dolor$reference sit amet...",
            'ref_id' => 'dda8b637-b2e8-4f79-a4af-d1d68e266bf1',
        ]);

        $response->assertStatus(204);


        // greater and lower

        $reference = 'LN'.rand(10_000_000,99_999_999);

        Loan::factory()->create([
            'customer_id' => $customer,
            'reference'=> $reference,
            'state' => LoanState::ACTIVE,
            'amount_issued' => 100,
            'amount_to_pay' => 150,
        ]);

        $response = $this->postJson(action([PaymentController::class, 'store']), [
            'firstname' => 'Lorem',
            'lastname' => 'Ipsum',
            'payment_date' => '2022-12-12T15:19:21+00:00',
            'amount' => '99.99',
            'description' => "Lorem ipsum dolor$reference sit amet...",
            'ref_id' => 'dda8b637-b2e8-4f79-a4af-d1d68e266bf2',
        ]);

        $response->assertStatus(204);

        $response = $this->postJson(action([PaymentController::class, 'store']), [
            'firstname' => 'Lorem',
            'lastname' => 'Ipsum',
            'payment_date' => '2022-12-12T15:19:21+00:00',
            'amount' => '99.99',
            'description' => "Lorem ipsum dolor$reference sit amet...",
            'ref_id' => 'dda8b637-b2e8-4f79-a4af-d1d68e266bf3',
        ]);

        $response->assertStatus(204);
    }
}
