<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\RespuestaActividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActividadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Actividad $actividad)
    {
        $estudiante = Auth::user();
        $curso = $actividad->curso;

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

        // Obtener preguntas de la actividad
        $preguntas = $actividad->preguntas()->get();
        
        // Verificar si ya respondió
        $respuestaExistente = $actividad->respuestaEstudiante($estudiante->id);
        
        // Obtener estadísticas
        $totalRespuestas = $actividad->respuestas()->count();
        $promedioCalificacion = $actividad->respuestas()->avg('puntuacion') ?? 0;

        return view('actividades.show', compact(
            'actividad',
            'curso',
            'preguntas',
            'respuestaExistente',
            'totalRespuestas',
            'promedioCalificacion'
        ));
    }

    public function responder(Request $request, Actividad $actividad)
    {
        $estudiante = Auth::user();
        $curso = $actividad->curso;

        // Verificar inscripción
        if (!$curso->estudiantes()->where('estudiante_id', $estudiante->id)->exists()) {
            return redirect()->route('cursos.show', $curso)
                ->with('error', 'No estás inscrito en este curso.');
        }

        // Verificar si ya respondió
        if ($actividad->yaRespondidaPor($estudiante->id)) {
            return redirect()->route('actividades.show', $actividad)
                ->with('warning', 'Ya has respondido esta actividad.');
        }

        $request->validate([
            'respuestas' => 'required|array',
            'respuestas.*' => 'required|string',
        ]);

        // Calcular puntuación
        $preguntas = $actividad->preguntas;
        $respuestas = $request->input('respuestas');
        $puntuacionTotal = 0;
        $respuestasCorrectas = 0;

        foreach ($preguntas as $pregunta) {
            if (isset($respuestas[$pregunta->id])) {
                $respuestaEstudiante = $respuestas[$pregunta->id];
                if ($pregunta->esRespuestaCorrecta($respuestaEstudiante)) {
                    $puntuacionTotal += $pregunta->puntos;
                    $respuestasCorrectas++;
                }
            }
        }

        // Guardar respuesta
        RespuestaActividad::create([
            'estudiante_id' => $estudiante->id,
            'actividad_id' => $actividad->id,
            'respuestas' => $respuestas,
            'puntuacion' => $puntuacionTotal,
            'completada' => true,
            'fecha_completado' => now(),
        ]);

        $totalPreguntas = $preguntas->count();
        $porcentajeAcierto = $totalPreguntas > 0 ? round(($respuestasCorrectas / $totalPreguntas) * 100) : 0;

        return redirect()->route('actividades.show', $actividad)
            ->with('success', "¡Actividad completada! Obtuviste {$puntuacionTotal} puntos ({$porcentajeAcierto}% de acierto).");
    }
}