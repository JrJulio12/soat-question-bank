<?php

namespace Tests\Unit;

use App\Enums\QuestionStatus;
use App\Enums\QuestionType;
use App\Enums\Stage;
use App\Models\Option;
use App\Models\Question;
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
}
