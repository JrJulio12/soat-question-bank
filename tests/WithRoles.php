<?php

namespace Tests;

use Database\Seeders\RolePermissionSeeder;

trait WithRoles
{
    protected function seedRolesAndPermissions(): void
    {
        $this->seed(RolePermissionSeeder::class);
    }
}
