<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Create or update the full-access admin user (admin role has all permissions).
     */
    public function run(): void
    {
        $email = 'julioalves@sisttech.com.br';
        $password = 'Sisttech@2026';
        $name = 'Julio Alves';

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'email_verified_at' => now(),
            ]
        );

        if (! $user->hasRole('admin')) {
            $user->assignRole('admin');
        }
    }
}
