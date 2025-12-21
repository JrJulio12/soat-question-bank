<?php

namespace Tests\Unit;

use App\Models\Area;
use App\Models\Competence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AreaCompetenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_area_can_be_created(): void
    {
        $area = Area::create([
            'name' => 'Numbers and Operations',
        ]);

        $this->assertDatabaseHas('areas', [
            'id' => $area->id,
            'name' => 'Numbers and Operations',
        ]);
    }

    public function test_competence_can_be_created(): void
    {
        $area = Area::create([
            'name' => 'Numbers and Operations',
        ]);

        $competence = Competence::create([
            'area_id' => $area->id,
            'code' => 'EM13MAT01',
            'description' => 'Competence description',
        ]);

        $this->assertDatabaseHas('competences', [
            'id' => $competence->id,
            'area_id' => $area->id,
            'code' => 'EM13MAT01',
        ]);
    }

    public function test_area_has_many_competences(): void
    {
        $area = Area::create([
            'name' => 'Numbers and Operations',
        ]);

        $competence1 = Competence::create([
            'area_id' => $area->id,
            'code' => 'EM13MAT01',
            'description' => 'First competence',
        ]);

        $competence2 = Competence::create([
            'area_id' => $area->id,
            'code' => 'EM13MAT02',
            'description' => 'Second competence',
        ]);

        $this->assertCount(2, $area->competences);
        $this->assertTrue($area->competences->contains($competence1));
        $this->assertTrue($area->competences->contains($competence2));
    }

    public function test_competence_belongs_to_area(): void
    {
        $area = Area::create([
            'name' => 'Numbers and Operations',
        ]);

        $competence = Competence::create([
            'area_id' => $area->id,
            'code' => 'EM13MAT01',
            'description' => 'Competence description',
        ]);

        $this->assertEquals($area->id, $competence->area->id);
        $this->assertEquals($area->name, $competence->area->name);
    }
}
