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
        // Usuario administrador
        User::firstOrCreate(
            ['email' => 'admin@boleteria.com'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('password'),
                'rol'      => 'admin',
            ]
        );

        // Usuario visitante de prueba
        User::firstOrCreate(
            ['email' => 'visitante@boleteria.com'],
            [
                'name'     => 'Visitante',
                'password' => Hash::make('password'),
                'rol'      => 'visitante',
            ]
        );
    }
}
