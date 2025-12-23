<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_dashboard_route_accessible_when_authenticated(): void
    {
        $response = $this->actingAs($this->user)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_all_resource_index_routes_require_authentication(): void
    {
        $routes = [
            'disciplines.index',
            'units.index',
            'knowledges.index',
            'topics.index',
            'chapters.index',
            'subjects.index',
            'series.index',
            'bnccs.index',
            'questions.index',
        ];

        foreach ($routes as $route) {
            $response = $this->get(route($route));
            $response->assertRedirect(route('login'));
        }
    }

    public function test_all_resource_index_routes_accessible_when_authenticated(): void
    {
        $routes = [
            'disciplines.index',
            'units.index',
            'knowledges.index',
            'topics.index',
            'chapters.index',
            'subjects.index',
            'series.index',
            'bnccs.index',
            'questions.index',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($this->user)->get(route($route));
            $response->assertStatus(200);
        }
    }

    public function test_all_resource_create_routes_require_authentication(): void
    {
        $routes = [
            'disciplines.create',
            'units.create',
            'knowledges.create',
            'topics.create',
            'chapters.create',
            'subjects.create',
            'series.create',
            'bnccs.create',
            'questions.create',
        ];

        foreach ($routes as $route) {
            $response = $this->get(route($route));
            $response->assertRedirect(route('login'));
        }
    }

    public function test_all_resource_create_routes_accessible_when_authenticated(): void
    {
        $routes = [
            'disciplines.create',
            'units.create',
            'knowledges.create',
            'topics.create',
            'chapters.create',
            'subjects.create',
            'series.create',
            'bnccs.create',
            'questions.create',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($this->user)->get(route($route));
            $response->assertStatus(200);
        }
    }
}

