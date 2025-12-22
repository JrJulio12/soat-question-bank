<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/chapters.json');
        $json = File::get($jsonPath);
        $chapters = json_decode($json, true);

        foreach ($chapters as $chapterData) {
            $topic = Topic::where('name', $chapterData['topic'])->first();

            if (!$topic) {
                Log::warning("Topic '{$chapterData['topic']}' not found for chapter '{$chapterData['name']}'. Skipping.");
                continue;
            }

            Chapter::firstOrCreate(
                [
                    'name' => $chapterData['name'],
                    'topic_id' => $topic->id,
                ]
            );
        }
    }
}
