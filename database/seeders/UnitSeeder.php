<?php

namespace Database\Seeders;

use App\Models\Discipline;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/units.json');
        $json = File::get($jsonPath);
        $units = json_decode($json, true);

        foreach ($units as $unitData) {
            $discipline = Discipline::where('name', $unitData['discipline'])->first();

            if (!$discipline) {
                Log::warning("Discipline '{$unitData['discipline']}' not found for unit '{$unitData['name']}'. Skipping.");
                continue;
            }

            Unit::firstOrCreate(
                [
                    'name' => $unitData['name'],
                    'discipline_id' => $discipline->id,
                ]
            );
        }
    }
}

