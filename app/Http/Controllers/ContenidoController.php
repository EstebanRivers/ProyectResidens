<?php

namespace App\Http\Controllers;

use App\Models\Contenido;
use App\Models\Curso;
use App\Models\ProgresoContenido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ContenidoController extends Controller
{
    public function create(Curso $curso)
    {
        // Verificar permisos
        if (!Auth::user()->isAdmin() && !Auth::user()->isMaestro()) {
            return redirect()->route('cursos.index')->with('error', 'No tienes permisos para crear contenido.');
        }

        if (Auth::user()->isMaestro() && $curso->maestro_id !== Auth::id()) {
            return redirect()->route('cursos.index')->with('error', 'Solo puedes crear contenido en tus propios cursos.');
        }

        return view('contenidos.create', compact('curso'));
    }

    public function store(Request $request, Curso $curso)
    {
        // Verificar permisos
        if (!Auth::user()->isAdmin() && !Auth::user()->isMaestro()) {
            return redirect()->route('cursos.index')->with('error', 'No tienes permisos para crear contenido.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:video,texto,pdf,imagen,audio',
            'contenido_texto' => 'nullable|string',
            'archivo' => 'nullable|file|max:50000', // 50MB max
            'orden' => 'required|integer|min:0'
        ]);

        $archivoUrl = null;
        
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $archivoUrl = $archivo->storeAs('contenidos', $nombreArchivo, 'public');
        }

        $contenido = Contenido::create([
            'curso_id' => $curso->id,
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'tipo' => $validated['tipo'],
            'contenido_texto' => $validated['contenido_texto'],
            'archivo_url' => $archivoUrl ? Storage::url($archivoUrl) : null,
            'orden' => $validated['orden'],
            'activo' => true
        ]);

        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Contenido creado exitosamente');
    }

    public function show($contenidoId)
    {
        try {
            $contenido = Contenido::findOrFail($contenidoId);
            $curso = $contenido->curso;
            
            // Verificar que el usuario esté inscrito en el curso (solo para alumnos)
            if (Auth::user()->isAlumno()) {
                $estaInscrito = $curso->estudiantes()->where('estudiante_id', Auth::id())->exists();
                if (!$estaInscrito) {
                    return redirect()->route('cursos.show', $curso->id)
                        ->with('error', 'Debes estar inscrito en el curso para acceder a este contenido.');
                }
            }

            // Obtener todos los contenidos del curso para navegación
            $contenidos = $curso->contenidos()->activos()->ordenados()->get();
            
            // Obtener progreso del usuario (solo para alumnos)
            $progreso = null;
            if (Auth::user()->isAlumno()) {
                $progreso = ProgresoContenido::where('user_id', Auth::id())
                                           ->where('contenido_id', $contenido->id)
                                           ->first();
                
                // Crear progreso si no existe
                if (!$progreso) {
                    $progreso = ProgresoContenido::create([
                        'user_id' => Auth::id(),
                        'contenido_id' => $contenido->id,
                        'fecha_inicio' => now(),
                        'completado' => false
                    ]);
                }
            }
            
            // Calcular progreso general del curso
            $porcentajeProgreso = 0;
            if (Auth::user()->isAlumno()) {
                $totalContenidos = $contenidos->count();
                $completados = ProgresoContenido::where('user_id', Auth::id())
                                               ->whereIn('contenido_id', $contenidos->pluck('id'))
                                               ->where('completado', true)
                                               ->count();
                
                $porcentajeProgreso = $totalContenidos > 0 ? round(($completados / $totalContenidos) * 100) : 0;
            }

            return view('contenidos.show', compact(
                'contenido', 
                'curso', 
                'contenidos', 
                'progreso', 
                'porcentajeProgreso'
            ));

        } catch (\Exception $e) {
            return redirect()->route('cursos.index')
                ->with('error', 'No se pudo cargar el contenido: ' . $e->getMessage());
        }
    }

    public function edit(Curso $curso, Contenido $contenido)
    {
        // Verificar permisos
        if (!Auth::user()->isAdmin() && !Auth::user()->isMaestro()) {
            return redirect()->route('cursos.index')->with('error', 'No tienes permisos para editar contenido.');
        }

        if (Auth::user()->isMaestro() && $curso->maestro_id !== Auth::id()) {
            return redirect()->route('cursos.index')->with('error', 'Solo puedes editar contenido de tus propios cursos.');
        }

        return view('contenidos.edit', compact('curso', 'contenido'));
    }

    public function update(Request $request, Curso $curso, Contenido $contenido)
    {
        // Verificar permisos
        if (!Auth::user()->isAdmin() && !Auth::user()->isMaestro()) {
            return redirect()->route('cursos.index')->with('error', 'No tienes permisos para editar contenido.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:video,texto,pdf,imagen,audio',
            'contenido_texto' => 'nullable|string',
            'archivo' => 'nullable|file|max:50000',
            'orden' => 'required|integer|min:0',
            'activo' => 'boolean'
        ]);

        $archivoUrl = $contenido->archivo_url;
        
        if ($request->hasFile('archivo')) {
            // Eliminar archivo anterior si existe
            if ($contenido->archivo_url) {
                $rutaAnterior = str_replace('/storage/', '', $contenido->archivo_url);
                Storage::disk('public')->delete($rutaAnterior);
            }
            
            $archivo = $request->file('archivo');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $rutaArchivo = $archivo->storeAs('contenidos', $nombreArchivo, 'public');
            $archivoUrl = Storage::url($rutaArchivo);
        }

        $contenido->update([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'tipo' => $validated['tipo'],
            'contenido_texto' => $validated['contenido_texto'],
            'archivo_url' => $archivoUrl,
            'orden' => $validated['orden'],
            'activo' => $validated['activo'] ?? true
        ]);

        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Contenido actualizado exitosamente');
    }

    public function destroy(Curso $curso, Contenido $contenido)
    {
        // Verificar permisos
        if (!Auth::user()->isAdmin() && !Auth::user()->isMaestro()) {
            return redirect()->route('cursos.index')->with('error', 'No tienes permisos para eliminar contenido.');
        }

        // Eliminar archivo si existe
        if ($contenido->archivo_url) {
            $rutaArchivo = str_replace('/storage/', '', $contenido->archivo_url);
            Storage::disk('public')->delete($rutaArchivo);
        }

        $contenido->delete();

        return redirect()->route('cursos.show', $curso)
                        ->with('success', 'Contenido eliminado exitosamente');
    }

    public function marcarCompletado(Request $request, $contenidoId)
    {
        try {
            $contenido = Contenido::findOrFail($contenidoId);
            
            // Verificar que el usuario esté inscrito
            $estaInscrito = $contenido->curso->estudiantes()
                                            ->where('estudiante_id', Auth::id())
                                            ->exists();
            
            if (!$estaInscrito) {
                return response()->json(['error' => 'No tienes acceso a este contenido'], 403);
            }

            // Marcar como completado
            ProgresoContenido::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'contenido_id' => $contenido->id
                ],
                [
                    'completado' => true,
                    'fecha_completado' => now(),
                    'tiempo_dedicado' => $request->input('tiempo_dedicado', 0)
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Contenido marcado como completado'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al marcar contenido: ' . $e->getMessage()], 500);
        }
    }
}