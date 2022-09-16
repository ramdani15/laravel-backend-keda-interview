<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get customer success.
     *
     * @return void
     */
    public function test_get_customer_success()
    {
        $staff = User::factory()->create(['user_type_id' => 2]);

        Passport::actingAs($staff);

        $this->json('get', route('api.v1.customers.index'))
             ->assertStatus(Response::HTTP_OK);
    }

    /**
     * Get customer failed.
     *
     * @return void
     */
    public function test_get_customer_failed()
    {
        $customer = User::factory()->create(['user_type_id' => 1]);

        Passport::actingAs($customer);

        $this->json('get', route('api.v1.customers.index'))
             ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Delete customer success.
     *
     * @return void
     */
    public function test_delete_customer_success()
    {
        $staff = User::factory()->create(['user_type_id' => 2]);
        $customer = User::factory()->create(['user_type_id' => 1]);

        Passport::actingAs($staff);

        $this->json('delete', route('api.v1.customers.destroy', ['customer' => $customer->id]))
             ->assertStatus(Response::HTTP_OK);
    }

    /**
     * Delete customer failed.
     *
     * @return void
     */
    public function test_delete_customer_failed()
    {
        $customer1 = User::factory()->create(['user_type_id' => 1]);
        $customer2 = User::factory()->create(['user_type_id' => 1]);

        Passport::actingAs($customer1);

        $this->json('delete', route('api.v1.customers.destroy', ['customer' => $customer2->id]))
             ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
