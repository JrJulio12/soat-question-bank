<?php

namespace Tests\Unit;

use App\Enums\Stage;
use App\Models\Chapter;
use App\Models\Discipline;
use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChapterTest extends TestCase
{
    use RefreshDatabase;

    public function test_chapter_can_be_created(): void
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

        $this->assertDatabaseHas('chapters', [
            'id' => $chapter->id,
            'name' => 'Linear Equations',
            'topic_id' => $topic->id,
        ]);
    }

    public function test_chapter_belongs_to_topic(): void
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

        $this->assertEquals($topic->id, $chapter->topic->id);
        $this->assertEquals($topic->name, $chapter->topic->name);
    }
}

