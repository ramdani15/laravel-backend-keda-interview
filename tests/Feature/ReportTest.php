<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get report success.
     *
     * @return void
     */
    public function test_get_report_success()
    {
        $staff = User::factory()->create(['user_type_id' => 2]);

        Passport::actingAs($staff);

        $this->json('get', route('api.v1.reports.index'))
             ->assertStatus(Response::HTTP_OK);
    }

    /**
     * Get report failed.
     *
     * @return void
     */
    public function test_get_report_failed()
    {
        $customer = User::factory()->create(['user_type_id' => 1]);

        Passport::actingAs($customer);

        $this->json('get', route('api.v1.reports.index'))
             ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * Create report success.
     *
     * @return void
     */
    public function test_create_report_success()
    {
        $customer = User::factory()->create(['user_type_id' => 1]);

        Passport::actingAs($customer);

        $payload = [
            'type' => 'bug',
            'message' => 'new bug'
        ];

        $this->json('post', route('api.v1.reports.store'), $payload)
             ->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Create report failed.
     *
     * @return void
     */
    public function test_create_report_failed()
    {
        $staff = User::factory()->create(['user_type_id' => 2]);

        Passport::actingAs($staff);

        $payload = [
            'type' => 'bug',
            'message' => 'new bug'
        ];

        $this->json('post', route('api.v1.reports.store'), $payload)
             ->assertStatus(Response::HTTP_FORBIDDEN);
    }
}
