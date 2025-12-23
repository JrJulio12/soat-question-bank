<?php

namespace Tests\Feature;

use App\Enums\Stage;
use App\Models\Discipline;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisciplineControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_disciplines_index_requires_authentication(): void
    {
        $response = $this->get(route('disciplines.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_disciplines_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('disciplines.index'));
        $response->assertStatus(200);
        $response->assertViewIs('disciplines.index');
    }

    public function test_disciplines_index_displays_paginated_results(): void
    {
        Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        Discipline::create(['name' => 'Science', 'stage' => Stage::EM]);

        $response = $this->actingAs($this->user)->get(route('disciplines.index'));
        $response->assertStatus(200);
        $response->assertSee('Mathematics');
        $response->assertSee('Science');
    }

    public function test_authenticated_user_can_access_create_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('disciplines.create'));
        $response->assertStatus(200);
        $response->assertViewIs('disciplines.create');
    }

    public function test_create_form_requires_authentication(): void
    {
        $response = $this->get(route('disciplines.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_discipline(): void
    {
        $data = [
            'name' => 'Mathematics',
            'stage' => 'EF',
        ];

        $response = $this->actingAs($this->user)->post(route('disciplines.store'), $data);
        
        $response->assertRedirect(route('disciplines.index'));
        $response->assertSessionHas('success', 'Discipline created successfully.');
        $this->assertDatabaseHas('disciplines', [
            'name' => 'Mathematics',
            'stage' => 'EF',
        ]);
    }

    public function test_discipline_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('disciplines.store'), []);
        
        $response->assertSessionHasErrors(['name']);
    }

    public function test_discipline_creation_validates_stage_enum(): void
    {
        $data = [
            'name' => 'Mathematics',
            'stage' => 'INVALID',
        ];

        $response = $this->actingAs($this->user)->post(route('disciplines.store'), $data);
        $response->assertSessionHasErrors(['stage']);
    }

    public function test_discipline_can_be_created_without_stage(): void
    {
        $data = [
            'name' => 'Mathematics',
        ];

        $response = $this->actingAs($this->user)->post(route('disciplines.store'), $data);
        
        $response->assertRedirect(route('disciplines.index'));
        $this->assertDatabaseHas('disciplines', [
            'name' => 'Mathematics',
            'stage' => null,
        ]);
    }

    public function test_authenticated_user_can_view_discipline(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);

        $response = $this->actingAs($this->user)->get(route('disciplines.show', $discipline));
        $response->assertStatus(200);
        $response->assertViewIs('disciplines.show');
        $response->assertSee('Mathematics');
    }

    public function test_show_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $response = $this->get(route('disciplines.show', $discipline));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_edit_form(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);

        $response = $this->actingAs($this->user)->get(route('disciplines.edit', $discipline));
        $response->assertStatus(200);
        $response->assertViewIs('disciplines.edit');
        $response->assertSee('Mathematics');
    }

    public function test_edit_form_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $response = $this->get(route('disciplines.edit', $discipline));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_update_discipline(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);

        $data = [
            'name' => 'Updated Mathematics',
            'stage' => 'EM',
        ];

        $response = $this->actingAs($this->user)->put(route('disciplines.update', $discipline), $data);
        
        $response->assertRedirect(route('disciplines.index'));
        $response->assertSessionHas('success', 'Discipline updated successfully.');
        $this->assertDatabaseHas('disciplines', [
            'id' => $discipline->id,
            'name' => 'Updated Mathematics',
            'stage' => 'EM',
        ]);
    }

    public function test_discipline_update_validates_required_fields(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);

        $response = $this->actingAs($this->user)->put(route('disciplines.update', $discipline), []);
        $response->assertSessionHasErrors(['name']);
    }

    public function test_authenticated_user_can_delete_discipline(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);

        $response = $this->actingAs($this->user)->delete(route('disciplines.destroy', $discipline));
        
        $response->assertRedirect(route('disciplines.index'));
        $response->assertSessionHas('success', 'Discipline deleted successfully.');
        $this->assertDatabaseMissing('disciplines', ['id' => $discipline->id]);
    }

    public function test_delete_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $response = $this->delete(route('disciplines.destroy', $discipline));
        $response->assertRedirect(route('login'));
    }
}

