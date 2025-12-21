<?php

namespace Tests\Unit;

use App\Enums\Stage;
use App\Models\Area;
use App\Models\Bncc;
use App\Models\Competence;
use App\Models\Discipline;
use App\Models\Knowledge;
use App\Models\Serie;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class BnccTest extends TestCase
{
    use RefreshDatabase;

    public function test_bncc_can_be_created_with_ef_stage(): void
    {
        $serie = Serie::create([
            'stage' => Stage::EF,
            'name' => '1st Grade',
            'order' => 1,
        ]);

        $discipline = Discipline::create([
            'stage' => Stage::EF,
            'name' => 'Mathematics',
        ]);

        $unit = Unit::create([
            'discipline_id' => $discipline->id,
            'name' => 'Numbers',
        ]);

        $knowledge = Knowledge::create([
            'unit_id' => $unit->id,
            'name' => 'Basic Operations',
        ]);

        $bncc = Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA01',
            'description' => 'Test description',
            'serie_id' => $serie->id,
            'discipline_id' => $discipline->id,
            'knowledge_id' => $knowledge->id,
            'competence_id' => null,
        ]);

        $this->assertDatabaseHas('bnccs', [
            'id' => $bncc->id,
            'code' => 'EF01MA01',
            'stage' => 'EF',
        ]);
    }

    public function test_bncc_can_be_created_with_em_stage(): void
    {
        $serie = Serie::create([
            'stage' => Stage::EM,
            'name' => '6th Grade',
            'order' => 6,
        ]);

        $discipline = Discipline::create([
            'stage' => Stage::EM,
            'name' => 'Mathematics',
        ]);

        $area = Area::create([
            'name' => 'Numbers and Operations',
        ]);

        $competence = Competence::create([
            'area_id' => $area->id,
            'code' => 'EM13MAT01',
            'description' => 'Competence description',
        ]);

        $bncc = Bncc::create([
            'stage' => Stage::EM,
            'code' => 'EM13MAT01',
            'description' => 'Test description',
            'serie_id' => $serie->id,
            'discipline_id' => $discipline->id,
            'knowledge_id' => null,
            'competence_id' => $competence->id,
        ]);

        $this->assertDatabaseHas('bnccs', [
            'id' => $bncc->id,
            'code' => 'EM13MAT01',
            'stage' => 'EM',
        ]);
    }

    public function test_bncc_relationships_work(): void
    {
        $serie = Serie::create(['stage' => Stage::EF, 'name' => '1st Grade', 'order' => 1]);
        $discipline = Discipline::create(['stage' => Stage::EF, 'name' => 'Math']);
        $unit = Unit::create(['discipline_id' => $discipline->id, 'name' => 'Numbers']);
        $knowledge = Knowledge::create(['unit_id' => $unit->id, 'name' => 'Operations']);

        $bncc = Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA01',
            'description' => 'Test',
            'serie_id' => $serie->id,
            'discipline_id' => $discipline->id,
            'knowledge_id' => $knowledge->id,
        ]);

        $this->assertEquals($serie->id, $bncc->serie->id);
        $this->assertEquals($discipline->id, $bncc->discipline->id);
        $this->assertEquals($knowledge->id, $bncc->knowledge->id);
    }

    public function test_ef_stage_requires_knowledge_id(): void
    {
        $serie = Serie::create(['stage' => Stage::EF, 'name' => '1st Grade', 'order' => 1]);
        $discipline = Discipline::create(['stage' => Stage::EF, 'name' => 'Math']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bncc with stage EF must have a knowledge_id.');

        Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA01',
            'description' => 'Test',
            'serie_id' => $serie->id,
            'discipline_id' => $discipline->id,
            'knowledge_id' => null,
        ]);
    }

    public function test_ef_stage_rejects_competence_id(): void
    {
        $serie = Serie::create(['stage' => Stage::EF, 'name' => '1st Grade', 'order' => 1]);
        $discipline = Discipline::create(['stage' => Stage::EF, 'name' => 'Math']);
        $unit = Unit::create(['discipline_id' => $discipline->id, 'name' => 'Numbers']);
        $knowledge = Knowledge::create(['unit_id' => $unit->id, 'name' => 'Operations']);
        $area = Area::create(['name' => 'Area']);
        $competence = Competence::create(['area_id' => $area->id, 'code' => 'CODE', 'description' => 'Desc']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bncc with stage EF must not have a competence_id.');

        Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA01',
            'description' => 'Test',
            'serie_id' => $serie->id,
            'discipline_id' => $discipline->id,
            'knowledge_id' => $knowledge->id,
            'competence_id' => $competence->id,
        ]);
    }

    public function test_em_stage_requires_competence_id(): void
    {
        $serie = Serie::create(['stage' => Stage::EM, 'name' => '6th Grade', 'order' => 6]);
        $discipline = Discipline::create(['stage' => Stage::EM, 'name' => 'Math']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bncc with stage EM must have a competence_id.');

        Bncc::create([
            'stage' => Stage::EM,
            'code' => 'EM13MAT01',
            'description' => 'Test',
            'serie_id' => $serie->id,
            'discipline_id' => $discipline->id,
            'competence_id' => null,
        ]);
    }

    public function test_em_stage_rejects_knowledge_id(): void
    {
        $serie = Serie::create(['stage' => Stage::EM, 'name' => '6th Grade', 'order' => 6]);
        $discipline = Discipline::create(['stage' => Stage::EM, 'name' => 'Math']);
        $area = Area::create(['name' => 'Area']);
        $competence = Competence::create(['area_id' => $area->id, 'code' => 'CODE', 'description' => 'Desc']);
        $unit = Unit::create(['discipline_id' => $discipline->id, 'name' => 'Numbers']);
        $knowledge = Knowledge::create(['unit_id' => $unit->id, 'name' => 'Operations']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bncc with stage EM must not have a knowledge_id.');

        Bncc::create([
            'stage' => Stage::EM,
            'code' => 'EM13MAT01',
            'description' => 'Test',
            'serie_id' => $serie->id,
            'discipline_id' => $discipline->id,
            'competence_id' => $competence->id,
            'knowledge_id' => $knowledge->id,
        ]);
    }

    public function test_direct_relationship_accessors_work(): void
    {
        // Test EF stage - unit accessor
        $serie = Serie::create(['stage' => Stage::EF, 'name' => '1st Grade', 'order' => 1]);
        $discipline = Discipline::create(['stage' => Stage::EF, 'name' => 'Math']);
        $unit = Unit::create(['discipline_id' => $discipline->id, 'name' => 'Numbers']);
        $knowledge = Knowledge::create(['unit_id' => $unit->id, 'name' => 'Operations']);

        $bncc = Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA01',
            'description' => 'Test',
            'serie_id' => $serie->id,
            'discipline_id' => $discipline->id,
            'knowledge_id' => $knowledge->id,
        ]);

        $bncc->refresh();
        $this->assertNotNull($bncc->unit());
        $this->assertEquals($unit->id, $bncc->unit()->id);

        // Test EM stage - area accessor
        $serie2 = Serie::create(['stage' => Stage::EM, 'name' => '6th Grade', 'order' => 6]);
        $discipline2 = Discipline::create(['stage' => Stage::EM, 'name' => 'Math']);
        $area2 = Area::create(['name' => 'Area']);
        $competence2 = Competence::create(['area_id' => $area2->id, 'code' => 'CODE', 'description' => 'Desc']);

        $bncc2 = Bncc::create([
            'stage' => Stage::EM,
            'code' => 'EM13MAT01',
            'description' => 'Test',
            'serie_id' => $serie2->id,
            'discipline_id' => $discipline2->id,
            'competence_id' => $competence2->id,
        ]);

        $bncc2->refresh();
        $this->assertNotNull($bncc2->area());
        $this->assertEquals($area2->id, $bncc2->area()->id);
    }
}
