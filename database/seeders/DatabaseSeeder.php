<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Curso;
use App\Models\Contenido;
use App\Models\Actividad;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuarios de prueba
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@uhta.edu',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $maestro = User::create([
            'name' => 'Prof. Juan Pérez',
            'email' => 'maestro@uhta.edu',
            'password' => Hash::make('password'),
            'role' => 'maestro',
            'biografia' => 'Profesor con 10 años de experiencia en programación.',
        ]);

        $estudiante = User::create([
            'name' => 'María González',
            'email' => 'estudiante@uhta.edu',
            'password' => Hash::make('password'),
            'role' => 'estudiante',
        ]);

        // Crear curso de prueba
        $curso = Curso::create([
            'titulo' => 'Introducción a la Programación',
            'descripcion' => 'Aprende los fundamentos de la programación con ejemplos prácticos.',
            'maestro_id' => $maestro->id,
            'precio' => 99.99,
            'duracion_horas' => 40,
            'nivel' => 'principiante',
        ]);

        // Inscribir estudiante al curso
        $curso->estudiantes()->attach($estudiante->id, [
            'fecha_inscripcion' => now(),
        ]);

        // Crear contenidos
        $contenidos = [
            [
                'titulo' => 'Introducción al Curso',
                'descripcion' => 'Bienvenida y objetivos del curso',
                'tipo' => 'video',
                'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'duracion_minutos' => 15,
                'orden' => 1,
            ],
            [
                'titulo' => 'Conceptos Básicos',
                'descripcion' => 'Variables, tipos de datos y operadores',
                'tipo' => 'texto',
                'contenido' => '<h2>Variables en Programación</h2><p>Una variable es un espacio en memoria que almacena un valor...</p>',
                'duracion_minutos' => 30,
                'orden' => 2,
            ],
            [
                'titulo' => 'Manual de Referencia',
                'descripcion' => 'Guía completa de sintaxis',
                'tipo' => 'pdf',
                'archivo' => 'manual-programacion.pdf',
                'duracion_minutos' => 45,
                'orden' => 3,
            ],
        ];

        foreach ($contenidos as $contenidoData) {
            $contenidoData['curso_id'] = $curso->id;
            Contenido::create($contenidoData);
        }

        // Crear actividades
        $actividades = [
            [
                'titulo' => 'Quiz: Variables y Tipos de Datos',
                'descripcion' => 'Evalúa tu conocimiento sobre variables',
                'tipo' => 'opcion_multiple',
                'pregunta' => [
                    'texto' => '¿Cuál es el tipo de dato correcto para almacenar un número entero?',
                    'opciones' => [
                        'A' => 'string',
                        'B' => 'int',
                        'C' => 'float',
                        'D' => 'boolean'
                    ]
                ],
                'respuesta_correcta' => ['B'],
                'puntos' => 10,
                'orden' => 1,
            ],
            [
                'titulo' => 'Verdadero o Falso: Operadores',
                'descripcion' => 'Verifica tu comprensión de operadores',
                'tipo' => 'verdadero_falso',
                'pregunta' => [
                    'texto' => 'El operador == compara tanto el valor como el tipo de dato'
                ],
                'respuesta_correcta' => [false],
                'puntos' => 5,
                'orden' => 2,
            ],
            [
                'titulo' => 'Pregunta Abierta: Algoritmos',
                'descripcion' => 'Explica con tus propias palabras',
                'tipo' => 'respuesta_corta',
                'pregunta' => [
                    'texto' => '¿Qué es un algoritmo en programación?'
                ],
                'respuesta_correcta' => ['algoritmo', 'secuencia', 'pasos', 'instrucciones'],
                'puntos' => 15,
                'orden' => 3,
            ],
        ];

        foreach ($actividades as $actividadData) {
            $actividadData['curso_id'] = $curso->id;
            Actividad::create($actividadData);
        }
    }
}