<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Login success.
     *
     * @return void
     */
    public function test_login_success()
    {
        $password = 'test1234';
        $user = User::factory()->create(['password' => bcrypt($password)]);
        $payload = [
            'email' => $user->email,
            'password' => $password
        ];

        $this->json('post', route('api.v1.auth.login'), $payload)
             ->assertStatus(Response::HTTP_OK);
    }

    /**
     * Login failed.
     *
     * @return void
     */
    public function test_login_failed()
    {
        $password = 'test1234';
        $user = User::factory()->create(['password' => bcrypt($password)]);
        $payload = [
            'email' => $user->email,
            'password' => $password.'xx'
        ];

        $this->json('post', route('api.v1.auth.login'), $payload)
             ->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Register success.
     *
     * @return void
     */
    public function test_register_success()
    {
        $payload = [
            'email' => 'email1@gmail.com',
            'password' => 'password1',
            'user_type_id' => 1,
        ];

        $this->json('post', route('api.v1.auth.register'), $payload)
             ->assertStatus(Response::HTTP_CREATED);


        $this->json('post', route('api.v1.auth.login'), $payload)
             ->assertStatus(Response::HTTP_OK);
    }

    /**
     * Register failed.
     *
     * @return void
     */
    public function test_register_failed()
    {
        $payload = [
            'email' => 'email1@gmail.com',
            'password' => 'password1'
        ];

        $this->json('post', route('api.v1.auth.register'), $payload)
             ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
