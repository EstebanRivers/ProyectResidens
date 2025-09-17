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
            return view('cursos.index', compact('cursos'));
        } elseif ($user->isMaestro()) {
            $cursos = Curso::where('maestro_id', $user->id)->with(['estudiantes'])->get();
            return view('cursos.index', compact('cursos'));
        } else {
            // Para alumnos, mostrar cursos disponibles y sus cursos inscritos
            $cursosDisponibles = Curso::activos()->with(['maestro'])->get();
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

                return redirect()->back()->with('error', 'Solo los estudiantes pueden inscribirse a cursos.');
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para inscribirte');
        }
                return redirect()->route('login')->with('error', 'Debes iniciar sesión para inscribirte a un curso.');
        if (!Auth::user()->isAlumno()) {
            return redirect()->back()->with('error', 'Solo los estudiantes pueden inscribirse a cursos');
                return redirect()->back()->with('warning', 'Ya estás inscrito en este curso.');

        $curso = Curso::findOrFail($id);
        $estudiante = Auth::user();
                return redirect()->route('login')->with('error', 'Debes iniciar sesión para realizar esta acción.');
                return redirect()->back()->with('error', 'Lo sentimos, el curso ha alcanzado su cupo máximo.');
            return redirect()->route('cursos.show', $curso->id)->with('info', 'Ya estás inscrito en este curso');
        }

                return redirect()->back()->with('error', 'Solo los estudiantes pueden desinscribirse de cursos.');
            return redirect()->back()->with('error', 'El curso no tiene cupo disponible');
        }

            return redirect()->route('cursos.show', $curso->id)->with('success', '¡Felicidades! Te has inscrito exitosamente al curso "' . $curso->nombre . '".');
            'fecha_inscripcion' => now(),
            'estado' => 'inscrito'
        ]);
                return redirect()->back()->with('warning', 'No estás inscrito en este curso.');
        return redirect()->route('cursos.show', $curso->id)->with('success', 'Te has inscrito exitosamente al curso');
    }

    public function desinscribir($id)
    {
            return redirect()->route('cursos.index')->with('success', 'Te has desinscrito exitosamente del curso "' . $curso->nombre . '".');
        $estudiante = Auth::user();

        if (!$curso->estudiantes()->where('estudiante_id', $estudiante->id)->exists()) {
            return redirect()->back()->with('error', 'Ocurrió un error al procesar tu desinscripción. Por favor, inténtalo de nuevo.');
        }

        $curso->estudiantes()->detach($estudiante->id);

        return redirect()->route('cursos.index')->with('success', 'Te has desinscrito del curso exitosamente');
    }
}