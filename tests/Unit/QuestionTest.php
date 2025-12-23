<?php

namespace Tests\Unit;

use App\Enums\QuestionStatus;
use App\Enums\QuestionType;
use App\Enums\Stage;
use App\Models\Bncc;
use App\Models\Chapter;
use App\Models\Discipline;
use App\Models\Knowledge;
use App\Models\Option;
use App\Models\Question;
use App\Models\Serie;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    public function test_question_can_be_created(): void
    {
        $question = Question::create([
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'stem' => 'What is 2+2?',
            'status' => QuestionStatus::DRAFT,
        ]);

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'stage' => 'EF',
            'type' => 'multiple_choice',
            'status' => 'draft',
        ]);
    }

    public function test_question_enum_casting_works(): void
    {
        $question = Question::create([
            'stage' => Stage::EM,
            'type' => QuestionType::TRUE_FALSE,
            'stem' => 'Test question',
            'status' => QuestionStatus::PUBLISHED,
        ]);

        $this->assertInstanceOf(Stage::class, $question->stage);
        $this->assertInstanceOf(QuestionType::class, $question->type);
        $this->assertInstanceOf(QuestionStatus::class, $question->status);
        $this->assertEquals(Stage::EM, $question->stage);
        $this->assertEquals(QuestionType::TRUE_FALSE, $question->type);
        $this->assertEquals(QuestionStatus::PUBLISHED, $question->status);
    }

    public function test_question_has_many_options(): void
    {
        $question = Question::create([
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'stem' => 'Test question',
            'status' => QuestionStatus::DRAFT,
        ]);

        $option1 = Option::create([
            'question_id' => $question->id,
            'text' => 'Option 1',
            'order' => 1,
            'is_correct' => true,
        ]);

        $option2 = Option::create([
            'question_id' => $question->id,
            'text' => 'Option 2',
            'order' => 2,
            'is_correct' => false,
        ]);

        $this->assertCount(2, $question->options);
        $this->assertTrue($question->options->contains($option1));
        $this->assertTrue($question->options->contains($option2));
    }

    public function test_question_has_many_bnccs(): void
    {
        $question = Question::create([
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'stem' => 'Test question',
            'status' => QuestionStatus::DRAFT,
        ]);

        $serie = Serie::create([
            'stage' => Stage::EF,
            'name' => '1st Grade',
            'order' => 1,
        ]);

        $discipline = Discipline::create([
            'stage' => Stage::EF,
            'name' => 'Mathematics',
        ]);

        $unit = Unit::create([
            'discipline_id' => $discipline->id,
            'name' => 'Numbers',
        ]);

        $knowledge1 = Knowledge::create([
            'unit_id' => $unit->id,
            'name' => 'Basic Operations',
        ]);

        $knowledge2 = Knowledge::create([
            'unit_id' => $unit->id,
            'name' => 'Advanced Operations',
        ]);

        $bncc1 = Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA01',
            'description' => 'First BNCC description',
            'discipline_id' => $discipline->id,
        ]);

        $bncc2 = Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA02',
            'description' => 'Second BNCC description',
            'discipline_id' => $discipline->id,
        ]);

        $bncc1->series()->attach($serie->id);
        $bncc1->knowledges()->attach($knowledge1->id);
        $bncc2->series()->attach($serie->id);
        $bncc2->knowledges()->attach($knowledge2->id);

        $question->bnccs()->attach([$bncc1->id, $bncc2->id]);

        $this->assertCount(2, $question->bnccs);
        $this->assertTrue($question->bnccs->contains($bncc1));
        $this->assertTrue($question->bnccs->contains($bncc2));
    }

    public function test_question_can_attach_and_detach_bnccs(): void
    {
        $question = Question::create([
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'stem' => 'Test question',
            'status' => QuestionStatus::DRAFT,
        ]);

        $serie = Serie::create([
            'stage' => Stage::EF,
            'name' => '1st Grade',
            'order' => 1,
        ]);

        $discipline = Discipline::create([
            'stage' => Stage::EF,
            'name' => 'Mathematics',
        ]);

        $unit = Unit::create([
            'discipline_id' => $discipline->id,
            'name' => 'Numbers',
        ]);

        $knowledge = Knowledge::create([
            'unit_id' => $unit->id,
            'name' => 'Basic Operations',
        ]);

        $bncc = Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA01',
            'description' => 'BNCC description',
            'discipline_id' => $discipline->id,
        ]);

        $bncc->series()->attach($serie->id);
        $bncc->knowledges()->attach($knowledge->id);

        // Test attach
        $question->bnccs()->attach($bncc->id);
        $this->assertTrue($question->bnccs->contains($bncc));
        $this->assertDatabaseHas('bncc_question', [
            'question_id' => $question->id,
            'bncc_id' => $bncc->id,
        ]);

        // Test detach
        $question->bnccs()->detach($bncc->id);
        $question->refresh();
        $this->assertFalse($question->bnccs->contains($bncc));
        $this->assertDatabaseMissing('bncc_question', [
            'question_id' => $question->id,
            'bncc_id' => $bncc->id,
        ]);
    }

    public function test_question_has_many_subjects(): void
    {
        $question = Question::create([
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'stem' => 'Test question',
            'status' => QuestionStatus::DRAFT,
        ]);

        $discipline = Discipline::create([
            'stage' => Stage::EF,
            'name' => 'Mathematics',
        ]);

        $topic = Topic::create([
            'name' => 'Algebra',
            'discipline_id' => $discipline->id,
        ]);

        $chapter = Chapter::create([
            'name' => 'Linear Equations',
            'topic_id' => $topic->id,
        ]);

        $subject1 = Subject::create([
            'name' => 'Solving Equations',
            'chapter_id' => $chapter->id,
        ]);

        $subject2 = Subject::create([
            'name' => 'Graphing Linear Equations',
            'chapter_id' => $chapter->id,
        ]);

        $question->subjects()->attach([$subject1->id, $subject2->id]);

        $this->assertCount(2, $question->subjects);
        $this->assertTrue($question->subjects->contains($subject1));
        $this->assertTrue($question->subjects->contains($subject2));
    }

    public function test_question_can_attach_and_detach_subjects(): void
    {
        $question = Question::create([
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'stem' => 'Test question',
            'status' => QuestionStatus::DRAFT,
        ]);

        $discipline = Discipline::create([
            'stage' => Stage::EF,
            'name' => 'Mathematics',
        ]);

        $topic = Topic::create([
            'name' => 'Algebra',
            'discipline_id' => $discipline->id,
        ]);

        $chapter = Chapter::create([
            'name' => 'Linear Equations',
            'topic_id' => $topic->id,
        ]);

        $subject = Subject::create([
            'name' => 'Solving Equations',
            'chapter_id' => $chapter->id,
        ]);

        // Test attach
        $question->subjects()->attach($subject->id);
        $this->assertTrue($question->subjects->contains($subject));
        $this->assertDatabaseHas('question_subject', [
            'question_id' => $question->id,
            'subject_id' => $subject->id,
        ]);

        // Test detach
        $question->subjects()->detach($subject->id);
        $question->refresh();
        $this->assertFalse($question->subjects->contains($subject));
        $this->assertDatabaseMissing('question_subject', [
            'question_id' => $question->id,
            'subject_id' => $subject->id,
        ]);
    }
}

