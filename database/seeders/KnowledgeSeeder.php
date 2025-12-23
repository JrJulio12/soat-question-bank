<?php

namespace Database\Seeders;

use App\Models\Knowledge;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class KnowledgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/knowledges.json');
        $json = File::get($jsonPath);
        $knowledges = json_decode($json, true);

        foreach ($knowledges as $knowledgeData) {
            $unit = Unit::where('name', $knowledgeData['unit'])->first();

            if (!$unit) {
                Log::warning("Unit '{$knowledgeData['unit']}' not found for knowledge '{$knowledgeData['name']}'. Skipping.");
                continue;
            }

            Knowledge::firstOrCreate(
                [
                    'name' => $knowledgeData['name'],
                    'unit_id' => $unit->id,
                ]
            );
        }
    }
}


