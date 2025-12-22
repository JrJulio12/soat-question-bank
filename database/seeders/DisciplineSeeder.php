<?php

namespace Database\Seeders;

use App\Enums\Stage;
use App\Models\Discipline;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DisciplineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/disciplines.json');
        $json = File::get($jsonPath);
        $disciplines = json_decode($json, true);

        foreach ($disciplines as $disciplineData) {
            $stage = null;
            if (!empty($disciplineData['stage'])) {
                $stage = Stage::from($disciplineData['stage']);
            }

            Discipline::firstOrCreate(
                [
                    'name' => $disciplineData['name'],
                    'stage' => $stage,
                ]
            );
        }
    }
}
