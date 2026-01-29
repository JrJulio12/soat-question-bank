<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guard = config('auth.defaults.guard', 'web');

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $bnccResources = [
            'disciplines',
            'units',
            'knowledges',
            'topics',
            'chapters',
            'subjects',
            'series',
            'bnccs',
        ];

        $permissions = [];

        foreach ($bnccResources as $resource) {
            $permissions[] = Permission::firstOrCreate(['name' => "view {$resource}", 'guard_name' => $guard]);
            $permissions[] = Permission::firstOrCreate(['name' => "manage {$resource}", 'guard_name' => $guard]);
        }

        $permissions[] = Permission::firstOrCreate(['name' => 'view questions', 'guard_name' => $guard]);
        $permissions[] = Permission::firstOrCreate(['name' => 'manage questions', 'guard_name' => $guard]);

        $allPermissions = Permission::where('guard_name', $guard)->pluck('name')->toArray();

        $viewPermissions = array_values(array_filter($allPermissions, fn (string $name) => str_starts_with($name, 'view ')));

        // Admin: all permissions
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
        $admin->syncPermissions($allPermissions);

        // Teacher: all view + manage (BNCC + questions)
        $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => $guard]);
        $teacher->syncPermissions($allPermissions);

        // Student: only view permissions
        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => $guard]);
        $student->syncPermissions($viewPermissions);
    }
}
