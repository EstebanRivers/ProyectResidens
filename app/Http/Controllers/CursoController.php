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
        $maestros = User::maestros()->get();
        return view('cursos.create', compact('maestros'));
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