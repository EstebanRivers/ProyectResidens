<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Contenido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ContenidoController extends Controller
{
    use AuthorizesRequests;

    public function create(Curso $curso)
    {
        $this->authorize('update', $curso);
        
        return view('contenidos.create', compact('curso'));
    }

    public function store(Request $request, Curso $curso)
    {
        $this->authorize('update', $curso);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:video,texto,pdf,imagen,audio',
            'contenido_texto' => 'nullable|string',
            'archivo' => 'nullable|file|max:50000', // 50MB max
            'orden' => 'required|integer|min:0'
        ]);

        // Manejar archivo si existe
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $path = $archivo->store('contenidos/' . $curso->id, 'public');
            $validated['archivo_url'] = Storage::url($path);
        }

        $validated['curso_id'] = $curso->id;
        $contenido = Contenido::create($validated);

        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Contenido agregado exitosamente');
    }

    public function show(Curso $curso, Contenido $contenido)
    {
        $this->authorize('view', $curso);
        
        return view('contenidos.show', compact('curso', 'contenido'));
    }

    public function edit(Curso $curso, Contenido $contenido)
    {
        $this->authorize('update', $curso);
        
        return view('contenidos.edit', compact('curso', 'contenido'));
    }

    public function update(Request $request, Curso $curso, Contenido $contenido)
    {
        $this->authorize('update', $curso);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:video,texto,pdf,imagen,audio',
            'contenido_texto' => 'nullable|string',
            'archivo' => 'nullable|file|max:50000',
            'orden' => 'required|integer|min:0',
            'activo' => 'boolean'
        ]);

        // Manejar nuevo archivo si existe
        if ($request->hasFile('archivo')) {
            // Eliminar archivo anterior si existe
            if ($contenido->archivo_url) {
                $oldPath = str_replace('/storage/', '', $contenido->archivo_url);
                Storage::disk('public')->delete($oldPath);
            }
            
            $archivo = $request->file('archivo');
            $path = $archivo->store('contenidos/' . $curso->id, 'public');
            $validated['archivo_url'] = Storage::url($path);
        }

        $contenido->update($validated);

        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Contenido actualizado exitosamente');
    }

    public function destroy(Curso $curso, Contenido $contenido)
    {
        $this->authorize('update', $curso);
        
        // Eliminar archivo si existe
        if ($contenido->archivo_url) {
            $path = str_replace('/storage/', '', $contenido->archivo_url);
            Storage::disk('public')->delete($path);
        }
        
        $contenido->delete();
        
        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Contenido eliminado exitosamente');
    }
    
    public function marcarCompletado(Curso $curso, Contenido $contenido)
    {
        if (!Auth::user()->isAlumno()) {
            return back()->with('error', 'Solo los alumnos pueden marcar contenido como completado');
        }
        
        if (!Auth::user()->cursosComoEstudiante()->where('curso_id', $curso->id)->exists()) {
            return back()->with('error', 'Debes estar inscrito en el curso');
        }
        
        $progreso = \App\Models\ProgresoCurso::updateOrCreate(
            [
                'curso_id' => $curso->id,
                'estudiante_id' => Auth::id(),
                'contenido_id' => $contenido->id,
                'tipo' => 'contenido'
            ],
            [
                'completado' => true,
                'fecha_completado' => now(),
                'tiempo_dedicado' => 5 // minutos mínimo
            ]
        );
        
        return back()->with('success', 'Contenido marcado como completado');
    }
    
    public function actualizarProgreso(Request $request, Curso $curso, Contenido $contenido)
    {
        if (!Auth::user()->isAlumno()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        
        $progreso = \App\Models\ProgresoCurso::where([
            'curso_id' => $curso->id,
            'estudiante_id' => Auth::id(),
            'contenido_id' => $contenido->id,
            'tipo' => 'contenido'
        ])->first();
        
        if ($progreso && !$progreso->completado) {
            $progreso->update([
                'tiempo_dedicado' => $request->tiempo_dedicado ?? 5
            ]);
        }
        
        return response()->json(['success' => true]);
    }
}