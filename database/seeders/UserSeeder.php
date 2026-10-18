<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::firstOrCreate(
            ['email' => 'owner@donjulio.com'],
            [
                'name' => 'Propietario Don Julio',
                'password' => Hash::make('password'),
                'current_branch_id' => 1,
                'email_verified_at' => now(),
            ]
        );
        $owner->assignRole('Propietario');

        $admin = User::firstOrCreate(
            ['email' => 'admin@donjulio.com'],
            [
                'name' => 'Administrador General',
                'password' => Hash::make('password'),
                'current_branch_id' => 1,
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Administrador');
    }
}
