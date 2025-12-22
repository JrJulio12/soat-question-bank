<?php

namespace Database\Seeders;

use App\Models\Discipline;
use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/topics.json');
        $json = File::get($jsonPath);
        $topics = json_decode($json, true);

        foreach ($topics as $topicData) {
            $discipline = Discipline::where('name', $topicData['discipline'])->first();

            if (!$discipline) {
                Log::warning("Discipline '{$topicData['discipline']}' not found for topic '{$topicData['name']}'. Skipping.");
                continue;
            }

            Topic::firstOrCreate(
                [
                    'name' => $topicData['name'],
                    'discipline_id' => $discipline->id,
                ]
            );
        }
    }
}
