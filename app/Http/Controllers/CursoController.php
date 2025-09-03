<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Contenido;
use App\Models\Actividad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CursoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin() || $user->isMaestro()) {
            $cursos = Curso::with(['estudiantes', 'contenidos', 'actividades'])
                          ->when($user->isMaestro(), function($query) use ($user) {
                              return $query->where('maestro_id', $user->id);
                          })
                          ->get();
        } else {
            $cursos = $user->cursosComoEstudiante()->with(['contenidos', 'actividades'])->get();
        }
        
        return view('cursos.index', compact('cursos'));
    }

    public function create()
    {
        $this->authorize('create', Curso::class);
        return view('cursos.create-wizard');
    }

    public function storeWizard(Request $request)
    {
        $this->authorize('create', Curso::class);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'codigo' => 'required|string|unique:cursos,codigo',
            'creditos' => 'required|integer|min:1|max:10',
            'maestro_id' => 'required|exists:users,id',
            'cupo_maximo' => 'required|integer|min:1',
            'periodo_academico' => 'required|string',
            'lecciones' => 'required|array|min:1',
            'lecciones.*.titulo' => 'required|string|max:255',
            'lecciones.*.descripcion' => 'nullable|string',
        ]);

        // Crear el curso
        $curso = Curso::create([
            'nombre' => $validated['nombre'],
            'descripcion' => $validated['descripcion'],
            'codigo' => $validated['codigo'],
            'creditos' => $validated['creditos'],
            'maestro_id' => $validated['maestro_id'],
            'cupo_maximo' => $validated['cupo_maximo'],
            'periodo_academico' => $validated['periodo_academico'],
        ]);

        // Crear lecciones con contenido y actividades
        foreach ($validated['lecciones'] as $orden => $leccionData) {
            // Crear contenido si existe
            if (isset($leccionData['contenido'])) {
                $contenido = Contenido::create([
                    'curso_id' => $curso->id,
                    'titulo' => $leccionData['titulo'],
                    'descripcion' => $leccionData['descripcion'] ?? '',
                    'tipo' => $leccionData['contenido']['tipo'],
                    'contenido_texto' => $leccionData['contenido']['texto'] ?? null,
                    'orden' => $orden,
                ]);

                // Manejar archivos subidos
                if (isset($leccionData['contenido']['video_file']) && $request->hasFile("lecciones.{$orden}.contenido.video_file")) {
                    $archivo = $request->file("lecciones.{$orden}.contenido.video_file");
                    $path = $archivo->store('contenidos/' . $curso->id, 'public');
                    $contenido->update(['archivo_url' => Storage::url($path)]);
                }
                
                if (isset($leccionData['contenido']['pdf_file']) && $request->hasFile("lecciones.{$orden}.contenido.pdf_file")) {
                    $archivo = $request->file("lecciones.{$orden}.contenido.pdf_file");
                    $path = $archivo->store('contenidos/' . $curso->id, 'public');
                    $contenido->update(['archivo_url' => Storage::url($path)]);
                }
            }

            // Crear actividad si existe
            if (isset($leccionData['actividad'])) {
                $actividadData = $leccionData['actividad'];
                
                // Preparar respuesta correcta según el tipo
                $respuestaCorrecta = [];
                switch ($actividadData['tipo']) {
                    case 'opcion_multiple':
                        $respuestaCorrecta = [$actividadData['respuesta_correcta']];
                        break;
                    case 'verdadero_falso':
                        $respuestaCorrecta = [$actividadData['respuesta_correcta']];
                        break;
                    case 'respuesta_corta':
                        $respuestaCorrecta = array_filter(explode("\n", $actividadData['respuestas_correctas'] ?? ''));
                        break;
                    case 'ensayo':
                        $respuestaCorrecta = ['manual_review'];
                        break;
                }

                Actividad::create([
                    'curso_id' => $curso->id,
                    'contenido_id' => $contenido->id ?? null,
                    'titulo' => 'Actividad: ' . $leccionData['titulo'],
                    'descripcion' => 'Actividad de la lección: ' . $leccionData['titulo'],
                    'tipo' => $actividadData['tipo'],
                    'pregunta' => ['texto' => $actividadData['pregunta']],
                    'opciones' => $actividadData['opciones'] ?? null,
                    'respuesta_correcta' => $respuestaCorrecta,
                    'explicacion' => $actividadData['explicacion'] ?? null,
                    'puntos' => $actividadData['puntos'] ?? 10,
                    'orden' => $orden,
                ]);
            }
        }

        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Curso creado exitosamente con ' . count($validated['lecciones']) . ' lecciones');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Curso::class);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'codigo' => 'required|string|unique:cursos,codigo',
            'creditos' => 'required|integer|min:1|max:10',
            'maestro_id' => 'required|exists:users,id',
            'cupo_maximo' => 'required|integer|min:1',
            'periodo_academico' => 'required|string'
        ]);

        $curso = Curso::create($validated);
        
        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Curso creado exitosamente');
    }

    public function show(Curso $curso)
    {
        $curso->load(['contenidos' => function($query) {
            $query->activos()->ordenados();
        }, 'actividades' => function($query) {
            $query->activas()->ordenadas();
        }, 'estudiantes']);

        $user = Auth::user();
        $progreso = null;
        
        if ($user->isAlumno()) {
            $progreso = $curso->progreso()
                             ->where('estudiante_id', $user->id)
                             ->get()
                             ->keyBy(function($item) {
                                 return $item->tipo . '_' . ($item->contenido_id ?? $item->actividad_id);
                             });
        }

        return view('cursos.show', compact('curso', 'progreso'));
    }

    public function edit(Curso $curso)
    {
        $this->authorize('update', $curso);
        $maestros = User::maestros()->get();
        return view('cursos.edit', compact('curso', 'maestros'));
    }

    public function update(Request $request, Curso $curso)
    {
        $this->authorize('update', $curso);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'codigo' => 'required|string|unique:cursos,codigo,' . $curso->id,
            'creditos' => 'required|integer|min:1|max:10',
            'maestro_id' => 'required|exists:users,id',
            'cupo_maximo' => 'required|integer|min:1',
            'periodo_academico' => 'required|string',
            'activo' => 'boolean'
        ]);

        $curso->update($validated);
        
        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Curso actualizado exitosamente');
    }

    public function destroy(Curso $curso)
    {
        $this->authorize('delete', $curso);
        
        $curso->delete();
        
        return redirect()->route('cursos.index')
                        ->with('success', 'Curso eliminado exitosamente');
    }

    public function inscribir(Request $request, Curso $curso)
    {
        $user = Auth::user();
        
        if (!$user->isAlumno()) {
            return back()->with('error', 'Solo los alumnos pueden inscribirse a cursos');
        }

        if (!$curso->tieneCupoDisponible()) {
            return back()->with('error', 'El curso no tiene cupo disponible');
        }

        if ($user->cursosComoEstudiante()->where('curso_id', $curso->id)->exists()) {
            return back()->with('error', 'Ya estás inscrito en este curso');
        }

        $user->cursosComoEstudiante()->attach($curso->id, [
            'fecha_inscripcion' => now(),
            'estado' => 'inscrito'
        ]);

        return back()->with('success', 'Te has inscrito exitosamente al curso');
    }
}