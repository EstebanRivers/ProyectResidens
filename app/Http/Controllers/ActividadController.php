<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Actividad;
use App\Models\Contenido;
use App\Models\RespuestaActividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ActividadController extends Controller
{
    use AuthorizesRequests;

    public function create(Curso $curso)
    {
        $this->authorize('update', $curso);
        
        $contenidos = $curso->contenidos()->activos()->ordenados()->get();
        return view('actividades.create', compact('curso', 'contenidos'));
    }

    public function store(Request $request, Curso $curso)
    {
        $this->authorize('update', $curso);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo' => 'required|in:opcion_multiple,verdadero_falso,respuesta_corta,ensayo',
            'pregunta.texto' => 'required|string',
            'contenido_id' => 'nullable|exists:contenidos,id',
            'puntos' => 'required|integer|min:1|max:100',
            'orden' => 'required|integer|min:0',
            'explicacion' => 'nullable|string'
        ]);

        // Procesar respuestas según el tipo
        $respuestaCorrecta = [];
        $opciones = null;

        switch ($validated['tipo']) {
            case 'opcion_multiple':
                $request->validate([
                    'opciones' => 'required|array|min:2',
                    'opciones.*' => 'required|string',
                    'respuesta_correcta' => 'required|array|min:1'
                ]);
                $opciones = $request->opciones;
                $respuestaCorrecta = $request->respuesta_correcta;
                break;

            case 'verdadero_falso':
                $request->validate([
                    'respuesta_correcta' => 'required|array|min:1'
                ]);
                $respuestaCorrecta = $request->respuesta_correcta;
                break;

            case 'respuesta_corta':
                $request->validate([
                    'respuestas_cortas' => 'required|string'
                ]);
                $respuestaCorrecta = array_filter(explode("\n", $request->respuestas_cortas));
                break;

            case 'ensayo':
                // Para ensayos, no hay respuesta correcta predefinida
                break;
        }

        $actividad = Actividad::create([
            'curso_id' => $curso->id,
            'contenido_id' => $validated['contenido_id'],
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'tipo' => $validated['tipo'],
            'pregunta' => ['texto' => $validated['pregunta']['texto']],
            'opciones' => $opciones,
            'respuesta_correcta' => $respuestaCorrecta,
            'explicacion' => $validated['explicacion'],
            'puntos' => $validated['puntos'],
            'orden' => $validated['orden'],
            'activo' => true
        ]);

        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Actividad creada exitosamente');
    }

    public function show(Curso $curso, Actividad $actividad)
    {
        $this->authorize('view', $curso);
        
        $respuestaUsuario = null;
        if (Auth::user()->isAlumno()) {
            $respuestaUsuario = RespuestaActividad::where('actividad_id', $actividad->id)
                                                  ->where('estudiante_id', Auth::id())
                                                  ->first();
        }
        
        return view('actividades.show', compact('curso', 'actividad', 'respuestaUsuario'));
    }

    public function responder(Request $request, Curso $curso, Actividad $actividad)
    {
        if (!Auth::user()->isAlumno()) {
            return back()->with('error', 'Solo los alumnos pueden responder actividades');
        }

        // Verificar que el alumno esté inscrito en el curso
        if (!Auth::user()->cursosComoEstudiante()->where('curso_id', $curso->id)->exists()) {
            return back()->with('error', 'Debes estar inscrito en el curso para responder actividades');
        }

        // Verificar si ya respondió
        $respuestaExistente = RespuestaActividad::where('actividad_id', $actividad->id)
                                               ->where('estudiante_id', Auth::id())
                                               ->first();

        if ($respuestaExistente) {
            return back()->with('error', 'Ya has respondido esta actividad');
        }

        $validated = $request->validate([
            'respuesta' => 'required'
        ]);

        // Evaluar respuesta
        $esCorrecta = $this->evaluarRespuesta($actividad, $validated['respuesta']);
        $puntosObtenidos = $esCorrecta ? $actividad->puntos : 0;

        RespuestaActividad::create([
            'actividad_id' => $actividad->id,
            'estudiante_id' => Auth::id(),
            'respuesta' => is_array($validated['respuesta']) ? $validated['respuesta'] : [$validated['respuesta']],
            'es_correcta' => $esCorrecta,
            'puntos_obtenidos' => $puntosObtenidos,
            'fecha_respuesta' => now()
        ]);

        $mensaje = $esCorrecta ? 
            "¡Correcto! Has obtenido {$puntosObtenidos} puntos." : 
            "Respuesta incorrecta. La respuesta correcta era: " . implode(', ', $actividad->respuesta_correcta);

        return back()->with($esCorrecta ? 'success' : 'info', $mensaje);
    }

    private function evaluarRespuesta(Actividad $actividad, $respuestaUsuario)
    {
        switch ($actividad->tipo) {
            case 'opcion_multiple':
                return in_array($respuestaUsuario, $actividad->respuesta_correcta);

            case 'verdadero_falso':
                return in_array($respuestaUsuario, $actividad->respuesta_correcta);

            case 'respuesta_corta':
                $respuestaLimpia = strtolower(trim($respuestaUsuario));
                foreach ($actividad->respuesta_correcta as $correcta) {
                    if (strtolower(trim($correcta)) === $respuestaLimpia) {
                        return true;
                    }
                }
                return false;

            case 'ensayo':
                // Los ensayos requieren evaluación manual
                return false;

            default:
                return false;
        }
    }
}