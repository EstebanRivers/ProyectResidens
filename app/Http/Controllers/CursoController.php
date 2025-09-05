<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\User;
use App\Models\Contenido;
use App\Models\Actividad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CursoController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $cursos = Curso::with(['maestro', 'estudiantes'])->get();
        } elseif ($user->isMaestro()) {
            $cursos = Curso::where('maestro_id', $user->id)->with(['estudiantes'])->get();
        } else {
            // Para alumnos, mostrar cursos disponibles y sus cursos inscritos
            $cursosDisponibles = Curso::activos()->with(['maestro'])->get();
            $cursosInscritos = $user->cursosComoEstudiante()->with(['maestro'])->get();
            
            return view('cursos.index', compact('cursosDisponibles', 'cursosInscritos'));
        }
        
        return view('cursos.index', compact('cursos'));
    }

    public function create()
    {
        $this->authorize('create', Curso::class);
        
        $maestros = User::maestros()->get();
        return view('cursos.create', compact('maestros'));
    }

    public function createWizard()
    {
        $this->authorize('create', Curso::class);
        
        $maestros = User::maestros()->get();
        return view('cursos.create-wizard', compact('maestros'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Curso::class);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:20|unique:cursos',
            'descripcion' => 'nullable|string',
            'creditos' => 'required|integer|min:1|max:5',
            'cupo_maximo' => 'required|integer|min:1|max:100',
            'periodo_academico' => 'required|string|max:20',
            'maestro_id' => 'required|exists:users,id'
        ]);

        $curso = Curso::create($validated);

        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Curso creado exitosamente');
    }

    public function storeWizard(Request $request)
    {
        $this->authorize('create', Curso::class);

        // Validar información básica del curso
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:20|unique:cursos',
            'descripcion' => 'nullable|string',
            'creditos' => 'required|integer|min:1|max:5',
            'cupo_maximo' => 'required|integer|min:1|max:100',
            'periodo_academico' => 'required|string|max:20',
            'maestro_id' => 'required|exists:users,id'
        ]);

        // Crear el curso
        $curso = Curso::create($validated);

        // Procesar lecciones si existen
        if ($request->has('lecciones')) {
            foreach ($request->lecciones as $orden => $leccionData) {
                if (!empty($leccionData['titulo'])) {
                    // Crear contenido
                    $contenido = Contenido::create([
                        'curso_id' => $curso->id,
                        'titulo' => $leccionData['titulo'],
                        'descripcion' => $leccionData['descripcion'] ?? '',
                        'tipo' => $leccionData['contenido']['tipo'] ?? 'texto',
                        'contenido_texto' => $leccionData['contenido']['texto'] ?? null,
                        'orden' => $orden,
                        'activo' => true
                    ]);

                    // Crear actividad si existe
                    if (!empty($leccionData['actividad']['pregunta'])) {
                        Actividad::create([
                            'curso_id' => $curso->id,
                            'contenido_id' => $contenido->id,
                            'titulo' => 'Actividad: ' . $leccionData['titulo'],
                            'descripcion' => 'Evaluación de la lección',
                            'tipo' => $leccionData['actividad']['tipo'] ?? 'opcion_multiple',
                            'pregunta' => ['texto' => $leccionData['actividad']['pregunta']],
                            'opciones' => $leccionData['actividad']['opciones'] ?? null,
                            'respuesta_correcta' => [$leccionData['actividad']['respuesta_correcta']] ?? [],
                            'explicacion' => $leccionData['actividad']['explicacion'] ?? null,
                            'puntos' => $leccionData['actividad']['puntos'] ?? 10,
                            'orden' => $orden,
                            'activo' => true
                        ]);
                    }
                }
            }
        }

        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Curso creado exitosamente con ' . count($request->lecciones ?? []) . ' lecciones');
    }

    public function show(Curso $curso)
    {
        $this->authorize('view', $curso);
        
        $curso->load(['maestro', 'estudiantes', 'contenidos', 'actividades']);
        
        // Obtener progreso si es alumno
        $progreso = null;
        if (Auth::user()->isAlumno()) {
            $progreso = $curso->progreso()
                             ->where('estudiante_id', Auth::id())
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
            'codigo' => 'required|string|max:20|unique:cursos,codigo,' . $curso->id,
            'descripcion' => 'nullable|string',
            'creditos' => 'required|integer|min:1|max:5',
            'cupo_maximo' => 'required|integer|min:1|max:100',
            'periodo_academico' => 'required|string|max:20',
            'maestro_id' => 'required|exists:users,id',
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
            return back()->with('error', 'Solo los alumnos pueden inscribirse en cursos');
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
        
        return back()->with('success', 'Te has inscrito exitosamente en el curso');
    }
}