<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'access_level' => 'N3',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'operador@aethersens.internal'],
            [
                'name' => 'Operador NOC',
                'password' => Hash::make('operador123'),
                'access_level' => 'N2',
                'email_verified_at' => now(),
            ]
        );
    }
}

