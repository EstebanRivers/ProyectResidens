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
}