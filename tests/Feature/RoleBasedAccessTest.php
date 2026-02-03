<?php

namespace Tests\Feature;

use App\Models\Discipline;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WithRoles;

class RoleBasedAccessTest extends TestCase
{
    use RefreshDatabase, WithRoles;

    protected User $admin;

    protected User $teacher;

    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        $this->teacher = User::factory()->create();
        $this->teacher->assignRole('teacher');
        $this->student = User::factory()->create();
        $this->student->assignRole('student');
    }

    public function test_student_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->student)->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_student_can_view_questions_index(): void
    {
        $response = $this->actingAs($this->student)->get(route('questions.index'));
        $response->assertStatus(200);
    }

    public function test_student_cannot_access_questions_create(): void
    {
        $response = $this->actingAs($this->student)->get(route('questions.create'));
        $response->assertStatus(403);
    }

    public function test_student_can_view_disciplines_index(): void
    {
        $response = $this->actingAs($this->student)->get(route('disciplines.index'));
        $response->assertStatus(200);
    }

    public function test_student_cannot_access_disciplines_create(): void
    {
        $response = $this->actingAs($this->student)->get(route('disciplines.create'));
        $response->assertStatus(403);
    }

    public function test_teacher_can_access_questions_create(): void
    {
        $response = $this->actingAs($this->teacher)->get(route('questions.create'));
        $response->assertStatus(200);
    }

    public function test_teacher_can_access_disciplines_create(): void
    {
        $response = $this->actingAs($this->teacher)->get(route('disciplines.create'));
        $response->assertStatus(200);
    }

    public function test_admin_can_access_questions_create(): void
    {
        $response = $this->actingAs($this->admin)->get(route('questions.create'));
        $response->assertStatus(200);
    }

    public function test_admin_can_access_disciplines_create(): void
    {
        $response = $this->actingAs($this->admin)->get(route('disciplines.create'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_discipline(): void
    {
        $data = ['name' => 'Mathematics', 'stage' => 'EF'];
        $response = $this->actingAs($this->admin)->post(route('disciplines.store'), $data);
        $response->assertRedirect(route('disciplines.index'));
        $this->assertDatabaseHas('disciplines', ['name' => 'Mathematics', 'stage' => 'EF']);
    }

    public function test_student_cannot_create_discipline(): void
    {
        $data = ['name' => 'Mathematics', 'stage' => 'EF'];
        $response = $this->actingAs($this->student)->post(route('disciplines.store'), $data);
        $response->assertStatus(403);
        $this->assertDatabaseMissing('disciplines', ['name' => 'Mathematics']);
    }

    public function test_student_can_view_discipline_show(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => \App\Enums\Stage::EF]);
        $response = $this->actingAs($this->student)->get(route('disciplines.show', $discipline));
        $response->assertStatus(200);
        $response->assertSee('Mathematics');
    }

    public function test_student_cannot_access_discipline_edit(): void
    {
        $discipline = Discipline::create(['name' => 'Mathematics', 'stage' => \App\Enums\Stage::EF]);
        $response = $this->actingAs($this->student)->get(route('disciplines.edit', $discipline));
        $response->assertStatus(403);
    }
}
