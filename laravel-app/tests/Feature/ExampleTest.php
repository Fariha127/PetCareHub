<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_regular_users_cannot_access_shelter_dashboard(): void
    {
        $user = User::create([
            'full_name' => 'Regular Adopter',
            'email' => 'regular@example.com',
            'password' => 'password',
            'role' => 'USER',
        ]);

        $this->actingAs($user)
            ->get('/dashboard/shelter')
            ->assertForbidden();
    }

    public function test_donation_routes_are_not_available(): void
    {
        $this->get('/donations')->assertNotFound();
    }
}
