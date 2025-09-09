<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Curso;
use App\Models\RespuestaActividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActividadController extends Controller
{
    public function create(Curso $curso)
    {
        // Verificar permisos
        if (!Auth::user()->isAdmin() && !Auth::user()->isMaestro()) {
            return redirect()->route('cursos.index')
                ->with('error', 'No tienes permisos para crear actividades.');
        }

        if (Auth::user()->isMaestro() && $curso->maestro_id !== Auth::id()) {
            return redirect()->route('cursos.index')
                ->with('error', 'Solo puedes crear actividades en tus propios cursos.');
        }

        $contenidos = $curso->contenidos()->where('activo', true)->orderBy('orden')->get();
        
        return view('actividades.create', compact('curso', 'contenidos'));
    }

    public function store(Request $request, Curso $curso)
    {
        // Verificar permisos
        if (!Auth::user()->isAdmin() && !Auth::user()->isMaestro()) {
            return redirect()->route('cursos.index')
                ->with('error', 'No tienes permisos para crear actividades.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo' => 'required|in:opcion_multiple,verdadero_falso,respuesta_corta,ensayo',
            'pregunta.texto' => 'required|string',
            'opciones' => 'required_if:tipo,opcion_multiple|array|min:2',
            'opciones.*' => 'required_if:tipo,opcion_multiple|string',
            'respuesta_correcta' => 'required|array',
            'respuestas_cortas' => 'required_if:tipo,respuesta_corta|string',
            'explicacion' => 'nullable|string',
            'puntos' => 'required|integer|min:1|max:100',
            'orden' => 'required|integer|min:0',
            'contenido_id' => 'nullable|exists:contenidos,id'
        ]);

        // Procesar respuestas según el tipo
        $respuestasCorrectas = [];
        
        if ($validated['tipo'] === 'opcion_multiple') {
            $respuestasCorrectas = array_map('intval', $validated['respuesta_correcta']);
        } elseif ($validated['tipo'] === 'verdadero_falso') {
            $respuestasCorrectas = [$validated['respuesta_correcta'][0]];
        } elseif ($validated['tipo'] === 'respuesta_corta') {
            $respuestasCorrectas = array_map('trim', explode("\n", $validated['respuestas_cortas']));
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
            ->with('success', 'Actividad creada exitosamente.');
    }

    public function show(Curso $curso, Actividad $actividad)
    {
        // Verificar que la actividad pertenece al curso
        if ($actividad->curso_id !== $curso->id) {
            return redirect()->route('cursos.index')
                ->with('error', 'Actividad no encontrada en este curso.');
        }

        $estudiante = Auth::user();

        // Verificar que sea estudiante
        if (!$estudiante->isAlumno()) {
            return redirect()->route('cursos.index')
                ->with('error', 'Solo los estudiantes pueden acceder a las actividades.');
        }

        // Verificar inscripción
        $inscrito = $curso->estudiantes()->where('estudiante_id', $estudiante->id)->exists();
        
        if (!$inscrito) {
            return redirect()->route('cursos.show', $curso)
                ->with('error', 'Debes estar inscrito en el curso para acceder a esta actividad.');
        }

        // Verificar si ya respondió
        $respuestaExistente = $actividad->respuestaEstudiante($estudiante->id);
        
        // Obtener estadísticas
        $totalRespuestas = $actividad->respuestas()->count();
        $promedioCalificacion = $actividad->respuestas()->avg('puntos_obtenidos') ?? 0;

        return view('actividades.show', compact(
            'actividad',
            'curso',
            'respuestaExistente',
            'totalRespuestas',
            'promedioCalificacion'
        ));
    }

    public function responder(Request $request, Curso $curso, Actividad $actividad)
    {
        // Verificar que la actividad pertenece al curso
        if ($actividad->curso_id !== $curso->id) {
            return redirect()->route('cursos.index')
                ->with('error', 'Actividad no encontrada en este curso.');
        }

        $estudiante = Auth::user();

        // Verificar inscripción
        if (!$curso->estudiantes()->where('estudiante_id', $estudiante->id)->exists()) {
            return redirect()->route('cursos.show', $curso)
                ->with('error', 'No estás inscrito en este curso.');
        }

        // Verificar si ya respondió
        if ($actividad->yaRespondidaPor($estudiante->id)) {
            return redirect()->route('actividades.show', [$curso, $actividad])
                ->with('warning', 'Ya has respondido esta actividad.');
        }

        $request->validate([
            'respuesta' => 'required|string',
        ]);

        $respuestaEstudiante = $request->input('respuesta');
        
        // Verificar si la respuesta es correcta
        $esCorrecta = $actividad->verificarRespuesta($respuestaEstudiante);
        $puntosObtenidos = $esCorrecta ? $actividad->puntos : 0;

        // Guardar respuesta
        RespuestaActividad::create([
            'estudiante_id' => $estudiante->id,
            'actividad_id' => $actividad->id,
            'respuesta' => $respuestaEstudiante,
            'puntos_obtenidos' => $puntosObtenidos,
            'es_correcta' => $esCorrecta,
            'completada' => true,
            'fecha_completado' => now(),
        ]);

        // Registrar progreso en el curso
        \App\Models\ProgresoCurso::updateOrCreate([
            'estudiante_id' => $estudiante->id,
            'curso_id' => $curso->id,
            'actividad_id' => $actividad->id,
        ], [
            'tipo' => 'actividad',
            'completado' => true,
            'fecha_completado' => now(),
        ]);

        $mensaje = $esCorrecta 
            ? "¡Correcto! Obtuviste {$puntosObtenidos} puntos." 
            : "Respuesta incorrecta. Obtuviste 0 puntos.";

        return redirect()->route('actividades.show', [$curso, $actividad])
            ->with($esCorrecta ? 'success' : 'warning', $mensaje);
    }
}