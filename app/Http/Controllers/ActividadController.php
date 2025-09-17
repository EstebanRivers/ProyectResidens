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

    public function show($id)
    {
        $actividad = Actividad::with(['contenido.curso', 'preguntas.opciones'])->findOrFail($id);
        $user = auth()->user();
        $curso = $actividad->contenido->curso;

        // Verificar acceso del estudiante
        if ($user->rol === 'estudiante') {
            $estaInscrito = $curso->estudiantes()->where('user_id', $user->id)->exists();
            if (!$estaInscrito) {
                return redirect()->route('cursos.show', $curso->id)->with('error', 'Debes inscribirte al curso para acceder a esta actividad');
            }
        }

        // Obtener respuestas del estudiante si las hay
        $respuestasEstudiante = [];
        if ($user->rol === 'estudiante') {
            $respuestasEstudiante = RespuestaActividad::where([
                'actividad_id' => $actividad->id,
                'estudiante_id' => $user->id
            ])->get()->keyBy('pregunta_id');
        }

        return view('actividades.show', compact('actividad', 'respuestasEstudiante'));
    }

    public function responder(Request $request, $id)
    {
        $actividad = Actividad::findOrFail($id);
        $user = auth()->user();

        if ($user->rol !== 'estudiante') {
            return redirect()->back()->with('error', 'Solo los estudiantes pueden responder actividades');
        }

        // Verificar que esté inscrito
        $estaInscrito = $actividad->contenido->curso->estudiantes()->where('user_id', $user->id)->exists();
        if (!$estaInscrito) {
            return redirect()->back()->with('error', 'No estás inscrito en este curso');
        }

        $respuestas = $request->input('respuestas', []);
        $puntajeTotal = 0;
        $totalPreguntas = $actividad->preguntas->count();

        foreach ($respuestas as $preguntaId => $opcionId) {
            $pregunta = $actividad->preguntas->find($preguntaId);
            $opcionCorrecta = $pregunta->opciones->where('es_correcta', true)->first();
            $esCorrecta = $opcionCorrecta && $opcionCorrecta->id == $opcionId;

            if ($esCorrecta) {
                $puntajeTotal++;
            }

            // Guardar o actualizar respuesta
            RespuestaActividad::updateOrCreate([
                'actividad_id' => $actividad->id,
                'estudiante_id' => $user->id,
                'pregunta_id' => $preguntaId
            ], [
                'opcion_id' => $opcionId,
                'es_correcta' => $esCorrecta,
                'fecha_respuesta' => now()
            ]);
        }

        $porcentaje = $totalPreguntas > 0 ? ($puntajeTotal / $totalPreguntas) * 100 : 0;

        return redirect()->back()->with('success', "Actividad completada. Puntaje: {$puntajeTotal}/{$totalPreguntas} ({$porcentaje}%)");
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