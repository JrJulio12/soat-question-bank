<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_users_can_access_home(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    public function test_unauthenticated_users_cannot_access_home(): void
    {
        $response = $this->get('/home');

        $response->assertRedirect(route('login'));
    }
}
