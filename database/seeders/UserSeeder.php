<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@uhta.edu.mx',
            'password' => Hash::make('admin123'),
            'role' => 'administrador',
        ]);
        
        // Crear maestro de ejemplo
        User::create([
            'name' => 'Juan Pérez',
            'email' => 'maestro@uhta.edu.mx',
            'password' => Hash::make('maestro123'),
            'role' => 'maestro',
        ]);
        
        // Crear alumno de ejemplo
        User::create([
            'name' => 'María González',
            'email' => 'alumno@uhta.edu.mx',
            'password' => Hash::make('alumno123'),
            'role' => 'alumno',
            'matricula' => 'A001',
        ]);
    }
}
