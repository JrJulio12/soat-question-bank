<?php

namespace Tests\Unit;

use App\Enums\Stage;
use App\Models\Chapter;
use App\Models\Discipline;
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
}

