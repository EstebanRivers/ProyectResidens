<?php

namespace App\Http\Controllers;

use App\Models\Contenido;
use App\Models\ProgresoCurso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContenidoController extends Controller
{
    public function show(Contenido $contenido)
    {
        // Verificar autenticación
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Verificar que el usuario esté inscrito en el curso
        $curso = $contenido->curso;
        $estudiante = Auth::user();

        if (!$estudiante->isAlumno()) {
            return redirect()->route('cursos.index')
                ->with('error', 'Solo los estudiantes pueden acceder a los contenidos.');
        }

        $inscrito = $curso->estudiantes()->where('estudiante_id', $estudiante->id)->exists();
        
        if (!$inscrito) {
            return redirect()->route('cursos.show', $curso)
                ->with('error', 'Debes estar inscrito en el curso para acceder a este contenido.');
        }

        // Obtener o crear progreso
        $progreso = ProgresoCurso::firstOrCreate([
            'estudiante_id' => $estudiante->id,
            'curso_id' => $curso->id,
            'contenido_id' => $contenido->id,
        ], [
            'completado' => false,
            'tiempo_dedicado' => 0,
        ]);

        // Obtener todos los contenidos del curso para navegación
        $contenidos = $curso->contenidos()->orderBy('orden')->get();
        
        // Calcular progreso general del curso
        $totalContenidos = $contenidos->count();
        $contenidosCompletados = ProgresoCurso::where('estudiante_id', $estudiante->id)
            ->where('curso_id', $curso->id)
            ->where('completado', true)
            ->distinct('contenido_id')
            ->count();
        
        $porcentajeProgreso = $totalContenidos > 0 ? round(($contenidosCompletados / $totalContenidos) * 100) : 0;

        return view('contenidos.show', compact(
            'contenido', 
            'curso', 
            'progreso', 
            'contenidos', 
            'porcentajeProgreso',
            'contenidosCompletados',
            'totalContenidos'
        ));
    }

    public function marcarCompletado(Request $request, Contenido $contenido)
    {
        // Verificar autenticación
        if (!auth()->check()) {
            return response()->json(['error' => 'No autenticado'], 401);
        }
        
        $estudiante = Auth::user();
        $curso = $contenido->curso;

        // Verificar inscripción
        if (!$curso->estudiantes()->where('estudiante_id', $estudiante->id)->exists()) {
            return response()->json(['error' => 'No estás inscrito en este curso'], 403);
        }

        // Actualizar progreso
        $progreso = ProgresoCurso::updateOrCreate([
            'estudiante_id' => $estudiante->id,
            'curso_id' => $curso->id,
            'contenido_id' => $contenido->id,
        ], [
            'completado' => true,
            'fecha_completado' => now(),
            'tiempo_dedicado' => $request->input('tiempo_dedicado', 0),
        ]);

        // Calcular nuevo progreso del curso
        $totalContenidos = $curso->contenidos()->count();
        $contenidosCompletados = ProgresoCurso::where('estudiante_id', $estudiante->id)
            ->where('curso_id', $curso->id)
            ->where('completado', true)
            ->distinct('contenido_id')
            ->count();
        
        $porcentajeProgreso = $totalContenidos > 0 ? round(($contenidosCompletados / $totalContenidos) * 100) : 0;

        return response()->json([
            'success' => true,
            'mensaje' => 'Contenido marcado como completado',
            'progreso' => $porcentajeProgreso,
            'completados' => $contenidosCompletados,
            'total' => $totalContenidos
        ]);
    }
}