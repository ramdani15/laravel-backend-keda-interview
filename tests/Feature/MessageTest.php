<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get message by chat author success.
     *
     * @return void
     */
    public function test_get_message_by_chat_author_success()
    {
        $staff1 = User::factory()->create(['user_type_id' => 2]);
        $staff2 = User::factory()->create(['user_type_id' => 2]);

        $chat = Chat::factory()->create([
            'user_id1' => $staff1->id,
            'user_id2' => $staff2->id,
        ]);

        Passport::actingAs($staff1);

        $this->json('get', route('api.v1.messages.index', ['chatId' => $chat->id]))
             ->assertStatus(Response::HTTP_OK);
    }

    /**
     * Get message by chat unauthor success.
     *
     * @return void
     */
    public function test_get_message_by_chat_unauthor_failed()
    {
        $staff1 = User::factory()->create(['user_type_id' => 2]);
        $staff2 = User::factory()->create(['user_type_id' => 2]);
        $staff3 = User::factory()->create(['user_type_id' => 2]);

        $chat = Chat::factory()->create([
            'user_id1' => $staff1->id,
            'user_id2' => $staff2->id,
        ]);

        Passport::actingAs($staff3);

        $this->json('get', route('api.v1.messages.index', ['chatId' => $chat->id]))
             ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Create message by chat author success.
     *
     * @return void
     */
    public function test_create_message_by_chat_author_success()
    {
        $staff1 = User::factory()->create(['user_type_id' => 2]);
        $staff2 = User::factory()->create(['user_type_id' => 2]);

        $chat = Chat::factory()->create([
            'user_id1' => $staff1->id,
            'user_id2' => $staff2->id,
        ]);

        Passport::actingAs($staff1);

        $payload = [
            'message' => 'new message'
        ];

        $this->json('post', route('api.v1.messages.store', ['chatId' => $chat->id]), $payload)
             ->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Create message by chat unauthor success.
     *
     * @return void
     */
    public function test_create_message_by_chat_unauthor_failed()
    {
        $staff1 = User::factory()->create(['user_type_id' => 2]);
        $staff2 = User::factory()->create(['user_type_id' => 2]);
        $staff3 = User::factory()->create(['user_type_id' => 2]);

        $chat = Chat::factory()->create([
            'user_id1' => $staff1->id,
            'user_id2' => $staff2->id,
        ]);

        Passport::actingAs($staff3);

        $payload = [
            'message' => 'new message'
        ];

        $this->json('post', route('api.v1.messages.store', ['chatId' => $chat->id]), $payload)
             ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
