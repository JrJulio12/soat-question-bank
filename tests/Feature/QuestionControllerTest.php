<?php

namespace Tests\Feature;

use App\Enums\QuestionStatus;
use App\Enums\QuestionType;
use App\Enums\Stage;
use App\Models\Bncc;
use App\Models\Chapter;
use App\Models\Discipline;
use App\Models\Knowledge;
use App\Models\Question;
use App\Models\Serie;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_questions_index_requires_authentication(): void
    {
        $response = $this->get(route('questions.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_questions_index(): void
    {
        $response = $this->actingAs($this->user)->get(route('questions.index'));
        $response->assertStatus(200);
        $response->assertViewIs('questions.index');
    }

    public function test_questions_index_displays_paginated_results(): void
    {
        Question::create([
            'stem' => 'Question 1',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);
        Question::create([
            'stem' => 'Question 2',
            'stage' => Stage::EM,
            'type' => QuestionType::TRUE_FALSE,
            'status' => QuestionStatus::PUBLISHED,
        ]);

        $response = $this->actingAs($this->user)->get(route('questions.index'));
        $response->assertStatus(200);
        $response->assertSee('Question 1');
        $response->assertSee('Question 2');
    }

    public function test_questions_index_filters_by_stage(): void
    {
        Question::create([
            'stem' => 'EF Question',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);
        Question::create([
            'stem' => 'EM Question',
            'stage' => Stage::EM,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);

        $response = $this->actingAs($this->user)->get(route('questions.index', ['stage' => 'EF']));
        $response->assertStatus(200);
        $response->assertSee('EF Question');
        $response->assertDontSee('EM Question');
    }

    public function test_questions_index_filters_by_status(): void
    {
        Question::create([
            'stem' => 'Draft Question',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);
        Question::create([
            'stem' => 'Published Question',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::PUBLISHED,
        ]);

        $response = $this->actingAs($this->user)->get(route('questions.index', ['status' => 'draft']));
        $response->assertStatus(200);
        $response->assertSee('Draft Question');
        $response->assertDontSee('Published Question');
    }

    public function test_questions_index_filters_by_stage_and_status(): void
    {
        Question::create([
            'stem' => 'EF Draft Question',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);
        Question::create([
            'stem' => 'EF Published Question',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::PUBLISHED,
        ]);
        Question::create([
            'stem' => 'EM Draft Question',
            'stage' => Stage::EM,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);

        $response = $this->actingAs($this->user)->get(route('questions.index', [
            'stage' => 'EF',
            'status' => 'draft',
        ]));
        $response->assertStatus(200);
        $response->assertSee('EF Draft Question');
        $response->assertDontSee('EF Published Question');
        $response->assertDontSee('EM Draft Question');
    }

    public function test_questions_index_filter_with_empty_value_shows_all(): void
    {
        Question::create([
            'stem' => 'Question 1',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);
        Question::create([
            'stem' => 'Question 2',
            'stage' => Stage::EM,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::PUBLISHED,
        ]);

        $response = $this->actingAs($this->user)->get(route('questions.index', ['stage' => '', 'status' => '']));
        $response->assertStatus(200);
        $response->assertSee('Question 1');
        $response->assertSee('Question 2');
    }

    public function test_authenticated_user_can_access_create_form(): void
    {
        $response = $this->actingAs($this->user)->get(route('questions.create'));
        $response->assertStatus(200);
        $response->assertViewIs('questions.create');
    }

    public function test_create_form_requires_authentication(): void
    {
        $response = $this->get(route('questions.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_question(): void
    {
        $data = [
            'stem' => 'What is 2+2?',
            'answer_text' => '4',
            'stage' => 'EF',
            'type' => 'multiple_choice',
            'status' => 'draft',
        ];

        $response = $this->actingAs($this->user)->post(route('questions.store'), $data);
        
        $response->assertRedirect(route('questions.index'));
        $response->assertSessionHas('success', 'Question created successfully.');
        $this->assertDatabaseHas('questions', [
            'stem' => 'What is 2+2?',
            'answer_text' => '4',
            'stage' => 'EF',
            'type' => 'multiple_choice',
            'status' => 'draft',
        ]);
    }

    public function test_question_creation_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('questions.store'), []);
        $response->assertSessionHasErrors(['stem', 'stage', 'type', 'status']);
    }

    public function test_question_creation_validates_stage_enum(): void
    {
        $data = [
            'stem' => 'Test question',
            'stage' => 'INVALID',
            'type' => 'multiple_choice',
            'status' => 'draft',
        ];

        $response = $this->actingAs($this->user)->post(route('questions.store'), $data);
        $response->assertSessionHasErrors(['stage']);
    }

    public function test_question_creation_validates_type_enum(): void
    {
        $data = [
            'stem' => 'Test question',
            'stage' => 'EF',
            'type' => 'INVALID',
            'status' => 'draft',
        ];

        $response = $this->actingAs($this->user)->post(route('questions.store'), $data);
        $response->assertSessionHasErrors(['type']);
    }

    public function test_question_creation_validates_status_enum(): void
    {
        $data = [
            'stem' => 'Test question',
            'stage' => 'EF',
            'type' => 'multiple_choice',
            'status' => 'INVALID',
        ];

        $response = $this->actingAs($this->user)->post(route('questions.store'), $data);
        $response->assertSessionHasErrors(['status']);
    }

    public function test_questions_can_be_created_with_bnccs(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $serie = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);
        $bncc1 = Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'BNCC 1',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);
        $bncc1->series()->attach($serie->id);
        $bncc2 = Bncc::create([
            'code' => 'EF01MA02',
            'description' => 'BNCC 2',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);
        $bncc2->series()->attach($serie->id);

        $data = [
            'stem' => 'Test question',
            'stage' => 'EF',
            'type' => 'multiple_choice',
            'status' => 'draft',
            'bnccs' => [$bncc1->id, $bncc2->id],
        ];

        $response = $this->actingAs($this->user)->post(route('questions.store'), $data);
        
        $response->assertRedirect(route('questions.index'));
        $question = Question::where('stem', 'Test question')->first();
        $this->assertCount(2, $question->bnccs);
        $this->assertTrue($question->bnccs->contains($bncc1));
        $this->assertTrue($question->bnccs->contains($bncc2));
    }

    public function test_questions_can_be_created_with_subjects(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        $subject1 = Subject::create(['name' => 'Subject 1', 'chapter_id' => $chapter->id]);
        $subject2 = Subject::create(['name' => 'Subject 2', 'chapter_id' => $chapter->id]);

        $data = [
            'stem' => 'Test question',
            'stage' => 'EF',
            'type' => 'multiple_choice',
            'status' => 'draft',
            'subjects' => [$subject1->id, $subject2->id],
        ];

        $response = $this->actingAs($this->user)->post(route('questions.store'), $data);
        
        $response->assertRedirect(route('questions.index'));
        $question = Question::where('stem', 'Test question')->first();
        $this->assertCount(2, $question->subjects);
        $this->assertTrue($question->subjects->contains($subject1));
        $this->assertTrue($question->subjects->contains($subject2));
    }

    public function test_questions_can_be_created_with_both_bnccs_and_subjects(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $serie = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);
        $bncc = Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'BNCC 1',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);
        $bncc->series()->attach($serie->id);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        $subject = Subject::create(['name' => 'Subject 1', 'chapter_id' => $chapter->id]);

        $data = [
            'stem' => 'Test question',
            'stage' => 'EF',
            'type' => 'multiple_choice',
            'status' => 'draft',
            'bnccs' => [$bncc->id],
            'subjects' => [$subject->id],
        ];

        $response = $this->actingAs($this->user)->post(route('questions.store'), $data);
        
        $response->assertRedirect(route('questions.index'));
        $question = Question::where('stem', 'Test question')->first();
        $this->assertCount(1, $question->bnccs);
        $this->assertCount(1, $question->subjects);
    }

    public function test_question_creation_validates_bnccs_exist(): void
    {
        $data = [
            'stem' => 'Test question',
            'stage' => 'EF',
            'type' => 'multiple_choice',
            'status' => 'draft',
            'bnccs' => [999],
        ];

        $response = $this->actingAs($this->user)->post(route('questions.store'), $data);
        $response->assertSessionHasErrors(['bnccs.0']);
    }

    public function test_question_creation_validates_subjects_exist(): void
    {
        $data = [
            'stem' => 'Test question',
            'stage' => 'EF',
            'type' => 'multiple_choice',
            'status' => 'draft',
            'subjects' => [999],
        ];

        $response = $this->actingAs($this->user)->post(route('questions.store'), $data);
        $response->assertSessionHasErrors(['subjects.0']);
    }

    public function test_authenticated_user_can_view_question(): void
    {
        $question = Question::create([
            'stem' => 'What is 2+2?',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);

        $response = $this->actingAs($this->user)->get(route('questions.show', $question));
        $response->assertStatus(200);
        $response->assertViewIs('questions.show');
        $response->assertSee('What is 2+2?');
    }

    public function test_show_requires_authentication(): void
    {
        $question = Question::create([
            'stem' => 'What is 2+2?',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);
        $response = $this->get(route('questions.show', $question));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_edit_form(): void
    {
        $question = Question::create([
            'stem' => 'What is 2+2?',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);

        $response = $this->actingAs($this->user)->get(route('questions.edit', $question));
        $response->assertStatus(200);
        $response->assertViewIs('questions.edit');
        $response->assertSee('What is 2+2?');
    }

    public function test_edit_form_requires_authentication(): void
    {
        $question = Question::create([
            'stem' => 'What is 2+2?',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);
        $response = $this->get(route('questions.edit', $question));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_update_question(): void
    {
        $question = Question::create([
            'stem' => 'What is 2+2?',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);

        $data = [
            'stem' => 'Updated question',
            'answer_text' => 'Updated answer',
            'stage' => 'EM',
            'type' => 'true_false',
            'status' => 'published',
        ];

        $response = $this->actingAs($this->user)->put(route('questions.update', $question), $data);
        
        $response->assertRedirect(route('questions.index'));
        $response->assertSessionHas('success', 'Question updated successfully.');
        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'stem' => 'Updated question',
            'answer_text' => 'Updated answer',
            'stage' => 'EM',
            'type' => 'true_false',
            'status' => 'published',
        ]);
    }

    public function test_question_update_validates_required_fields(): void
    {
        $question = Question::create([
            'stem' => 'What is 2+2?',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);

        $response = $this->actingAs($this->user)->put(route('questions.update', $question), []);
        $response->assertSessionHasErrors(['stem', 'stage', 'type', 'status']);
    }

    public function test_questions_can_be_updated_with_new_relationships(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $serie = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);
        $bncc1 = Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'BNCC 1',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);
        $bncc1->series()->attach($serie->id);
        $bncc2 = Bncc::create([
            'code' => 'EF01MA02',
            'description' => 'BNCC 2',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);
        $bncc2->series()->attach($serie->id);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        $subject = Subject::create(['name' => 'Subject 1', 'chapter_id' => $chapter->id]);

        $question = Question::create([
            'stem' => 'Test question',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);
        $question->bnccs()->attach($bncc1->id);

        $data = [
            'stem' => 'Updated question',
            'stage' => 'EF',
            'type' => 'multiple_choice',
            'status' => 'draft',
            'bnccs' => [$bncc2->id],
            'subjects' => [$subject->id],
        ];

        $response = $this->actingAs($this->user)->put(route('questions.update', $question), $data);
        
        $response->assertRedirect(route('questions.index'));
        $question->refresh();
        $this->assertCount(1, $question->bnccs);
        $this->assertTrue($question->bnccs->contains($bncc2));
        $this->assertFalse($question->bnccs->contains($bncc1));
        $this->assertCount(1, $question->subjects);
        $this->assertTrue($question->subjects->contains($subject));
    }

    public function test_question_relationships_can_be_cleared(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => Stage::EF]);
        $serie = Serie::create(['name' => '1st Grade', 'stage' => Stage::EF, 'order' => 1]);
        $bncc = Bncc::create([
            'code' => 'EF01MA01',
            'description' => 'BNCC 1',
            'stage' => Stage::EF,
            'discipline_id' => $discipline->id,
        ]);
        $bncc->series()->attach($serie->id);
        $topic = Topic::create(['name' => 'Algebra', 'discipline_id' => $discipline->id]);
        $chapter = Chapter::create(['name' => 'Linear Equations', 'topic_id' => $topic->id]);
        $subject = Subject::create(['name' => 'Subject 1', 'chapter_id' => $chapter->id]);

        $question = Question::create([
            'stem' => 'Test question',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);
        $question->bnccs()->attach($bncc->id);
        $question->subjects()->attach($subject->id);

        $data = [
            'stem' => 'Updated question',
            'stage' => 'EF',
            'type' => 'multiple_choice',
            'status' => 'draft',
        ];

        $response = $this->actingAs($this->user)->put(route('questions.update', $question), $data);
        
        $response->assertRedirect(route('questions.index'));
        $question->refresh();
        $this->assertCount(0, $question->bnccs);
        $this->assertCount(0, $question->subjects);
    }

    public function test_authenticated_user_can_delete_question(): void
    {
        $question = Question::create([
            'stem' => 'What is 2+2?',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);

        $response = $this->actingAs($this->user)->delete(route('questions.destroy', $question));
        
        $response->assertRedirect(route('questions.index'));
        $response->assertSessionHas('success', 'Question deleted successfully.');
        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }

    public function test_delete_requires_authentication(): void
    {
        $question = Question::create([
            'stem' => 'What is 2+2?',
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'status' => QuestionStatus::DRAFT,
        ]);
        $response = $this->delete(route('questions.destroy', $question));
        $response->assertRedirect(route('login'));
    }
}

