<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get chat by staff success.
     *
     * @return void
     */
    public function test_get_chat_by_staff_success()
    {
        $staff = User::factory()->create(['user_type_id' => 2]);

        Passport::actingAs($staff);

        $this->json('get', route('api.v1.chats.index'))
             ->assertStatus(Response::HTTP_OK);
    }

    /**
     * Get chat by customer success.
     *
     * @return void
     */
    public function test_get_chat_by_customer_success()
    {
        $customer = User::factory()->create(['user_type_id' => 1]);

        Passport::actingAs($customer);

        $this->json('get', route('api.v1.chats.index'))
             ->assertStatus(Response::HTTP_OK);
    }

    /**
     * Create chat staff to staff success.
     *
     * @return void
     */
    public function test_create_chat_staff_to_staff_success()
    {
        $staff1 = User::factory()->create(['user_type_id' => 2]);
        $staff2 = User::factory()->create(['user_type_id' => 2]);

        Passport::actingAs($staff1);

        $payload = [
            'user_id2' => $staff2->id
        ];

        $this->json('post', route('api.v1.chats.store'), $payload)
             ->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Create chat staff to customer success.
     *
     * @return void
     */
    public function test_create_chat_staff_to_customer_success()
    {
        $staff = User::factory()->create(['user_type_id' => 2]);
        $customer = User::factory()->create(['user_type_id' => 1]);

        Passport::actingAs($staff);

        $payload = [
            'user_id2' => $customer->id
        ];

        $this->json('post', route('api.v1.chats.store'), $payload)
             ->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Create chat customer to customer success.
     *
     * @return void
     */
    public function test_create_chat_customer_to_customer_success()
    {
        $customer1 = User::factory()->create(['user_type_id' => 1]);
        $customer2 = User::factory()->create(['user_type_id' => 1]);

        Passport::actingAs($customer1);

        $payload = [
            'user_id2' => $customer2->id
        ];

        $this->json('post', route('api.v1.chats.store'), $payload)
             ->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Create chat customer to staff failed.
     *
     * @return void
     */
    public function test_create_chat_customer_to_staff_failed()
    {
        $customer = User::factory()->create(['user_type_id' => 1]);
        $staff = User::factory()->create(['user_type_id' => 2]);

        Passport::actingAs($customer);

        $payload = [
            'user_id2' => $staff->id
        ];

        $this->json('post', route('api.v1.chats.store'), $payload)
             ->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
