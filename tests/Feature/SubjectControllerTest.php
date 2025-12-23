<?php

namespace Tests\Feature;

use App\Enums\Stage;
use App\Models\Chapter;
use App\Models\Discipline;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_subjects_index_requires_authentication(): void
    {
        $response = $this->get(route('subjects.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_subjects_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('subjects.index'));
        $response->assertStatus(200);
        $response->assertViewIs('subjects.index');
    }

    public function test_subjects_index_displays_paginated_results(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        Subject::create(['name' => 'Solving Equations', 'chapter_id' => $chapter->id]);
        Subject::create(['name' => 'Graphing', 'chapter_id' => $chapter->id]);

        $response = $this->actingAs($this->user)->get(route('subjects.index'));
        $response->assertStatus(200);
        $response->assertSee('Solving Equations');
        $response->assertSee('Graphing');
    }

    public function test_subjects_index_filters_by_chapter_id(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter1 = Chapter::create(['name' => 'Chapter 1', 'topic_id' => $topic->id]);
        $chapter2 = Chapter::create(['name' => 'Chapter 2', 'topic_id' => $topic->id]);
        
        Subject::create(['name' => 'Subject 1', 'chapter_id' => $chapter1->id]);
        Subject::create(['name' => 'Subject 2', 'chapter_id' => $chapter2->id]);

        $response = $this->actingAs($this->user)->get(route('subjects.index', ['chapter_id' => $chapter1->id]));
        $response->assertStatus(200);
        $response->assertSee('Subject 1');
        $response->assertDontSee('Subject 2');
    }

    public function test_subjects_index_filter_with_empty_value_shows_all(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        Subject::create(['name' => 'Subject 1', 'chapter_id' => $chapter->id]);
        Subject::create(['name' => 'Subject 2', 'chapter_id' => $chapter->id]);

        $response = $this->actingAs($this->user)->get(route('subjects.index', ['chapter_id' => '']));
        $response->assertStatus(200);
        $response->assertSee('Subject 1');
        $response->assertSee('Subject 2');
    }

    public function test_authenticated_user_can_access_create_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('subjects.create'));
        $response->assertStatus(200);
        $response->assertViewIs('subjects.create');
    }

    public function test_create_form_requires_authentication(): void
    {
        $response = $this->get(route('subjects.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_subject(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);

        $data = [
            'name' => 'Solving Equations',
            'chapter_id' => $chapter->id,
        ];

        $response = $this->actingAs($this->user)->post(route('subjects.store'), $data);
        
        $response->assertRedirect(route('subjects.index'));
        $response->assertSessionHas('success', 'Subject created successfully.');
        $this->assertDatabaseHas('subjects', [
            'name' => 'Solving Equations',
            'chapter_id' => $chapter->id,
        ]);
    }

    public function test_subject_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('subjects.store'), []);
        $response->assertSessionHasErrors(['name', 'chapter_id']);
    }

    public function test_subject_creation_validates_chapter_exists(): void
    {
        $data = [
            'name' => 'Solving Equations',
            'chapter_id' => 999,
        ];

        $response = $this->actingAs($this->user)->post(route('subjects.store'), $data);
        $response->assertSessionHasErrors(['chapter_id']);
    }

    public function test_authenticated_user_can_view_subject(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        $subject = Subject::create(['name' => 'Solving Equations', 'chapter_id' => $chapter->id]);

        $response = $this->actingAs($this->user)->get(route('subjects.show', $subject));
        $response->assertStatus(200);
        $response->assertViewIs('subjects.show');
        $response->assertSee('Solving Equations');
    }

    public function test_show_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        $subject = Subject::create(['name' => 'Solving Equations', 'chapter_id' => $chapter->id]);
        $response = $this->get(route('subjects.show', $subject));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_edit_form(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        $subject = Subject::create(['name' => 'Solving Equations', 'chapter_id' => $chapter->id]);

        $response = $this->actingAs($this->user)->get(route('subjects.edit', $subject));
        $response->assertStatus(200);
        $response->assertViewIs('subjects.edit');
        $response->assertSee('Solving Equations');
    }

    public function test_edit_form_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        $subject = Subject::create(['name' => 'Solving Equations', 'chapter_id' => $chapter->id]);
        $response = $this->get(route('subjects.edit', $subject));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_update_subject(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter1 = Chapter::create(['name' => 'Chapter 1', 'topic_id' => $topic->id]);
        $chapter2 = Chapter::create(['name' => 'Chapter 2', 'topic_id' => $topic->id]);
        $subject = Subject::create(['name' => 'Solving Equations', 'chapter_id' => $chapter1->id]);

        $data = [
            'name' => 'Updated Subject',
            'chapter_id' => $chapter2->id,
        ];

        $response = $this->actingAs($this->user)->put(route('subjects.update', $subject), $data);
        
        $response->assertRedirect(route('subjects.index'));
        $response->assertSessionHas('success', 'Subject updated successfully.');
        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'name' => 'Updated Subject',
            'chapter_id' => $chapter2->id,
        ]);
    }

    public function test_subject_update_validates_required_fields(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        $subject = Subject::create(['name' => 'Solving Equations', 'chapter_id' => $chapter->id]);

        $response = $this->actingAs($this->user)->put(route('subjects.update', $subject), []);
        $response->assertSessionHasErrors(['name', 'chapter_id']);
    }

    public function test_authenticated_user_can_delete_subject(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        $subject = Subject::create(['name' => 'Solving Equations', 'chapter_id' => $chapter->id]);

        $response = $this->actingAs($this->user)->delete(route('subjects.destroy', $subject));
        
        $response->assertRedirect(route('subjects.index'));
        $response->assertSessionHas('success', 'Subject deleted successfully.');
        $this->assertDatabaseMissing('subjects', ['id' => $subject->id]);
    }

    public function test_delete_requires_authentication(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        $subject = Subject::create(['name' => 'Solving Equations', 'chapter_id' => $chapter->id]);
        $response = $this->delete(route('subjects.destroy', $subject));
        $response->assertRedirect(route('login'));
    }
}

