<?php

namespace Tests\Feature;

use App\Enums\Stage;
use App\Models\Discipline;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopicControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_topics_index_requires_authentication(): void
    {
        $response = $this->get(route('topics.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_topics_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('topics.index'));
        $response->assertStatus(200);
        $response->assertViewIs('topics.index');
    }

    public function test_topics_index_displays_paginated_results(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        Topic::create(['name' => 'Geometry', 'discipline_id' => $discipline->id]);

        $response = $this->actingAs($this->user)->get(route('topics.index'));
        $response->assertStatus(200);
        $response->assertSee('Algebra');
        $response->assertSee('Geometry');
    }

    public function test_topics_index_filters_by_discipline_id(): void
    {
        $discipline1 = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $discipline2 = Discipline::create(['name' => 'Science', 'stage' => Stage::EF]);
        
        Topic::create(['name' => 'Math Topic', 'discipline_id' => $discipline1->id]);
        Topic::create(['name' => 'Science Topic', 'discipline_id' => $discipline2->id]);

        $response = $this->actingAs($this->user)->get(route('topics.index', ['discipline_id' => $discipline1->id]));
        $response->assertStatus(200);
        $response->assertSee('Math Topic');
        $response->assertDontSee('Science Topic');
    }

    public function test_topics_index_filter_with_empty_value_shows_all(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        Topic::create(['name' => 'Topic 1', 'discipline_id' => $discipline->id]);
        Topic::create(['name' => 'Topic 2', 'discipline_id' => $discipline->id]);

        $response = $this->actingAs($this->user)->get(route('topics.index', ['discipline_id' => '']));
        $response->assertStatus(200);
        $response->assertSee('Topic 1');
        $response->assertSee('Topic 2');
    }

    public function test_authenticated_user_can_access_create_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('topics.create'));
        $response->assertStatus(200);
        $response->assertViewIs('topics.create');
    }

    public function test_create_form_requires_authentication(): void
    {
        $response = $this->get(route('topics.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_topic(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);

        $data = [
            'name' => 'Algebra',
            'discipline_id' => $discipline->id,
        ];

        $response = $this->actingAs($this->user)->post(route('topics.store'), $data);
        
        $response->assertRedirect(route('topics.index'));
        $response->assertSessionHas('success', 'Topic created successfully.');
        $this->assertDatabaseHas('topics', [
            'name' => 'Algebra',
            'discipline_id' => $discipline->id,
        ]);
    }

    public function test_topic_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('topics.store'), []);
        $response->assertSessionHasErrors(['name', 'discipline_id']);
    }

    public function test_topic_creation_validates_discipline_exists(): void
    {
        $data = [
            'name' => 'Algebra',
            'discipline_id' => 999,
        ];

        $response = $this->actingAs($this->user)->post(route('topics.store'), $data);
        $response->assertSessionHasErrors(['discipline_id']);
    }

    public function test_authenticated_user_can_view_topic(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);

        $response = $this->actingAs($this->user)->get(route('topics.show', $topic));
        $response->assertStatus(200);
        $response->assertViewIs('topics.show');
        $response->assertSee('Algebra');
    }

    public function test_show_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $response = $this->get(route('topics.show', $topic));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_edit_form(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);

        $response = $this->actingAs($this->user)->get(route('topics.edit', $topic));
        $response->assertStatus(200);
        $response->assertViewIs('topics.edit');
        $response->assertSee('Algebra');
    }

    public function test_edit_form_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $response = $this->get(route('topics.edit', $topic));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_update_topic(): void
    {
        $discipline1 = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $discipline2 = Discipline::create(['name' => 'Science', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline1->id]);

        $data = [
            'name' => 'Updated Algebra',
            'discipline_id' => $discipline2->id,
        ];

        $response = $this->actingAs($this->user)->put(route('topics.update', $topic), $data);
        
        $response->assertRedirect(route('topics.index'));
        $response->assertSessionHas('success', 'Topic updated successfully.');
        $this->assertDatabaseHas('topics', [
            'id' => $topic->id,
            'name' => 'Updated Algebra',
            'discipline_id' => $discipline2->id,
        ]);
    }

    public function test_topic_update_validates_required_fields(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);

        $response = $this->actingAs($this->user)->put(route('topics.update', $topic), []);
        $response->assertSessionHasErrors(['name', 'discipline_id']);
    }

    public function test_authenticated_user_can_delete_topic(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);

        $response = $this->actingAs($this->user)->delete(route('topics.destroy', $topic));
        
        $response->assertRedirect(route('topics.index'));
        $response->assertSessionHas('success', 'Topic deleted successfully.');
        $this->assertDatabaseMissing('topics', ['id' => $topic->id]);
    }

    public function test_delete_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $response = $this->delete(route('topics.destroy', $topic));
        $response->assertRedirect(route('login'));
    }
}

