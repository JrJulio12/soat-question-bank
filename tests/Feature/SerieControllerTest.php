<?php

namespace Tests\Feature;

use App\Enums\Stage;
use App\Models\Serie;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SerieControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_series_index_requires_authentication(): void
    {
        $response = $this->get(route('series.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_series_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('series.index'));
        $response->assertStatus(200);
        $response->assertViewIs('series.index');
    }

    public function test_series_index_displays_paginated_results(): void
    {
        Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);
        Serie::create(['name' => '2nd Grade', 'stage' => Stage::EF, 'order' => 2]);

        $response = $this->actingAs($this->user)->get(route('series.index'));
        $response->assertStatus(200);
        $response->assertSee('1st Grade');
        $response->assertSee('2nd Grade');
    }

    public function test_authenticated_user_can_access_create_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('series.create'));
        $response->assertStatus(200);
        $response->assertViewIs('series.create');
    }

    public function test_create_form_requires_authentication(): void
    {
        $response = $this->get(route('series.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_serie(): void
    {
        $data = [
            'name' => '1st Grade',
            'stage' => 'EF',
            'order' => 1,
        ];

        $response = $this->actingAs($this->user)->post(route('series.store'), $data);
        
        $response->assertRedirect(route('series.index'));
        $response->assertSessionHas('success', 'Serie created successfully.');
        $this->assertDatabaseHas('series', [
            'name' => '1st Grade',
            'stage' => 'EF',
            'order' => 1,
        ]);
    }

    public function test_serie_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('series.store'), []);
        $response->assertSessionHasErrors(['name', 'stage']);
    }

    public function test_serie_creation_validates_stage_enum(): void
    {
        $data = [
            'name' => '1st Grade',
            'stage' => 'INVALID',
        ];

        $response = $this->actingAs($this->user)->post(route('series.store'), $data);
        $response->assertSessionHasErrors(['stage']);
    }

    public function test_serie_can_be_created_without_order(): void
    {
        $data = [
            'name' => '1st Grade',
            'stage' => 'EF',
        ];

        $response = $this->actingAs($this->user)->post(route('series.store'), $data);
        
        $response->assertRedirect(route('series.index'));
        $this->assertDatabaseHas('series', [
            'name' => '1st Grade',
            'stage' => 'EF',
            'order' => null,
        ]);
    }

    public function test_serie_order_validates_minimum_value(): void
    {
        $data = [
            'name' => '1st Grade',
            'stage' => 'EF',
            'order' => 0,
        ];

        $response = $this->actingAs($this->user)->post(route('series.store'), $data);
        $response->assertSessionHasErrors(['order']);
    }

    public function test_authenticated_user_can_view_serie(): void
    {
        $serie = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);

        $response = $this->actingAs($this->user)->get(route('series.show', $serie));
        $response->assertStatus(200);
        $response->assertViewIs('series.show');
        $response->assertSee('1st Grade');
    }

    public function test_show_requires_authentication(): void
    {
        $serie = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);
        $response = $this->get(route('series.show', $serie));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_edit_form(): void
    {
        $serie = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);

        $response = $this->actingAs($this->user)->get(route('series.edit', $serie));
        $response->assertStatus(200);
        $response->assertViewIs('series.edit');
        $response->assertSee('1st Grade');
    }

    public function test_edit_form_requires_authentication(): void
    {
        $serie = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);
        $response = $this->get(route('series.edit', $serie));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_update_serie(): void
    {
        $serie = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);

        $data = [
            'name' => 'Updated Grade',
            'stage' => 'EM',
            'order' => 2,
        ];

        $response = $this->actingAs($this->user)->put(route('series.update', $serie), $data);
        
        $response->assertRedirect(route('series.index'));
        $response->assertSessionHas('success', 'Serie updated successfully.');
        $this->assertDatabaseHas('series', [
            'id' => $serie->id,
            'name' => 'Updated Grade',
            'order' => 2,
        ]);
        
        // Verify stage enum is updated correctly
        $serie->refresh();
        $this->assertEquals(Stage::EM, $serie->stage);
    }

    public function test_serie_update_validates_required_fields(): void
    {
        $serie = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);

        $response = $this->actingAs($this->user)->put(route('series.update', $serie), []);
        $response->assertSessionHasErrors(['name', 'stage']);
    }

    public function test_authenticated_user_can_delete_serie(): void
    {
        $serie = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);

        $response = $this->actingAs($this->user)->delete(route('series.destroy', $serie));
        
        $response->assertRedirect(route('series.index'));
        $response->assertSessionHas('success', 'Serie deleted successfully.');
        $this->assertDatabaseMissing('series', ['id' => $serie->id]);
    }

    public function test_delete_requires_authentication(): void
    {
        $serie = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);
        $response = $this->delete(route('series.destroy', $serie));
        $response->assertRedirect(route('login'));
    }
}

