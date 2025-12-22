<?php

namespace Tests\Unit;

use App\Enums\QuestionStatus;
use App\Enums\QuestionType;
use App\Enums\Stage;
use App\Models\Chapter;
use App\Models\Discipline;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_subject_can_be_created(): void
    {
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

        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'name' => 'Solving Equations',
            'chapter_id' => $chapter->id,
        ]);
    }

    public function test_subject_belongs_to_chapter(): void
    {
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

        $this->assertEquals($chapter->id, $subject->chapter->id);
        $this->assertEquals($chapter->name, $subject->chapter->name);
    }

    public function test_subject_has_many_questions(): void
    {
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

        $question1 = Question::create([
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'stem' => 'First question',
            'status' => QuestionStatus::DRAFT,
        ]);

        $question2 = Question::create([
            'stage' => Stage::EF,
            'type' => QuestionType::TRUE_FALSE,
            'stem' => 'Second question',
            'status' => QuestionStatus::PUBLISHED,
        ]);

        $subject->questions()->attach([$question1->id, $question2->id]);

        $this->assertCount(2, $subject->questions);
        $this->assertTrue($subject->questions->contains($question1));
        $this->assertTrue($subject->questions->contains($question2));
    }

    public function test_subject_can_attach_and_detach_questions(): void
    {
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

        $question = Question::create([
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'stem' => 'Test question',
            'status' => QuestionStatus::DRAFT,
        ]);

        // Test attach
        $subject->questions()->attach($question->id);
        $this->assertTrue($subject->questions->contains($question));
        $this->assertDatabaseHas('question_subject', [
            'subject_id' => $subject->id,
            'question_id' => $question->id,
        ]);

        // Test detach
        $subject->questions()->detach($question->id);
        $subject->refresh();
        $this->assertFalse($subject->questions->contains($question));
        $this->assertDatabaseMissing('question_subject', [
            'subject_id' => $subject->id,
            'question_id' => $question->id,
        ]);
    }
}

