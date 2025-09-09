<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Curso;
use App\Models\RespuestaActividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ActividadController extends Controller
{
    public function show($id)
    {
        try {
            // Buscar la actividad por ID
            $actividad = Actividad::findOrFail($id);
            
            // Verificar que el usuario esté inscrito en el curso
            $curso = $actividad->curso;
            
            if (!$curso->estudiantes()->where('user_id', Auth::id())->exists()) {
                return redirect()->route('cursos.show', $curso->id)
                    ->with('error', 'Debes estar inscrito en el curso para acceder a esta actividad.');
            }

            // Obtener todas las actividades del curso para navegación
            $actividades = $curso->actividades()->activos()->orderBy('orden')->get();
            
            // Verificar si ya respondió
            $yaRespondida = $actividad->yaRespondidaPor(Auth::id());
            $respuestaUsuario = null;
            
            if ($yaRespondida) {
                $respuestaUsuario = $actividad->respuestaDelUsuario(Auth::id());
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
                ->with('error', 'No se pudo cargar la actividad.');
        }
    }

    public function responder(Request $request, $id)
    {
        try {
            $actividad = Actividad::findOrFail($id);
            
            // Verificar inscripción
            if (!$actividad->curso->estudiantes()->where('user_id', Auth::id())->exists()) {
                return redirect()->route('cursos.show', $actividad->curso->id)
                    ->with('error', 'No tienes acceso a esta actividad.');
            }

            // Verificar si ya respondió
            if ($actividad->yaRespondidaPor(Auth::id())) {
                return redirect()->route('actividades.show', $actividad->id)
                    ->with('warning', 'Ya has respondido esta actividad.');
            }

            // Validar respuesta
            $validator = Validator::make($request->all(), [
                'respuesta' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $respuestaUsuario = $request->input('respuesta');
            
            // Asegurar que sea array para múltiples opciones
            if (!is_array($respuestaUsuario)) {
                $respuestaUsuario = [$respuestaUsuario];
            }

            // Calcular puntuación
            $puntuacion = $actividad->calcularPuntuacion($respuestaUsuario);
            $esCorrecta = $puntuacion > 0;

            // Guardar respuesta
            RespuestaActividad::create([
                'user_id' => Auth::id(),
                'actividad_id' => $actividad->id,
                'respuesta' => $respuestaUsuario,
                'puntuacion' => $puntuacion,
                'es_correcta' => $esCorrecta,
                'fecha_respuesta' => now()
            ]);

            $mensaje = $esCorrecta 
                ? "¡Respuesta correcta! Has obtenido {$puntuacion} puntos."
                : "Respuesta incorrecta. La respuesta correcta era: " . implode(', ', $actividad->respuesta_correcta);

            return redirect()->route('actividades.show', $actividad->id)
                ->with($esCorrecta ? 'success' : 'error', $mensaje);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al procesar la respuesta.');
        }
    }
}