<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\User;
use App\Models\Contenido;
use App\Models\Actividad;
use App\Models\ProgresoCurso;
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
            return view('cursos.index', compact('cursos'));
        } elseif ($user->isMaestro()) {
            $cursos = Curso::where('maestro_id', $user->id)->with(['estudiantes'])->get();
            return view('cursos.index', compact('cursos'));
        } else {
            // Para alumnos, mostrar cursos disponibles y sus cursos inscritos
            $cursosDisponibles = Curso::where('activo', true)
                ->whereDoesntHave('estudiantes', function($query) use ($user) {
                    $query->where('estudiante_id', $user->id);
                })
                ->with(['maestro', 'estudiantes'])
                ->get();
                
            $cursosInscritos = $user->cursosComoEstudiante()->with(['maestro'])->get();
            
            return view('cursos.index', compact('cursosDisponibles', 'cursosInscritos'));
        }
    }

    public function create()
    {
        $this->authorize('create', Curso::class);
        
        $maestros = User::maestros()->get();
        return view('cursos.create', compact('maestros'));
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

    public function show(Curso $curso)
    {
        $this->authorize('view', $curso);
        
        $curso->load(['maestro', 'estudiantes', 'contenidos' => function($query) {
            $query->activos()->ordenados();
        }, 'actividades' => function($query) {
            $query->activas()->ordenadas();
        }]);
        
        // Obtener progreso si es alumno inscrito
        $progreso = null;
        $estaInscrito = false;
        
        if (Auth::user()->isAlumno()) {
            $estaInscrito = $curso->estudiantes()->where('estudiante_id', Auth::id())->exists();
            
            if ($estaInscrito) {
                $progreso = $curso->progreso()
                                 ->where('estudiante_id', Auth::id())
                                 ->get()
                                 ->keyBy(function($item) {
                                     return $item->tipo . '_' . ($item->contenido_id ?? $item->actividad_id);
                                 });
            }
        }
        
        return view('cursos.show', compact('curso', 'progreso', 'estaInscrito'));
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

    public function inscribir(Request $request, $id)
    {
        // Verificar autenticación
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para inscribirte a un curso.');
        }

        $estudiante = Auth::user();
        
        // Verificar que sea alumno
        if (!$estudiante->isAlumno()) {
            return redirect()->back()->with('error', 'Solo los estudiantes pueden inscribirse a cursos.');
        }

        $curso = Curso::findOrFail($id);

        // Verificar si ya está inscrito
        if ($curso->estudiantes()->where('estudiante_id', $estudiante->id)->exists()) {
            return redirect()->back()->with('warning', 'Ya estás inscrito en este curso.');
        }

        // Verificar cupo disponible
        if (!$curso->tieneCupoDisponible()) {
            return redirect()->back()->with('error', 'Lo sentimos, el curso ha alcanzado su cupo máximo.');
        }

        // Inscribir al estudiante
        $curso->estudiantes()->attach($estudiante->id, [
            'fecha_inscripcion' => now(),
            'estado' => 'inscrito'
        ]);

        return redirect()->route('cursos.show', $curso->id)
                        ->with('success', '¡Felicidades! Te has inscrito exitosamente al curso "' . $curso->nombre . '".');
    }

    public function desinscribir(Request $request, $id)
    {
        // Verificar autenticación
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para realizar esta acción.');
        }

        $estudiante = Auth::user();
        
        // Verificar que sea alumno
        if (!$estudiante->isAlumno()) {
            return redirect()->back()->with('error', 'Solo los estudiantes pueden desinscribirse de cursos.');
        }

        $curso = Curso::findOrFail($id);

        // Verificar si está inscrito
        if (!$curso->estudiantes()->where('estudiante_id', $estudiante->id)->exists()) {
            return redirect()->back()->with('warning', 'No estás inscrito en este curso.');
        }

        // Desinscribir al estudiante
        $curso->estudiantes()->detach($estudiante->id);

        return redirect()->route('cursos.index')
                        ->with('success', 'Te has desinscrito exitosamente del curso "' . $curso->nombre . '".');
    }
}