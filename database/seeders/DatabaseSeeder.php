<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            DisciplineSeeder::class,
            TopicSeeder::class,
            UnitSeeder::class,
            ChapterSeeder::class,
            KnowledgeSeeder::class,
            SubjectSeeder::class,
            BnccSeeder::class,
        ]);

        $this->seedUsers();
    }

    /**
     * Create test users per role and backfill existing users with no role.
     */
    private function seedUsers(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'email_verified_at' => now(), 'password' => bcrypt('password')]
        );
        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        $teacher = User::firstOrCreate(
            ['email' => 'teacher@example.com'],
            ['name' => 'Teacher User', 'email' => 'teacher@example.com', 'email_verified_at' => now(), 'password' => bcrypt('password')]
        );
        if (! $teacher->hasRole('teacher')) {
            $teacher->assignRole('teacher');
        }

        $student = User::firstOrCreate(
            ['email' => 'student@example.com'],
            ['name' => 'Student User', 'email' => 'student@example.com', 'email_verified_at' => now(), 'password' => bcrypt('password')]
        );
        if (! $student->hasRole('student')) {
            $student->assignRole('student');
        }

        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'email' => 'test@example.com', 'email_verified_at' => now(), 'password' => bcrypt('password')]
        );
        if (! $testUser->hasAnyRole(['admin', 'teacher', 'student'])) {
            $testUser->assignRole('student');
        }

        // Backfill: assign student role to any user with no roles
        User::all()->each(function (User $user) {
            if (! $user->hasAnyRole(['admin', 'teacher', 'student'])) {
                $user->assignRole('student');
            }
        });
    }
}
