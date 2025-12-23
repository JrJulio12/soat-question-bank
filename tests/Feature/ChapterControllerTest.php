<?php

namespace Tests\Feature;

use App\Enums\Stage;
use App\Models\Chapter;
use App\Models\Discipline;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChapterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_chapters_index_requires_authentication(): void
    {
        $response = $this->get(route('chapters.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_chapters_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('chapters.index'));
        $response->assertStatus(200);
        $response->assertViewIs('chapters.index');
    }

    public function test_chapters_index_displays_paginated_results(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        Chapter::create(['name' => 'Quadratic Equations', 'topic_id' => $topic->id]);

        $response = $this->actingAs($this->user)->get(route('chapters.index'));
        $response->assertStatus(200);
        $response->assertSee('Linear Equations');
        $response->assertSee('Quadratic Equations');
    }

    public function test_chapters_index_filters_by_topic_id(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic1 = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $topic2 = Topic::create(['name' => 'Geometry', 'discipline_id' => $discipline->id]);
        
        Chapter::create(['name' => 'Algebra Chapter', 'topic_id' => $topic1->id]);
        Chapter::create(['name' => 'Geometry Chapter', 'topic_id' => $topic2->id]);

        $response = $this->actingAs($this->user)->get(route('chapters.index', ['topic_id' => $topic1->id]));
        $response->assertStatus(200);
        $response->assertSee('Algebra Chapter');
        $response->assertDontSee('Geometry Chapter');
    }

    public function test_chapters_index_filter_with_empty_value_shows_all(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        Chapter::create(['name' => 'Chapter 1', 'topic_id' => $topic->id]);
        Chapter::create(['name' => 'Chapter 2', 'topic_id' => $topic->id]);

        $response = $this->actingAs($this->user)->get(route('chapters.index', ['topic_id' => '']));
        $response->assertStatus(200);
        $response->assertSee('Chapter 1');
        $response->assertSee('Chapter 2');
    }

    public function test_authenticated_user_can_access_create_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('chapters.create'));
        $response->assertStatus(200);
        $response->assertViewIs('chapters.create');
    }

    public function test_create_form_requires_authentication(): void
    {
        $response = $this->get(route('chapters.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_chapter(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);

        $data = [
            'name' => 'Linear Equations',
            'topic_id' => $topic->id,
        ];

        $response = $this->actingAs($this->user)->post(route('chapters.store'), $data);
        
        $response->assertRedirect(route('chapters.index'));
        $response->assertSessionHas('success', 'Chapter created successfully.');
        $this->assertDatabaseHas('chapters', [
            'name' => 'Linear Equations',
            'topic_id' => $topic->id,
        ]);
    }

    public function test_chapter_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('chapters.store'), []);
        $response->assertSessionHasErrors(['name', 'topic_id']);
    }

    public function test_chapter_creation_validates_topic_exists(): void
    {
        $data = [
            'name' => 'Linear Equations',
            'topic_id' => 999,
        ];

        $response = $this->actingAs($this->user)->post(route('chapters.store'), $data);
        $response->assertSessionHasErrors(['topic_id']);
    }

    public function test_authenticated_user_can_view_chapter(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);

        $response = $this->actingAs($this->user)->get(route('chapters.show', $chapter));
        $response->assertStatus(200);
        $response->assertViewIs('chapters.show');
        $response->assertSee('Linear Equations');
    }

    public function test_show_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        $response = $this->get(route('chapters.show', $chapter));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_edit_form(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);

        $response = $this->actingAs($this->user)->get(route('chapters.edit', $chapter));
        $response->assertStatus(200);
        $response->assertViewIs('chapters.edit');
        $response->assertSee('Linear Equations');
    }

    public function test_edit_form_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        $response = $this->get(route('chapters.edit', $chapter));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_update_chapter(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic1 = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $topic2 = Topic::create(['name' => 'Geometry', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic1->id]);

        $data = [
            'name' => 'Updated Equations',
            'topic_id' => $topic2->id,
        ];

        $response = $this->actingAs($this->user)->put(route('chapters.update', $chapter), $data);
        
        $response->assertRedirect(route('chapters.index'));
        $response->assertSessionHas('success', 'Chapter updated successfully.');
        $this->assertDatabaseHas('chapters', [
            'id' => $chapter->id,
            'name' => 'Updated Equations',
            'topic_id' => $topic2->id,
        ]);
    }

    public function test_chapter_update_validates_required_fields(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);

        $response = $this->actingAs($this->user)->put(route('chapters.update', $chapter), []);
        $response->assertSessionHasErrors(['name', 'topic_id']);
    }

    public function test_authenticated_user_can_delete_chapter(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);

        $response = $this->actingAs($this->user)->delete(route('chapters.destroy', $chapter));
        
        $response->assertRedirect(route('chapters.index'));
        $response->assertSessionHas('success', 'Chapter deleted successfully.');
        $this->assertDatabaseMissing('chapters', ['id' => $chapter->id]);
    }

    public function test_delete_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        $response = $this->delete(route('chapters.destroy', $chapter));
        $response->assertRedirect(route('login'));
    }
}

