<?php

namespace Tests\Unit;

use App\Enums\Stage;
use App\Models\Bncc;
use App\Models\Discipline;
use App\Models\Serie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SerieTest extends TestCase
{
    use RefreshDatabase;

    public function test_serie_can_be_created(): void
    {
        $serie = Serie::create([
            'stage' => Stage::EF,
            'name' => '1st Grade',
            'order' => 1,
        ]);

        $this->assertDatabaseHas('series', [
            'id' => $serie->id,
            'stage' => 'EF',
            'name' => '1st Grade',
            'order' => 1,
        ]);
    }

    public function test_serie_enum_casting_works(): void
    {
        $serie = Serie::create([
            'stage' => Stage::EM,
            'name' => '6th Grade',
            'order' => 6,
        ]);

        $this->assertInstanceOf(Stage::class, $serie->stage);
        $this->assertEquals(Stage::EM, $serie->stage);
        $this->assertIsInt($serie->order);
        $this->assertEquals(6, $serie->order);
    }

    public function test_serie_has_many_bnccs(): void
    {
        $serie = Serie::create(['stage' => Stage::EF, 'name' => '1st Grade', 'order' => 1]);
        $discipline = Discipline::create(['stage' => Stage::EF, 'name' => 'Math']);

        $unit = \App\Models\Unit::create(['discipline_id' => $discipline->id, 'name' => 'Numbers']);
        $knowledge = \App\Models\Knowledge::create(['unit_id' => $unit->id, 'name' => 'Operations']);

        $bncc1 = Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA01',
            'description' => 'First bncc',
            'discipline_id' => $discipline->id,
        ]);

        $bncc2 = Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA02',
            'description' => 'Second bncc',
            'discipline_id' => $discipline->id,
        ]);

        $bncc1->series()->attach($serie->id);
        $bncc1->knowledges()->attach($knowledge->id);
        $bncc2->series()->attach($serie->id);
        $bncc2->knowledges()->attach($knowledge->id);

        $this->assertCount(2, $serie->bnccs);
        $this->assertTrue($serie->bnccs->contains($bncc1));
        $this->assertTrue($serie->bnccs->contains($bncc2));
    }
}

