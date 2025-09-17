<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Curso;
use App\Models\Contenido;
use App\Models\RespuestaActividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActividadController extends Controller
{
    public function create(Curso $curso)
    {
        // Verificar permisos
        if (!Auth::user()->isAdmin() && !Auth::user()->isMaestro()) {
            return redirect()->route('cursos.index')->with('error', 'No tienes permisos para crear actividades.');
        }

        if (Auth::user()->isMaestro() && $curso->maestro_id !== Auth::id()) {
            return redirect()->route('cursos.index')->with('error', 'Solo puedes crear actividades en tus propios cursos.');
        }

        $contenidos = $curso->contenidos()->where('activo', true)->orderBy('orden')->get();
        return view('actividades.create', compact('curso', 'contenidos'));
    }

    public function store(Request $request, Curso $curso)
    {
        // Verificar permisos
        if (!Auth::user()->isAdmin() && !Auth::user()->isMaestro()) {
            return redirect()->route('cursos.index')->with('error', 'No tienes permisos para crear actividades.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo' => 'required|in:opcion_multiple,verdadero_falso,respuesta_corta,ensayo',
            'pregunta.texto' => 'required|string',
            'opciones' => 'nullable|array',
            'opciones.*' => 'nullable|string',
            'respuesta_correcta' => 'required|array',
            'respuestas_cortas' => 'nullable|string',
            'explicacion' => 'nullable|string',
            'puntos' => 'required|integer|min:1|max:100',
            'orden' => 'required|integer|min:0',
            'contenido_id' => 'nullable|exists:contenidos,id'
        ]);

        // Procesar respuestas según el tipo
        $respuestasCorrectas = [];
        
        if ($validated['tipo'] === 'opcion_multiple') {
            $respuestasCorrectas = $validated['respuesta_correcta'];
        } elseif ($validated['tipo'] === 'verdadero_falso') {
            $respuestasCorrectas = $validated['respuesta_correcta'];
        } elseif ($validated['tipo'] === 'respuesta_corta') {
            $respuestasCorrectas = array_filter(array_map('trim', explode("\n", $validated['respuestas_cortas'] ?? '')));
        }

        Actividad::create([
            'curso_id' => $curso->id,
            'contenido_id' => $validated['contenido_id'],
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'tipo' => $validated['tipo'],
            'pregunta' => ['texto' => $validated['pregunta']['texto']],
            'opciones' => $validated['opciones'] ?? null,
            'respuesta_correcta' => $respuestasCorrectas,
            'explicacion' => $validated['explicacion'],
            'puntos' => $validated['puntos'],
            'orden' => $validated['orden'],
            'activo' => true
        ]);

        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Actividad creada exitosamente');
    }

    public function show($actividadId)
    {
        try {
            $actividad = Actividad::findOrFail($actividadId);
            $curso = $actividad->curso;
            
            // Verificar que el usuario esté inscrito en el curso (solo para alumnos)
            if (Auth::user()->isAlumno()) {
                $estaInscrito = $curso->estudiantes()->where('estudiante_id', Auth::id())->exists();
                if (!$estaInscrito) {
                    return redirect()->route('cursos.show', $curso->id)
                        ->with('error', 'Debes estar inscrito en el curso para acceder a esta actividad.');
                }
            }

            // Obtener todas las actividades del curso para navegación
            $actividades = $curso->actividades()->activos()->ordenadas()->get();
            
            // Verificar si ya respondió (solo para alumnos)
            $yaRespondida = false;
            $respuestaUsuario = null;
            
            if (Auth::user()->isAlumno()) {
                $respuestaUsuario = RespuestaActividad::where('estudiante_id', Auth::id())
                                                    ->where('actividad_id', $actividad->id)
                                                    ->first();
                $yaRespondida = $respuestaUsuario !== null;
            }

            return view('actividades.show', compact(
                'actividad', 
                'curso', 
                'actividades', 
                'yaRespondida', 
                'respuestaUsuario'
            ));

        } catch (\Exception $e) {
            return redirect()->route('cursos.index')
                ->with('error', 'No se pudo cargar la actividad: ' . $e->getMessage());
        }
    }

    public function responder(Request $request, $actividadId)
    {
        try {
            $actividad = Actividad::findOrFail($actividadId);
            $curso = $actividad->curso;
            
            // Verificar que es alumno
            if (!Auth::user()->isAlumno()) {
                return redirect()->back()->with('error', 'Solo los alumnos pueden responder actividades.');
            }
            
            // Verificar inscripción
            $estaInscrito = $curso->estudiantes()->where('estudiante_id', Auth::id())->exists();
            if (!$estaInscrito) {
                return redirect()->route('cursos.show', $curso->id)
                    ->with('error', 'No tienes acceso a esta actividad.');
            }

            // Verificar si ya respondió
            $yaRespondida = RespuestaActividad::where('estudiante_id', Auth::id())
                                            ->where('actividad_id', $actividad->id)
                                            ->exists();
            
            if ($yaRespondida) {
                return redirect()->route('actividades.show', [$curso->id, $actividad->id])
                    ->with('warning', 'Ya has respondido esta actividad.');
            }

            // Validar respuesta
            $request->validate([
                'respuesta' => 'required'
            ]);

            $respuestaUsuario = $request->input('respuesta');
            
            // Asegurar que sea array para consistencia
            if (!is_array($respuestaUsuario)) {
                $respuestaUsuario = [$respuestaUsuario];
            }

            // Verificar respuesta correcta
            $respuestasCorrectas = $actividad->respuesta_correcta ?? [];
            $esCorrecta = false;
            $puntuacion = 0;

            if (!empty($respuestasCorrectas)) {
                if ($actividad->tipo === 'opcion_multiple') {
                    // Para opción múltiple, verificar que las respuestas coincidan
                    $esCorrecta = count(array_intersect($respuestaUsuario, $respuestasCorrectas)) > 0;
                } elseif ($actividad->tipo === 'verdadero_falso') {
                    $esCorrecta = in_array($respuestaUsuario[0], $respuestasCorrectas);
                } elseif ($actividad->tipo === 'respuesta_corta') {
                    // Para respuesta corta, verificar si alguna respuesta correcta coincide
                    $respuestaLimpia = strtolower(trim($respuestaUsuario[0]));
                    foreach ($respuestasCorrectas as $correcta) {
                        if (strtolower(trim($correcta)) === $respuestaLimpia) {
                            $esCorrecta = true;
                            break;
                        }
                    }
                }
                
                $puntuacion = $esCorrecta ? $actividad->puntos : 0;
            }

            // Guardar respuesta
            RespuestaActividad::create([
                'estudiante_id' => Auth::id(),
                'actividad_id' => $actividad->id,
                'respuesta' => $respuestaUsuario,
                'puntos_obtenidos' => $puntuacion,
                'es_correcta' => $esCorrecta,
                'completada' => true,
                'fecha_completado' => now()
            ]);

            $mensaje = $esCorrecta 
                ? "¡Respuesta correcta! Has obtenido {$puntuacion} puntos."
                : "Respuesta incorrecta. Revisa el material del curso.";

            return redirect()->route('actividades.show', [$curso->id, $actividad->id])
                ->with($esCorrecta ? 'success' : 'error', $mensaje);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al procesar la respuesta: ' . $e->getMessage());
        }
    }
}