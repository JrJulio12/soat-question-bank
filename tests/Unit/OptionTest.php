<?php

namespace Tests\Unit;

use App\Enums\QuestionStatus;
use App\Enums\QuestionType;
use App\Enums\Stage;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_option_can_be_created(): void
    {
        $question = Question::create([
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'stem' => 'Test question',
            'status' => QuestionStatus::DRAFT,
        ]);

        $option = Option::create([
            'question_id' => $question->id,
            'text' => 'Test option',
            'order' => 1,
            'is_correct' => true,
        ]);

        $this->assertDatabaseHas('options', [
            'id' => $option->id,
            'question_id' => $question->id,
            'text' => 'Test option',
            'order' => 1,
            'is_correct' => true,
        ]);
    }

    public function test_option_belongs_to_question(): void
    {
        $question = Question::create([
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'stem' => 'Test question',
            'status' => QuestionStatus::DRAFT,
        ]);

        $option = Option::create([
            'question_id' => $question->id,
            'text' => 'Test option',
            'order' => 1,
            'is_correct' => false,
        ]);

        $this->assertEquals($question->id, $option->question->id);
        $this->assertEquals($question->stem, $option->question->stem);
    }

    public function test_option_casts_work(): void
    {
        $question = Question::create([
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'stem' => 'Test question',
            'status' => QuestionStatus::DRAFT,
        ]);

        $option = Option::create([
            'question_id' => $question->id,
            'text' => 'Test option',
            'order' => 5,
            'is_correct' => true,
        ]);

        $this->assertIsInt($option->order);
        $this->assertIsBool($option->is_correct);
        $this->assertEquals(5, $option->order);
        $this->assertTrue($option->is_correct);
    }
}

