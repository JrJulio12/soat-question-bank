<?php

namespace Tests\Feature;

use App\Enums\Stage;
use App\Models\Bncc;
use App\Models\Discipline;
use App\Models\Knowledge;
use App\Models\Serie;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BnccControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_bnccs_index_requires_authentication(): void
    {
        $response = $this->get(route('bnccs.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_bnccs_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('bnccs.index'));
        $response->assertStatus(200);
        $response->assertViewIs('bnccs.index');
    }

    public function test_bnccs_index_displays_paginated_results(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'Description 1',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);
        Bncc::create([
            'code' => 'EF01MA02',
            'description' => 'Description 2',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('bnccs.index'));
        $response->assertStatus(200);
        $response->assertSee('EF01MA01');
        $response->assertSee('EF01MA02');
    }

    public function test_bnccs_index_filters_by_discipline_id(): void
    {
        $discipline1 = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $discipline2 = Discipline::create(['name' => 'Science', 'stage' => Stage::EF]);
        
        Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'Math BNCC',
            'stage' => Stage::EF,
            'discipline_id' => $discipline1->id,
        ]);
        Bncc::create([
            'code' => 'EF01CI01',
            'description' => 'Science BNCC',
            'stage' => Stage::EF,
            'discipline_id' => $discipline2->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('bnccs.index', ['discipline_id' => $discipline1->id]));
        $response->assertStatus(200);
        $response->assertSee('EF01MA01');
        $response->assertDontSee('EF01CI01');
    }

    public function test_bnccs_index_filter_with_empty_value_shows_all(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'BNCC 1',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);
        Bncc::create([
            'code' => 'EF01MA02',
            'description' => 'BNCC 2',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('bnccs.index', ['discipline_id' => '']));
        $response->assertStatus(200);
        $response->assertSee('EF01MA01');
        $response->assertSee('EF01MA02');
    }

    public function test_authenticated_user_can_access_create_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('bnccs.create'));
        $response->assertStatus(200);
        $response->assertViewIs('bnccs.create');
    }

    public function test_create_form_requires_authentication(): void
    {
        $response = $this->get(route('bnccs.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_bncc(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);

        $data = [
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => 'EF',
            'discipline_id' => $discipline->id,
        ];

        $response = $this->actingAs($this->user)->post(route('bnccs.store'), $data);
        
        $response->assertRedirect(route('bnccs.index'));
        $response->assertSessionHas('success', 'BNCC created successfully.');
        $this->assertDatabaseHas('bnccs', [
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => 'EF',
            'discipline_id' => $discipline->id,
        ]);
    }

    public function test_bncc_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('bnccs.store'), []);
        $response->assertSessionHasErrors(['code', 'stage', 'discipline_id']);
    }

    public function test_bncc_creation_validates_code_uniqueness(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'Existing',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);

        $data = [
            'code' => 'EF01MA01',
            'description' => 'Duplicate',
            'stage' => 'EF',
            'discipline_id' => $discipline->id,
        ];

        $response = $this->actingAs($this->user)->post(route('bnccs.store'), $data);
        $response->assertSessionHasErrors(['code']);
    }

    public function test_bncc_can_be_created_with_series_relationships(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $serie1 = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);
        $serie2 = Serie::create(['name' => '2nd Grade', 'stage' => Stage::EF, 'order' => 2]);

        $data = [
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => 'EF',
            'discipline_id' => $discipline->id,
            'series' => [$serie1->id, $serie2->id],
        ];

        $response = $this->actingAs($this->user)->post(route('bnccs.store'), $data);
        
        $response->assertRedirect(route('bnccs.index'));
        $bncc = Bncc::where('code', 'EF01MA01')->first();
        $this->assertCount(2, $bncc->series);
        $this->assertTrue($bncc->series->contains($serie1));
        $this->assertTrue($bncc->series->contains($serie2));
    }

    public function test_bncc_can_be_created_with_knowledges_relationships(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $knowledge1 = Knowledge::create(['name' => 'Knowledge 1', 'unit_id' => $unit->id]);
        $knowledge2 = Knowledge::create(['name' => 'Knowledge 2', 'unit_id' => $unit->id]);

        $data = [
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => 'EF',
            'discipline_id' => $discipline->id,
            'knowledges' => [$knowledge1->id, $knowledge2->id],
        ];

        $response = $this->actingAs($this->user)->post(route('bnccs.store'), $data);
        
        $response->assertRedirect(route('bnccs.index'));
        $bncc = Bncc::where('code', 'EF01MA01')->first();
        $this->assertCount(2, $bncc->knowledges);
        $this->assertTrue($bncc->knowledges->contains($knowledge1));
        $this->assertTrue($bncc->knowledges->contains($knowledge2));
    }

    public function test_bncc_can_be_created_with_both_series_and_knowledges(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $serie = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $knowledge = Knowledge::create(['name' => 'Knowledge 1', 'unit_id' => $unit->id]);

        $data = [
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => 'EF',
            'discipline_id' => $discipline->id,
            'series' => [$serie->id],
            'knowledges' => [$knowledge->id],
        ];

        $response = $this->actingAs($this->user)->post(route('bnccs.store'), $data);
        
        $response->assertRedirect(route('bnccs.index'));
        $bncc = Bncc::where('code', 'EF01MA01')->first();
        $this->assertCount(1, $bncc->series);
        $this->assertCount(1, $bncc->knowledges);
    }

    public function test_bncc_creation_validates_series_exist(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);

        $data = [
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => 'EF',
            'discipline_id' => $discipline->id,
            'series' => [999],
        ];

        $response = $this->actingAs($this->user)->post(route('bnccs.store'), $data);
        $response->assertSessionHasErrors(['series.0']);
    }

    public function test_bncc_creation_validates_knowledges_exist(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);

        $data = [
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => 'EF',
            'discipline_id' => $discipline->id,
            'knowledges' => [999],
        ];

        $response = $this->actingAs($this->user)->post(route('bnccs.store'), $data);
        $response->assertSessionHasErrors(['knowledges.0']);
    }

    public function test_authenticated_user_can_view_bncc(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $bncc = Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('bnccs.show', $bncc));
        $response->assertStatus(200);
        $response->assertViewIs('bnccs.show');
        $response->assertSee('EF01MA01');
    }

    public function test_show_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $bncc = Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);
        $response = $this->get(route('bnccs.show', $bncc));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_edit_form(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $bncc = Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);

        $response = $this->actingAs($this->user)->get(route('bnccs.edit', $bncc));
        $response->assertStatus(200);
        $response->assertViewIs('bnccs.edit');
        $response->assertSee('EF01MA01');
    }

    public function test_edit_form_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $bncc = Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);
        $response = $this->get(route('bnccs.edit', $bncc));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_update_bncc(): void
    {
        $discipline1 = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $discipline2 = Discipline::create(['name' => 'Science', 'stage' => Stage::EF]);
        $bncc = Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => Stage::EF,
            'discipline_id' => $discipline1->id,
        ]);

        $data = [
            'code' => 'EF01MA02',
            'description' => 'Updated description',
            'stage' => 'EM',
            'discipline_id' => $discipline2->id,
        ];

        $response = $this->actingAs($this->user)->put(route('bnccs.update', $bncc), $data);
        
        $response->assertRedirect(route('bnccs.index'));
        $response->assertSessionHas('success', 'BNCC updated successfully.');
        $this->assertDatabaseHas('bnccs', [
            'id' => $bncc->id,
            'code' => 'EF01MA02',
            'description' => 'Updated description',
            'stage' => 'EM',
            'discipline_id' => $discipline2->id,
        ]);
    }

    public function test_bncc_update_validates_code_uniqueness_excluding_current(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $bncc1 = Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'First',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);
        $bncc2 = Bncc::create([
            'code' => 'EF01MA02',
            'description' => 'Second',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);

        $data = [
            'code' => 'EF01MA02',
            'description' => 'Updated',
            'stage' => 'EF',
            'discipline_id' => $discipline->id,
        ];

        $response = $this->actingAs($this->user)->put(route('bnccs.update', $bncc1), $data);
        $response->assertSessionHasErrors(['code']);
    }

    public function test_bncc_can_be_updated_with_new_relationships(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $serie1 = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);
        $serie2 = Serie::create(['name' => '2nd Grade', 'stage' => Stage::EF, 'order' => 2]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $knowledge = Knowledge::create(['name' => 'Knowledge 1', 'unit_id' => $unit->id]);

        $bncc = Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);
        $bncc->series()->attach($serie1->id);

        $data = [
            'code' => 'EF01MA01',
            'description' => 'Updated description',
            'stage' => 'EF',
            'discipline_id' => $discipline->id,
            'series' => [$serie2->id],
            'knowledges' => [$knowledge->id],
        ];

        $response = $this->actingAs($this->user)->put(route('bnccs.update', $bncc), $data);
        
        $response->assertRedirect(route('bnccs.index'));
        $bncc->refresh();
        $this->assertCount(1, $bncc->series);
        $this->assertTrue($bncc->series->contains($serie2));
        $this->assertFalse($bncc->series->contains($serie1));
        $this->assertCount(1, $bncc->knowledges);
        $this->assertTrue($bncc->knowledges->contains($knowledge));
    }

    public function test_bncc_relationships_can_be_cleared(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $serie = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);
        $unit = Unit::create(['name' => 'Numbers', 'discipline_id' => $discipline->id]);
        $knowledge = Knowledge::create(['name' => 'Knowledge 1', 'unit_id' => $unit->id]);

        $bncc = Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);
        $bncc->series()->attach($serie->id);
        $bncc->knowledges()->attach($knowledge->id);

        $data = [
            'code' => 'EF01MA01',
            'description' => 'Updated description',
            'stage' => 'EF',
            'discipline_id' => $discipline->id,
        ];

        $response = $this->actingAs($this->user)->put(route('bnccs.update', $bncc), $data);
        
        $response->assertRedirect(route('bnccs.index'));
        $bncc->refresh();
        $this->assertCount(0, $bncc->series);
        $this->assertCount(0, $bncc->knowledges);
    }

    public function test_authenticated_user_can_delete_bncc(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $bncc = Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);

        $response = $this->actingAs($this->user)->delete(route('bnccs.destroy', $bncc));
        
        $response->assertRedirect(route('bnccs.index'));
        $response->assertSessionHas('success', 'BNCC deleted successfully.');
        $this->assertDatabaseMissing('bnccs', ['id' => $bncc->id]);
    }

    public function test_delete_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $bncc = Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);
        $response = $this->delete(route('bnccs.destroy', $bncc));
        $response->assertRedirect(route('login'));
    }
}

