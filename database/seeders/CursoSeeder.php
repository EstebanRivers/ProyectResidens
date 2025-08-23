<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\User;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        $maestros = User::where('role', 'maestro')->get();
        
        $cursos = [
            [
                'nombre' => 'Matemáticas I',
                'descripcion' => 'Curso básico de matemáticas',
                'codigo' => 'MAT101',
                'creditos' => 4,
                'periodo_academico' => '2025-1'
            ],
            [
                'nombre' => 'Física General',
                'descripcion' => 'Introducción a la física',
                'codigo' => 'FIS101',
                'creditos' => 4,
                'periodo_academico' => '2025-1'
            ],
            [
                'nombre' => 'Química Básica',
                'descripcion' => 'Fundamentos de química',
                'codigo' => 'QUI101',
                'creditos' => 3,
                'periodo_academico' => '2025-1'
            ],
            [
                'nombre' => 'Programación I',
                'descripcion' => 'Introducción a la programación',
                'codigo' => 'PRG101',
                'creditos' => 4,
                'periodo_academico' => '2025-1'
            ]
        ];

        foreach ($cursos as $cursoData) {
            $cursoData['maestro_id'] = $maestros->random()->id;
            Curso::create($cursoData);
        }
    }
}