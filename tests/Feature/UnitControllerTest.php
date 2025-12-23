<?php

namespace Tests\Feature;

use App\Enums\Stage;
use App\Models\Discipline;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_units_index_requires_authentication(): void
    {
        $response = $this->get(route('units.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_units_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('units.index'));
        $response->assertStatus(200);
        $response->assertViewIs('units.index');
    }

    public function test_units_index_displays_paginated_results(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        Unit::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);

        $response = $this->actingAs($this->user)->get(route('units.index'));
        $response->assertStatus(200);
        $response->assertSee('Numbers');
        $response->assertSee('Algebra');
    }

    public function test_units_index_filters_by_discipline_id(): void
    {
        $discipline1 = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $discipline2 = Discipline::create(['name' => 'Science', 'stage' => Stage::EF]);
        
        Unit::create(['name' => 'Math Unit', 'discipline_id' => $discipline1->id]);
        Unit::create(['name' => 'Science Unit', 'discipline_id' => $discipline2->id]);

        $response = $this->actingAs($this->user)->get(route('units.index', ['discipline_id' => $discipline1->id]));
        $response->assertStatus(200);
        $response->assertSee('Math Unit');
        $response->assertDontSee('Science Unit');
    }

    public function test_units_index_filter_with_empty_value_shows_all(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        Unit::create(['name' => 'Unit 1', 'discipline_id' => $discipline->id]);
        Unit::create(['name' => 'Unit 2', 'discipline_id' => $discipline->id]);

        $response = $this->actingAs($this->user)->get(route('units.index', ['discipline_id' => '']));

        $response->assertStatus(200);
        $response->assertSee('Unit 1');
        $response->assertSee('Unit 2');
    }

    public function test_authenticated_user_can_access_create_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('units.create'));
        $response->assertStatus(200);
        $response->assertViewIs('units.create');
    }

    public function test_create_form_requires_authentication(): void
    {
        $response = $this->get(route('units.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_unit(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);

        $data = [
            'name' => 'Numbers',
            'discipline_id' => $discipline->id,
        ];

        $response = $this->actingAs($this->user)->post(route('units.store'), $data);
        
        $response->assertRedirect(route('units.index'));
        $response->assertSessionHas('success', 'Unit created successfully.');
        $this->assertDatabaseHas('units', [
            'name' => 'Numbers',
            'discipline_id' => $discipline->id,
        ]);
    }

    public function test_unit_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('units.store'), []);
        $response->assertSessionHasErrors(['name', 'discipline_id']);
    }

    public function test_unit_creation_validates_discipline_exists(): void
    {
        $data = [
            'name' => 'Numbers',
            'discipline_id' => 999,
        ];

        $response = $this->actingAs($this->user)->post(route('units.store'), $data);
        $response->assertSessionHasErrors(['discipline_id']);
    }

    public function test_authenticated_user_can_view_unit(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);

        $response = $this->actingAs($this->user)->get(route('units.show', $unit));
        $response->assertStatus(200);
        $response->assertViewIs('units.show');
        $response->assertSee('Numbers');
    }

    public function test_show_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $response = $this->get(route('units.show', $unit));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_edit_form(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);

        $response = $this->actingAs($this->user)->get(route('units.edit', $unit));
        $response->assertStatus(200);
        $response->assertViewIs('units.edit');
        $response->assertSee('Numbers');
    }

    public function test_edit_form_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $response = $this->get(route('units.edit', $unit));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_update_unit(): void
    {
        $discipline1 = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $discipline2 = Discipline::create(['name' => 'Science', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline1->id]);

        $data = [
            'name' => 'Updated Numbers',
            'discipline_id' => $discipline2->id,
        ];

        $response = $this->actingAs($this->user)->put(route('units.update', $unit), $data);
        
        $response->assertRedirect(route('units.index'));
        $response->assertSessionHas('success', 'Unit updated successfully.');
        $this->assertDatabaseHas('units', [
            'id' => $unit->id,
            'name' => 'Updated Numbers',
            'discipline_id' => $discipline2->id,
        ]);
    }

    public function test_unit_update_validates_required_fields(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);

        $response = $this->actingAs($this->user)->put(route('units.update', $unit), []);
        $response->assertSessionHasErrors(['name', 'discipline_id']);
    }

    public function test_authenticated_user_can_delete_unit(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);

        $response = $this->actingAs($this->user)->delete(route('units.destroy', $unit));
        
        $response->assertRedirect(route('units.index'));
        $response->assertSessionHas('success', 'Unit deleted successfully.');
        $this->assertDatabaseMissing('units', ['id' => $unit->id]);
    }

    public function test_delete_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $response = $this->delete(route('units.destroy', $unit));
        $response->assertRedirect(route('login'));
    }
}

