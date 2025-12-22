<?php

namespace Database\Seeders;

use App\Enums\Stage;
use App\Models\Bncc;
use App\Models\Discipline;
use App\Models\Knowledge;
use App\Models\Serie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class BnccSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('seeders/data/bnccs-EF.json');
        $json = File::get($jsonPath);
        $bnccs = json_decode($json, true);

        foreach ($bnccs as $bnccData) {
            // Look up discipline
            $discipline = Discipline::where('name', $bnccData['discipline'])->first();

            if (!$discipline) {
                Log::warning("Discipline '{$bnccData['discipline']}' not found for BNCC '{$bnccData['name']}'. Skipping.");
                continue;
            }

            // Create or get BNCC
            $bncc = Bncc::firstOrCreate(
                [
                    'code' => $bnccData['name'],
                ],
                [
                    'stage' => Stage::from($bnccData['stage']),
                    'description' => $bnccData['description'] ?? '',
                    'discipline_id' => $discipline->id,
                ]
            );

            // Handle series (pipe-separated)
            if (!empty($bnccData['series'])) {
                $serieNames = explode('|', $bnccData['series']);
                $serieIds = [];

                foreach ($serieNames as $serieName) {
                    $serieName = trim($serieName);
                    $serie = $this->getOrCreateSerie($serieName, $bnccData['stage']);

                    if ($serie) {
                        $serieIds[] = $serie->id;
                    }
                }

                if (!empty($serieIds)) {
                    $bncc->series()->syncWithoutDetaching($serieIds);
                }
            }

            // Handle knowledges (pipe-separated or single)
            if (!empty($bnccData['knowledges'])) {
                $knowledgeNames = explode('|', $bnccData['knowledges']);
                $knowledgeIds = [];

                foreach ($knowledgeNames as $knowledgeName) {
                    $knowledgeName = trim($knowledgeName);
                    $knowledge = Knowledge::where('name', $knowledgeName)->first();

                    if ($knowledge) {
                        $knowledgeIds[] = $knowledge->id;
                    } else {
                        Log::warning("Knowledge '{$knowledgeName}' not found for BNCC '{$bnccData['name']}'. Skipping.");
                    }
                }

                if (!empty($knowledgeIds)) {
                    $bncc->knowledges()->syncWithoutDetaching($knowledgeIds);
                }
            }
        }
    }

    /**
     * Get or create a Serie record from a serie name.
     */
    private function getOrCreateSerie(string $serieName, string $stage): ?Serie
    {
        // Check if serie already exists
        $serie = Serie::where('name', $serieName)->first();

        if ($serie) {
            return $serie;
        }

        // Parse order from name (e.g., "1º Ano" -> 1, "6º Ano" -> 6)
        $order = $this->extractOrderFromSerieName($serieName);

        // Determine stage from name if not provided
        $serieStage = $this->extractStageFromSerieName($serieName, $stage);

        return Serie::create([
            'name' => $serieName,
            'stage' => $serieStage,
            'order' => $order,
        ]);
    }

    /**
     * Extract order number from serie name.
     */
    private function extractOrderFromSerieName(string $serieName): ?int
    {
        // Match patterns like "1º", "2º", "6º", etc.
        if (preg_match('/(\d+)º/', $serieName, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    /**
     * Extract stage from serie name or use provided stage.
     */
    private function extractStageFromSerieName(string $serieName, string $defaultStage): Stage
    {
        if (stripos($serieName, 'Ensino Médio') !== false) {
            return Stage::EM;
        }

        if (stripos($serieName, 'Ensino Fundamental') !== false) {
            return Stage::EF;
        }

        // Fall back to provided stage
        return Stage::from($defaultStage);
    }
}

