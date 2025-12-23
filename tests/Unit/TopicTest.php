<?php

namespace Tests\Unit;

use App\Enums\Stage;
use App\Models\Discipline;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TopicTest extends TestCase
{
    use RefreshDatabase;

    public function test_topic_can_be_created(): void
    {
        $discipline = Discipline::create([
            'stage' => Stage::EF,
            'name' => 'Mathematics',
        ]);

        $topic = Topic::create([
            'name' => 'Algebra',
            'discipline_id' => $discipline->id,
        ]);

        $this->assertDatabaseHas('topics', [
            'id' => $topic->id,
            'name' => 'Algebra',
            'discipline_id' => $discipline->id,
        ]);
    }

    public function test_topic_belongs_to_discipline(): void
    {
        $discipline = Discipline::create([
            'stage' => Stage::EF,
            'name' => 'Mathematics',
        ]);

        $topic = Topic::create([
            'name' => 'Algebra',
            'discipline_id' => $discipline->id,
        ]);

        $this->assertEquals($discipline->id, $topic->discipline->id);
        $this->assertEquals($discipline->name, $topic->discipline->name);
    }
}


