<?php

namespace Tests\Feature;

use App\Enums\Stage;
use App\Models\Discipline;
use App\Models\Knowledge;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KnowledgeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_knowledges_index_requires_authentication(): void
    {
        $response = $this->get(route('knowledges.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_knowledges_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('knowledges.index'));
        $response->assertStatus(200);
        $response->assertViewIs('knowledges.index');
    }

    public function test_knowledges_index_displays_paginated_results(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        Knowledge::create(['name' => 'Basic Operations', 'unit_id' => $unit->id]);
        Knowledge::create(['name' => 'Advanced Operations', 'unit_id' => $unit->id]);

        $response = $this->actingAs($this->user)->get(route('knowledges.index'));
        $response->assertStatus(200);
        $response->assertSee('Basic Operations');
        $response->assertSee('Advanced Operations');
    }

    public function test_knowledges_index_filters_by_unit_id(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit1 = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $unit2 = Unit::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        
        Knowledge::create(['name' => 'Numbers Knowledge', 'unit_id' => $unit1->id]);
        Knowledge::create(['name' => 'Algebra Knowledge', 'unit_id' => $unit2->id]);

        $response = $this->actingAs($this->user)->get(route('knowledges.index', ['unit_id' => $unit1->id]));
        $response->assertStatus(200);
        $response->assertSee('Numbers Knowledge');
        $response->assertDontSee('Algebra Knowledge');
    }

    public function test_knowledges_index_filter_with_empty_value_shows_all(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        Knowledge::create(['name' => 'Knowledge 1', 'unit_id' => $unit->id]);
        Knowledge::create(['name' => 'Knowledge 2', 'unit_id' => $unit->id]);

        $response = $this->actingAs($this->user)->get(route('knowledges.index', ['unit_id' => '']));
        $response->assertStatus(200);
        $response->assertSee('Knowledge 1');
        $response->assertSee('Knowledge 2');
    }

    public function test_authenticated_user_can_access_create_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('knowledges.create'));
        $response->assertStatus(200);
        $response->assertViewIs('knowledges.create');
    }

    public function test_create_form_requires_authentication(): void
    {
        $response = $this->get(route('knowledges.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_knowledge(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);

        $data = [
            'name' => 'Basic Operations',
            'unit_id' => $unit->id,
        ];

        $response = $this->actingAs($this->user)->post(route('knowledges.store'), $data);
        
        $response->assertRedirect(route('knowledges.index'));
        $response->assertSessionHas('success', 'Knowledge created successfully.');
        $this->assertDatabaseHas('knowledges', [
            'name' => 'Basic Operations',
            'unit_id' => $unit->id,
        ]);
    }

    public function test_knowledge_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('knowledges.store'), []);
        $response->assertSessionHasErrors(['name', 'unit_id']);
    }

    public function test_knowledge_creation_validates_unit_exists(): void
    {
        $data = [
            'name' => 'Basic Operations',
            'unit_id' => 999,
        ];

        $response = $this->actingAs($this->user)->post(route('knowledges.store'), $data);
        $response->assertSessionHasErrors(['unit_id']);
    }

    public function test_authenticated_user_can_view_knowledge(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $knowledge = Knowledge::create(['name' => 'Basic Operations', 'unit_id' => $unit->id]);

        $response = $this->actingAs($this->user)->get(route('knowledges.show', $knowledge));
        $response->assertStatus(200);
        $response->assertViewIs('knowledges.show');
        $response->assertSee('Basic Operations');
    }

    public function test_show_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $knowledge = Knowledge::create(['name' => 'Basic Operations', 'unit_id' => $unit->id]);
        $response = $this->get(route('knowledges.show', $knowledge));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_edit_form(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $knowledge = Knowledge::create(['name' => 'Basic Operations', 'unit_id' => $unit->id]);

        $response = $this->actingAs($this->user)->get(route('knowledges.edit', $knowledge));
        $response->assertStatus(200);
        $response->assertViewIs('knowledges.edit');
        $response->assertSee('Basic Operations');
    }

    public function test_edit_form_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $knowledge = Knowledge::create(['name' => 'Basic Operations', 'unit_id' => $unit->id]);
        $response = $this->get(route('knowledges.edit', $knowledge));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_update_knowledge(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit1 = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $unit2 = Unit::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $knowledge = Knowledge::create(['name' => 'Basic Operations', 'unit_id' => $unit1->id]);

        $data = [
            'name' => 'Updated Operations',
            'unit_id' => $unit2->id,
        ];

        $response = $this->actingAs($this->user)->put(route('knowledges.update', $knowledge), $data);
        
        $response->assertRedirect(route('knowledges.index'));
        $response->assertSessionHas('success', 'Knowledge updated successfully.');
        $this->assertDatabaseHas('knowledges', [
            'id' => $knowledge->id,
            'name' => 'Updated Operations',
            'unit_id' => $unit2->id,
        ]);
    }

    public function test_knowledge_update_validates_required_fields(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $knowledge = Knowledge::create(['name' => 'Basic Operations', 'unit_id' => $unit->id]);

        $response = $this->actingAs($this->user)->put(route('knowledges.update', $knowledge), []);
        $response->assertSessionHasErrors(['name', 'unit_id']);
    }

    public function test_authenticated_user_can_delete_knowledge(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $knowledge = Knowledge::create(['name' => 'Basic Operations', 'unit_id' => $unit->id]);

        $response = $this->actingAs($this->user)->delete(route('knowledges.destroy', $knowledge));
        
        $response->assertRedirect(route('knowledges.index'));
        $response->assertSessionHas('success', 'Knowledge deleted successfully.');
        $this->assertDatabaseMissing('knowledges', ['id' => $knowledge->id]);
    }

    public function test_delete_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $knowledge = Knowledge::create(['name' => 'Basic Operations', 'unit_id' => $unit->id]);
        $response = $this->delete(route('knowledges.destroy', $knowledge));
        $response->assertRedirect(route('login'));
    }
}

