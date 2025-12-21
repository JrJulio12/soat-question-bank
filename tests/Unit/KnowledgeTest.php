<?php

namespace Tests\Unit;

use App\Enums\Stage;
use App\Models\Bncc;
use App\Models\Discipline;
use App\Models\Knowledge;
use App\Models\Serie;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KnowledgeTest extends TestCase
{
    use RefreshDatabase;

    public function test_knowledge_can_be_created(): void
    {
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

        $this->assertDatabaseHas('knowledges', [
            'id' => $knowledge->id,
            'unit_id' => $unit->id,
            'name' => 'Basic Operations',
        ]);
    }

    public function test_knowledge_belongs_to_unit(): void
    {
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

        $this->assertEquals($unit->id, $knowledge->unit->id);
        $this->assertEquals($unit->name, $knowledge->unit->name);
    }

    public function test_knowledge_has_many_bnccs(): void
    {
        $serie = Serie::create(['stage' => Stage::EF, 'name' => '1st Grade', 'order' => 1]);
        $discipline = Discipline::create(['stage' => Stage::EF, 'name' => 'Math']);
        $unit = Unit::create(['discipline_id' => $discipline->id, 'name' => 'Numbers']);
        $knowledge = Knowledge::create(['unit_id' => $unit->id, 'name' => 'Operations']);

        $bncc1 = Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA01',
            'description' => 'First bncc',
            'serie_id' => $serie->id,
            'discipline_id' => $discipline->id,
            'knowledge_id' => $knowledge->id,
        ]);

        $bncc2 = Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA02',
            'description' => 'Second bncc',
            'serie_id' => $serie->id,
            'discipline_id' => $discipline->id,
            'knowledge_id' => $knowledge->id,
        ]);

        $this->assertCount(2, $knowledge->bnccs);
        $this->assertTrue($knowledge->bnccs->contains($bncc1));
        $this->assertTrue($knowledge->bnccs->contains($bncc2));
    }
}
