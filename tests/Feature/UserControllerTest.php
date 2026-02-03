<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WithRoles;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithRoles;

    protected User $admin;

    protected User $otherUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedRolesAndPermissions();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        $this->otherUser = User::factory()->create(['email' => 'other@example.com']);
        $this->otherUser->assignRole('teacher');
    }

    public function test_users_index_requires_authentication(): void
    {
        $response = $this->get(route('users.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_admin_can_view_users_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    public function test_users_index_displays_users(): void
    {
        $response = $this->actingAs($this->admin)->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertSee($this->admin->name);
        $response->assertSee($this->admin->email);
        $response->assertSee($this->otherUser->name);
        $response->assertSee($this->otherUser->email);
    }

    public function test_user_without_view_users_permission_cannot_access_index(): void
    {
        $student = User::factory()->create();
        $student->assignRole('student');

        $response = $this->actingAs($student)->get(route('users.index'));
        $response->assertStatus(403);
    }

    public function test_create_form_requires_authentication(): void
    {
        $response = $this->get(route('users.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_create_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('users.create'));
        $response->assertStatus(200);
        $response->assertViewIs('users.create');
        $response->assertSee('admin');
        $response->assertSee('teacher');
        $response->assertSee('student');
    }

    public function test_user_without_manage_users_permission_cannot_access_create(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)->get(route('users.create'));
        $response->assertStatus(403);
    }

    public function test_admin_can_store_user_with_role(): void
    {
        $data = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'student',
        ];

        $response = $this->actingAs($this->admin)->post(route('users.store'), $data);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User created successfully.');
        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
        ]);
        $user = User::where('email', 'newuser@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('student'));
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('users.store'), []);
        $response->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    }

    public function test_store_validates_email_unique(): void
    {
        $data = [
            'name' => 'Duplicate',
            'email' => $this->admin->email,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'student',
        ];
        $response = $this->actingAs($this->admin)->post(route('users.store'), $data);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_store_validates_role_in_list(): void
    {
        $data = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'superadmin',
        ];
        $response = $this->actingAs($this->admin)->post(route('users.store'), $data);
        $response->assertSessionHasErrors(['role']);
    }

    public function test_show_requires_authentication(): void
    {
        $response = $this->get(route('users.show', $this->otherUser));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_view_user_show(): void
    {
        $response = $this->actingAs($this->admin)->get(route('users.show', $this->otherUser));
        $response->assertStatus(200);
        $response->assertViewIs('users.show');
        $response->assertSee($this->otherUser->name);
        $response->assertSee($this->otherUser->email);
    }

    public function test_edit_form_requires_authentication(): void
    {
        $response = $this->get(route('users.edit', $this->otherUser));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_edit_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('users.edit', $this->otherUser));
        $response->assertStatus(200);
        $response->assertViewIs('users.edit');
        $response->assertSee($this->otherUser->name);
        $response->assertSee($this->otherUser->email);
    }

    public function test_user_without_manage_users_permission_cannot_access_edit(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)->get(route('users.edit', $this->otherUser));
        $response->assertStatus(403);
    }

    public function test_admin_can_update_user(): void
    {
        $data = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'role' => 'admin',
        ];

        $response = $this->actingAs($this->admin)->put(route('users.update', $this->otherUser), $data);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User updated successfully.');
        $this->otherUser->refresh();
        $this->assertSame('Updated Name', $this->otherUser->name);
        $this->assertSame('updated@example.com', $this->otherUser->email);
        $this->assertTrue($this->otherUser->hasRole('admin'));
    }

    public function test_user_without_manage_users_permission_cannot_update(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $data = [
            'name' => 'Hacked',
            'email' => $this->otherUser->email,
            'role' => 'admin',
        ];
        $response = $this->actingAs($teacher)->put(route('users.update', $this->otherUser), $data);
        $response->assertStatus(403);
        $this->otherUser->refresh();
        $this->assertNotSame('Hacked', $this->otherUser->name);
    }

    public function test_destroy_returns_403_when_deleting_self(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('users.destroy', $this->admin));
        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_admin_can_destroy_other_user(): void
    {
        $userId = $this->otherUser->id;
        $response = $this->actingAs($this->admin)->delete(route('users.destroy', $this->otherUser));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User deleted successfully.');
        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    public function test_user_without_manage_users_permission_cannot_destroy(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)->delete(route('users.destroy', $this->otherUser));
        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $this->otherUser->id]);
    }

    public function test_destroy_requires_authentication(): void
    {
        $response = $this->delete(route('users.destroy', $this->otherUser));
        $response->assertRedirect(route('login'));
    }
}
