<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;
use App\Models\User;
use App\Models\Contenido;
use App\Models\Actividad;

class CursoSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener usuarios
        $admin = User::where('role', 'administrador')->first();
        $maestro = User::where('role', 'maestro')->first();
        $alumno = User::where('role', 'alumno')->first();

        if (!$maestro) {
            $maestro = User::create([
                'name' => 'Dr. Juan Pérez',
                'email' => 'juan.perez@uhta.edu.mx',
                'password' => bcrypt('password'),
                'role' => 'maestro'
            ]);
        }

        // Crear cursos con contenido completo
        $cursos = [
            [
                'nombre' => 'Matemáticas I',
                'codigo' => 'MAT101',
                'descripcion' => 'Curso fundamental de matemáticas que cubre álgebra, geometría y trigonometría básica. Incluye ejercicios prácticos y evaluaciones continuas.',
                'creditos' => 4,
                'cupo_maximo' => 30,
                'periodo_academico' => '2025-1',
                'maestro_id' => $maestro->id,
                'lecciones' => [
                    [
                        'titulo' => 'Introducción al Álgebra',
                        'descripcion' => 'Conceptos básicos de álgebra y operaciones fundamentales',
                        'contenido_texto' => 'El álgebra es una rama de las matemáticas que utiliza símbolos y letras para representar números y cantidades en fórmulas y ecuaciones. En esta lección aprenderemos los conceptos fundamentales que nos permitirán resolver problemas matemáticos de manera sistemática.',
                        'pregunta' => '¿Cuál es el resultado de 2x + 3 = 11?',
                        'opciones' => ['x = 4', 'x = 5', 'x = 6', 'x = 7'],
                        'respuesta_correcta' => 0
                    ],
                    [
                        'titulo' => 'Ecuaciones Lineales',
                        'descripcion' => 'Resolución de ecuaciones de primer grado',
                        'contenido_texto' => 'Las ecuaciones lineales son ecuaciones algebraicas en las que cada término es una constante o el producto de una constante por una variable elevada a la primera potencia. Son fundamentales para resolver problemas de la vida real.',
                        'pregunta' => '¿Cuál es la pendiente de la recta y = 3x + 2?',
                        'opciones' => ['2', '3', '5', '1'],
                        'respuesta_correcta' => 1
                    ],
                    [
                        'titulo' => 'Sistemas de Ecuaciones',
                        'descripcion' => 'Métodos para resolver sistemas de ecuaciones lineales',
                        'contenido_texto' => 'Un sistema de ecuaciones lineales es un conjunto de dos o más ecuaciones lineales que comparten las mismas variables. Existen varios métodos para resolverlos: sustitución, eliminación y método gráfico.',
                        'pregunta' => '¿Cuál es el valor de x en el sistema: x + y = 5, x - y = 1?',
                        'opciones' => ['x = 2', 'x = 3', 'x = 4', 'x = 5'],
                        'respuesta_correcta' => 1
                    ]
                ]
            ],
            [
                'nombre' => 'Física General',
                'codigo' => 'FIS101',
                'descripcion' => 'Introducción a los principios fundamentales de la física: mecánica, termodinámica, ondas y electricidad.',
                'creditos' => 4,
                'cupo_maximo' => 25,
                'periodo_academico' => '2025-1',
                'maestro_id' => $maestro->id,
                'lecciones' => [
                    [
                        'titulo' => 'Cinemática y Movimiento',
                        'descripcion' => 'Estudio del movimiento de los objetos',
                        'contenido_texto' => 'La cinemática es la rama de la física que describe el movimiento de los objetos sin considerar las causas que lo producen. Estudiaremos conceptos como velocidad, aceleración y desplazamiento.',
                        'pregunta' => '¿Cuál es la unidad de velocidad en el Sistema Internacional?',
                        'opciones' => ['km/h', 'm/s', 'cm/s', 'mph'],
                        'respuesta_correcta' => 1
                    ],
                    [
                        'titulo' => 'Leyes de Newton',
                        'descripcion' => 'Los tres principios fundamentales de la mecánica',
                        'contenido_texto' => 'Las tres leyes de Newton son los principios fundamentales que describen la relación entre las fuerzas que actúan sobre un cuerpo y su movimiento. Estas leyes son la base de la mecánica clásica.',
                        'pregunta' => '¿Cuál es la segunda ley de Newton?',
                        'opciones' => ['F = ma', 'v = d/t', 'E = mc²', 'P = mv'],
                        'respuesta_correcta' => 0
                    ]
                ]
            ],
            [
                'nombre' => 'Programación I',
                'codigo' => 'PRG101',
                'descripcion' => 'Introducción a la programación con conceptos fundamentales, algoritmos y práctica con lenguajes modernos.',
                'creditos' => 4,
                'cupo_maximo' => 20,
                'periodo_academico' => '2025-1',
                'maestro_id' => $maestro->id,
                'lecciones' => [
                    [
                        'titulo' => 'Fundamentos de Programación',
                        'descripcion' => 'Conceptos básicos y lógica de programación',
                        'contenido_texto' => 'La programación es el proceso de crear un conjunto de instrucciones que le dicen a una computadora cómo realizar una tarea. Aprenderemos sobre algoritmos, variables, estructuras de control y funciones.',
                        'pregunta' => '¿Qué es una variable en programación?',
                        'opciones' => ['Un número fijo', 'Un espacio de memoria para almacenar datos', 'Una función', 'Un algoritmo'],
                        'respuesta_correcta' => 1
                    ],
                    [
                        'titulo' => 'Estructuras de Control',
                        'descripcion' => 'Condicionales y bucles en programación',
                        'contenido_texto' => 'Las estructuras de control permiten alterar el flujo de ejecución de un programa. Incluyen condicionales (if, else) y bucles (for, while) que nos permiten crear programas más complejos y útiles.',
                        'pregunta' => '¿Cuál es la diferencia entre un bucle for y while?',
                        'opciones' => ['No hay diferencia', 'For tiene contador definido, while tiene condición', 'While es más rápido', 'For solo funciona con números'],
                        'respuesta_correcta' => 1
                    ]
                ]
            ]
        ];

        foreach ($cursos as $cursoData) {
            $lecciones = $cursoData['lecciones'];
            unset($cursoData['lecciones']);
            
            $curso = Curso::create($cursoData);

            // Inscribir al alumno en algunos cursos
            if ($alumno && in_array($curso->codigo, ['MAT101', 'PRG101'])) {
                $curso->estudiantes()->attach($alumno->id, [
                    'fecha_inscripcion' => now(),
                    'estado' => 'inscrito'
                ]);
            }

            // Crear contenido y actividades para cada lección
            foreach ($lecciones as $index => $leccion) {
                $contenido = Contenido::create([
                    'curso_id' => $curso->id,
                    'titulo' => $leccion['titulo'],
                    'descripcion' => $leccion['descripcion'],
                    'tipo' => 'texto',
                    'contenido_texto' => $leccion['contenido_texto'],
                    'orden' => $index,
                    'activo' => true
                ]);

                Actividad::create([
                    'curso_id' => $curso->id,
                    'contenido_id' => $contenido->id,
                    'titulo' => 'Quiz: ' . $leccion['titulo'],
                    'descripcion' => 'Evaluación de comprensión de la lección',
                    'tipo' => 'opcion_multiple',
                    'pregunta' => ['texto' => $leccion['pregunta']],
                    'opciones' => $leccion['opciones'],
                    'respuesta_correcta' => [$leccion['respuesta_correcta']],
                    'explicacion' => 'Revisa el contenido de la lección para entender mejor el concepto.',
                    'puntos' => 10,
                    'orden' => $index,
                    'activo' => true
                ]);
            }
        }
    }
}