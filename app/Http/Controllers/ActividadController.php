<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Actividad;
use App\Models\Contenido;
use App\Models\RespuestaActividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActividadController extends Controller
{
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
            'contenido_id' => 'nullable|exists:contenidos,id',
            'pregunta' => 'required|array',
            'opciones' => 'nullable|array',
            'respuesta_correcta' => 'required|array',
            'explicacion' => 'nullable|string',
            'puntos' => 'required|integer|min:1|max:100',
            'orden' => 'required|integer|min:0'
        ]);

        $actividad = new Actividad($validated);
        $actividad->curso_id = $curso->id;
        $actividad->save();

        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Actividad creada exitosamente');
    }

    public function show(Curso $curso, Actividad $actividad)
    {
        $user = Auth::user();
        
        // Verificar acceso
        if ($user->isAlumno() && !$user->cursosComoEstudiante()->where('curso_id', $curso->id)->exists()) {
            abort(403, 'No tienes acceso a esta actividad');
        }

        $respuestaAnterior = null;
        if ($user->isAlumno()) {
            $respuestaAnterior = $actividad->respuestas()
                                          ->where('estudiante_id', $user->id)
                                          ->first();
        }

        return view('actividades.show', compact('curso', 'actividad', 'respuestaAnterior'));
    }

    public function responder(Request $request, Curso $curso, Actividad $actividad)
    {
        $user = Auth::user();
        
        if (!$user->isAlumno()) {
            return back()->with('error', 'Solo los alumnos pueden responder actividades');
        }

        // Verificar si ya respondió
        $respuestaExistente = $actividad->respuestas()
                                       ->where('estudiante_id', $user->id)
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

        // Guardar respuesta
        $respuesta = new RespuestaActividad([
            'actividad_id' => $actividad->id,
            'estudiante_id' => $user->id,
            'respuesta' => is_array($validated['respuesta']) ? $validated['respuesta'] : [$validated['respuesta']],
            'es_correcta' => $esCorrecta,
            'puntos_obtenidos' => $puntosObtenidos,
            'fecha_respuesta' => now()
        ]);

        $respuesta->save();

        // Registrar progreso
        $curso->progreso()->updateOrCreate([
            'estudiante_id' => $user->id,
            'actividad_id' => $actividad->id,
            'tipo' => 'actividad'
        ], [
            'completado' => true,
            'fecha_completado' => now()
        ]);

        return redirect()->route('actividades.resultado', [$curso, $actividad])
                        ->with('success', 'Respuesta enviada exitosamente');
    }

    public function resultado(Curso $curso, Actividad $actividad)
    {
        $user = Auth::user();
        
        if (!$user->isAlumno()) {
            abort(403);
        }

        $respuesta = $actividad->respuestas()
                              ->where('estudiante_id', $user->id)
                              ->first();

        if (!$respuesta) {
            return redirect()->route('actividades.show', [$curso, $actividad]);
        }

        return view('actividades.resultado', compact('curso', 'actividad', 'respuesta'));
    }

    private function evaluarRespuesta(Actividad $actividad, $respuestaEstudiante)
    {
        $respuestaCorrecta = $actividad->respuesta_correcta;
        
        switch ($actividad->tipo) {
            case 'opcion_multiple':
            case 'verdadero_falso':
                return in_array($respuestaEstudiante, $respuestaCorrecta);
                
            case 'respuesta_corta':
                $respuestaEstudiante = strtolower(trim($respuestaEstudiante));
                return in_array($respuestaEstudiante, array_map('strtolower', $respuestaCorrecta));
                
            case 'ensayo':
                // Para ensayos, requerirá evaluación manual del maestro
                return false;
                
            default:
                return false;
        }
    }

    public function edit(Curso $curso, Actividad $actividad)
    {
        $this->authorize('update', $curso);
        $contenidos = $curso->contenidos()->activos()->ordenados()->get();
        return view('actividades.edit', compact('curso', 'actividad', 'contenidos'));
    }

    public function update(Request $request, Curso $curso, Actividad $actividad)
    {
        $this->authorize('update', $curso);
        
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo' => 'required|in:opcion_multiple,verdadero_falso,respuesta_corta,ensayo',
            'contenido_id' => 'nullable|exists:contenidos,id',
            'pregunta' => 'required|array',
            'opciones' => 'nullable|array',
            'respuesta_correcta' => 'required|array',
            'explicacion' => 'nullable|string',
            'puntos' => 'required|integer|min:1|max:100',
            'orden' => 'required|integer|min:0',
            'activo' => 'boolean'
        ]);

        $actividad->update($validated);

        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Actividad actualizada exitosamente');
    }

    public function destroy(Curso $curso, Actividad $actividad)
    {
        $this->authorize('update', $curso);
        
        $actividad->delete();

        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Actividad eliminada exitosamente');
    }
}