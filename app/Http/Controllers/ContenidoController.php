<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Contenido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContenidoController extends Controller
{
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
            'archivo' => 'nullable|file|max:100000', // 100MB max
            'contenido_texto' => 'nullable|string',
            'orden' => 'required|integer|min:0'
        ]);

        $contenido = new Contenido($validated);
        $contenido->curso_id = $curso->id;

        // Manejar archivo subido
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $path = $archivo->store('contenidos/' . $curso->id, 'public');
            $contenido->archivo_url = Storage::url($path);
            
            // Guardar metadata del archivo
            $contenido->metadata = [
                'nombre_original' => $archivo->getClientOriginalName(),
                'tamaño' => $archivo->getSize(),
                'tipo_mime' => $archivo->getMimeType()
            ];
        }

        $contenido->save();

        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Contenido agregado exitosamente');
    }

    public function show(Curso $curso, Contenido $contenido)
    {
        $user = Auth::user();
        
        // Verificar acceso
        if ($user->isAlumno() && !$user->cursosComoEstudiante()->where('curso_id', $curso->id)->exists()) {
            abort(403, 'No tienes acceso a este contenido');
        }

        // Registrar progreso para alumnos
        if ($user->isAlumno()) {
            $progreso = $curso->progreso()
                             ->where('estudiante_id', $user->id)
                             ->where('contenido_id', $contenido->id)
                             ->where('tipo', 'contenido')
                             ->first();

            if (!$progreso) {
                $curso->progreso()->create([
                    'estudiante_id' => $user->id,
                    'contenido_id' => $contenido->id,
                    'tipo' => 'contenido',
                    'fecha_inicio' => now()
                ]);
            }
        }

        return view('contenidos.show', compact('curso', 'contenido'));
    }

    public function marcarCompletado(Curso $curso, Contenido $contenido)
    {
        $user = Auth::user();
        
        if (!$user->isAlumno()) {
            return response()->json(['error' => 'Solo los alumnos pueden marcar contenido como completado'], 403);
        }

        $progreso = $curso->progreso()
                         ->where('estudiante_id', $user->id)
                         ->where('contenido_id', $contenido->id)
                         ->where('tipo', 'contenido')
                         ->first();

        if ($progreso && !$progreso->completado) {
            $progreso->update([
                'completado' => true,
                'fecha_completado' => now()
            ]);
        }

        return response()->json(['success' => true]);
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
            'archivo' => 'nullable|file|max:100000',
            'contenido_texto' => 'nullable|string',
            'orden' => 'required|integer|min:0',
            'activo' => 'boolean'
        ]);

        // Manejar nuevo archivo
        if ($request->hasFile('archivo')) {
            // Eliminar archivo anterior si existe
            if ($contenido->archivo_url) {
                $oldPath = str_replace('/storage/', '', $contenido->archivo_url);
                Storage::disk('public')->delete($oldPath);
            }

            $archivo = $request->file('archivo');
            $path = $archivo->store('contenidos/' . $curso->id, 'public');
            $validated['archivo_url'] = Storage::url($path);
            
            $validated['metadata'] = [
                'nombre_original' => $archivo->getClientOriginalName(),
                'tamaño' => $archivo->getSize(),
                'tipo_mime' => $archivo->getMimeType()
            ];
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
}