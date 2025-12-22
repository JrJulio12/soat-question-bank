<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/subjects.json');
        $json = File::get($jsonPath);
        $subjects = json_decode($json, true);

        foreach ($subjects as $subjectData) {
            $chapter = Chapter::where('name', $subjectData['chapter'])->first();

            if (!$chapter) {
                Log::warning("Chapter '{$subjectData['chapter']}' not found for subject '{$subjectData['name']}'. Skipping.");
                continue;
            }

            Subject::firstOrCreate(
                [
                    'name' => $subjectData['name'],
                    'chapter_id' => $chapter->id,
                ]
            );
        }
    }
}
