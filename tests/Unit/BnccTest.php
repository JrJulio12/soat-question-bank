<?php

namespace Tests\Unit;

use App\Enums\QuestionStatus;
use App\Enums\QuestionType;
use App\Enums\Stage;
use App\Models\Area;
use App\Models\Bncc;
use App\Models\Competence;
use App\Models\Discipline;
use App\Models\Knowledge;
use App\Models\Question;
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
            'discipline_id' => $discipline->id,
            'competence_id' => null,
        ]);

        $bncc->series()->attach($serie->id);
        $bncc->knowledges()->attach($knowledge->id);

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
            'discipline_id' => $discipline->id,
            'competence_id' => $competence->id,
        ]);

        $bncc->series()->attach($serie->id);

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
            'discipline_id' => $discipline->id,
        ]);

        $bncc->series()->attach($serie->id);
        $bncc->knowledges()->attach($knowledge->id);

        $this->assertTrue($bncc->series->contains($serie));
        $this->assertEquals($discipline->id, $bncc->discipline->id);
        $this->assertTrue($bncc->knowledges->contains($knowledge));
    }

    public function test_ef_stage_can_be_created_without_knowledge(): void
    {
        $discipline = Discipline::create(['stage' => Stage::EF, 'name' => 'Math']);

        $bncc = Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA01',
            'description' => 'Test',
            'discipline_id' => $discipline->id,
        ]);

        $this->assertDatabaseHas('bnccs', [
            'id' => $bncc->id,
            'code' => 'EF01MA01',
            'stage' => 'EF',
        ]);
    }

    public function test_ef_stage_rejects_competence_id(): void
    {
        $discipline = Discipline::create(['stage' => Stage::EF, 'name' => 'Math']);
        $area = Area::create(['name' => 'Area']);
        $competence = Competence::create(['area_id' => $area->id, 'code' => 'CODE', 'description' => 'Desc']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bncc with stage EF must not have a competence_id.');

        Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA01',
            'description' => 'Test',
            'discipline_id' => $discipline->id,
            'competence_id' => $competence->id,
        ]);
    }

    public function test_em_stage_requires_competence_id(): void
    {
        $discipline = Discipline::create(['stage' => Stage::EM, 'name' => 'Math']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Bncc with stage EM must have a competence_id.');

        Bncc::create([
            'stage' => Stage::EM,
            'code' => 'EM13MAT01',
            'description' => 'Test',
            'discipline_id' => $discipline->id,
            'competence_id' => null,
        ]);
    }


    public function test_direct_relationship_accessors_work(): void
    {
        // Test EF stage - units accessor
        $serie = Serie::create(['stage' => Stage::EF, 'name' => '1st Grade', 'order' => 1]);
        $discipline = Discipline::create(['stage' => Stage::EF, 'name' => 'Math']);

        $unit1 = Unit::create(['discipline_id' => $discipline->id, 'name' => 'Numbers']);
        $unit1 = Unit::where('id', $unit1->id)->first();

        $unit2 = Unit::create(['discipline_id' => $discipline->id, 'name' => 'Algebra']);
        $unit2 = Unit::where('id', $unit2->id)->first();

        $knowledge1 = Knowledge::create(['unit_id' => $unit1->id, 'name' => 'Operations']);
        $knowledge2 = Knowledge::create(['unit_id' => $unit2->id, 'name' => 'Equations']);

        $bncc = Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA01',
            'description' => 'Test',
            'discipline_id' => $discipline->id,
        ]);

        $bncc->series()->attach($serie->id);
        $bncc->knowledges()->attach([$knowledge1->id, $knowledge2->id]);

        $bncc->refresh();

        // Test units() method returns collection of all units from knowledges
        $units = $bncc->units();

        $this->assertCount(2, $units);
        $this->assertTrue($units->contains($unit1));
        $this->assertTrue($units->contains($unit2));

        // Test EM stage - area accessor
        $serie2 = Serie::create(['stage' => Stage::EM, 'name' => '6th Grade', 'order' => 6]);
        $discipline2 = Discipline::create(['stage' => Stage::EM, 'name' => 'Math']);
        $area2 = Area::create(['name' => 'Area']);
        $competence2 = Competence::create(['area_id' => $area2->id, 'code' => 'CODE', 'description' => 'Desc']);

        $bncc2 = Bncc::create([
            'stage' => Stage::EM,
            'code' => 'EM13MAT01',
            'description' => 'Test',
            'discipline_id' => $discipline2->id,
            'competence_id' => $competence2->id,
        ]);

        $bncc2->series()->attach($serie2->id);
        $bncc2->refresh();
        
        // Test property access (verifies HasOneThrough relationship works)
        $this->assertNotNull($bncc2->area);
        $this->assertEquals($area2->id, $bncc2->area->id);
        $this->assertEquals($area2->name, $bncc2->area->name);
    }

    public function test_bncc_has_many_questions(): void
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
            'description' => 'BNCC description',
            'discipline_id' => $discipline->id,
        ]);

        $bncc->series()->attach($serie->id);
        $bncc->knowledges()->attach($knowledge->id);

        $question1 = Question::create([
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'stem' => 'First question',
            'status' => QuestionStatus::DRAFT,
        ]);

        $question2 = Question::create([
            'stage' => Stage::EF,
            'type' => QuestionType::TRUE_FALSE,
            'stem' => 'Second question',
            'status' => QuestionStatus::PUBLISHED,
        ]);

        $bncc->questions()->attach([$question1->id, $question2->id]);

        $this->assertCount(2, $bncc->questions);
        $this->assertTrue($bncc->questions->contains($question1));
        $this->assertTrue($bncc->questions->contains($question2));
    }

    public function test_bncc_can_attach_and_detach_questions(): void
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
            'description' => 'BNCC description',
            'discipline_id' => $discipline->id,
        ]);

        $bncc->series()->attach($serie->id);
        $bncc->knowledges()->attach($knowledge->id);

        $question = Question::create([
            'stage' => Stage::EF,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'stem' => 'Test question',
            'status' => QuestionStatus::DRAFT,
        ]);

        // Test attach
        $bncc->questions()->attach($question->id);
        $this->assertTrue($bncc->questions->contains($question));
        $this->assertDatabaseHas('bncc_question', [
            'bncc_id' => $bncc->id,
            'question_id' => $question->id,
        ]);

        // Test detach
        $bncc->questions()->detach($question->id);
        $bncc->refresh();
        $this->assertFalse($bncc->questions->contains($question));
        $this->assertDatabaseMissing('bncc_question', [
            'bncc_id' => $bncc->id,
            'question_id' => $question->id,
        ]);
    }

    public function test_bncc_can_have_multiple_series(): void
    {
        $serie1 = Serie::create([
            'stage' => Stage::EF,
            'name' => '1st Grade',
            'order' => 1,
        ]);

        $serie2 = Serie::create([
            'stage' => Stage::EF,
            'name' => '2nd Grade',
            'order' => 2,
        ]);

        $discipline = Discipline::create([
            'stage' => Stage::EF,
            'name' => 'Mathematics',
        ]);

        $bncc = Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA01',
            'description' => 'BNCC description',
            'discipline_id' => $discipline->id,
        ]);

        $bncc->series()->attach([$serie1->id, $serie2->id]);

        $this->assertCount(2, $bncc->series);
        $this->assertTrue($bncc->series->contains($serie1));
        $this->assertTrue($bncc->series->contains($serie2));
    }

    public function test_bncc_can_have_multiple_knowledges(): void
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

        $bncc = Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA01',
            'description' => 'BNCC description',
            'discipline_id' => $discipline->id,
        ]);

        $bncc->knowledges()->attach([$knowledge1->id, $knowledge2->id]);

        $this->assertCount(2, $bncc->knowledges);
        $this->assertTrue($bncc->knowledges->contains($knowledge1));
        $this->assertTrue($bncc->knowledges->contains($knowledge2));
    }

    public function test_bncc_units_returns_all_units_from_knowledges(): void
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
            'name' => 'Algebra',
        ]);

        $knowledge1 = Knowledge::create([
            'unit_id' => $unit1->id,
            'name' => 'Basic Operations',
        ]);

        $knowledge2 = Knowledge::create([
            'unit_id' => $unit2->id,
            'name' => 'Equations',
        ]);

        $bncc = Bncc::create([
            'stage' => Stage::EF,
            'code' => 'EF01MA01',
            'description' => 'BNCC description',
            'discipline_id' => $discipline->id,
        ]);

        $bncc->knowledges()->attach([$knowledge1->id, $knowledge2->id]);

        $units = $bncc->units();

        $this->assertCount(2, $units);
        $this->assertTrue($units->contains('id', $unit1->id));
        $this->assertTrue($units->contains('id', $unit2->id));
        $this->assertEquals($unit1->name, $units->firstWhere('id', $unit1->id)->name);
        $this->assertEquals($unit2->name, $units->firstWhere('id', $unit2->id)->name);
    }
}
