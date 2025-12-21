<?php

namespace Tests\Unit;

use App\Enums\Stage;
use App\Models\Discipline;
use App\Models\Knowledge;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisciplineUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_discipline_can_be_created(): void
    {
        $discipline = Discipline::create([
            'stage' => Stage::EF,
            'name' => 'Mathematics',
        ]);

        $this->assertDatabaseHas('disciplines', [
            'id' => $discipline->id,
            'stage' => 'EF',
            'name' => 'Mathematics',
        ]);
    }

    public function test_unit_can_be_created(): void
    {
        $discipline = Discipline::create([
            'stage' => Stage::EF,
            'name' => 'Mathematics',
        ]);

        $unit = Unit::create([
            'discipline_id' => $discipline->id,
            'name' => 'Numbers',
        ]);

        $this->assertDatabaseHas('units', [
            'id' => $unit->id,
            'discipline_id' => $discipline->id,
            'name' => 'Numbers',
        ]);
    }

    public function test_discipline_has_many_units(): void
    {
        $discipline = Discipline::create([
            'stage' => Stage::EF,
            'name' => 'Mathematics',
        ]);

        $unit1 = Unit::create([
            'discipline_id' => $discipline->id,
            'name' => 'Numbers',
        ]);

        $unit2 = Unit::create([
            'discipline_id' => $discipline->id,
            'name' => 'Geometry',
        ]);

        $this->assertCount(2, $discipline->units);
        $this->assertTrue($discipline->units->contains($unit1));
        $this->assertTrue($discipline->units->contains($unit2));
    }

    public function test_unit_belongs_to_discipline(): void
    {
        $discipline = Discipline::create([
            'stage' => Stage::EF,
            'name' => 'Mathematics',
        ]);

        $unit = Unit::create([
            'discipline_id' => $discipline->id,
            'name' => 'Numbers',
        ]);

        $this->assertEquals($discipline->id, $unit->discipline->id);
        $this->assertEquals($discipline->name, $unit->discipline->name);
    }

    public function test_unit_has_many_knowledges(): void
    {
        $discipline = Discipline::create([
            'stage' => Stage::EF,
            'name' => 'Mathematics',
        ]);

        $unit = Unit::create([
            'discipline_id' => $discipline->id,
            'name' => 'Numbers',
        ]);

        $knowledge1 = Knowledge::create([
            'unit_id' => $unit->id,
            'name' => 'Basic Operations',
        ]);

        $knowledge2 = Knowledge::create([
            'unit_id' => $unit->id,
            'name' => 'Advanced Operations',
        ]);

        $this->assertCount(2, $unit->knowledges);
        $this->assertTrue($unit->knowledges->contains($knowledge1));
        $this->assertTrue($unit->knowledges->contains($knowledge2));
    }
}
