<?php

namespace App\Http\Controllers;

use App\Models\Contenido;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContenidoController extends Controller
{
    public function show($id)
    {
        try {
            // Buscar el contenido por ID
            $contenido = Contenido::findOrFail($id);
            
            // Verificar que el usuario esté inscrito en el curso
            $curso = $contenido->curso;
            
            if (!$curso->estudiantes()->where('user_id', Auth::id())->exists()) {
                return redirect()->route('cursos.show', $curso->id)
                    ->with('error', 'Debes estar inscrito en el curso para acceder a este contenido.');
            }

            // Obtener todos los contenidos del curso para navegación
            $contenidos = $curso->contenidos()->activos()->orderBy('orden')->get();
            
            // Obtener progreso del usuario
            $progreso = $contenido->progresoDelUsuario(Auth::id());
            
            // Calcular progreso general del curso
            $totalContenidos = $contenidos->count();
            $completados = $curso->contenidos()
                ->whereHas('progresos', function($query) {
                    $query->where('user_id', Auth::id())
                          ->where('completado', true);
                })
                ->count();
            
            $porcentajeProgreso = $totalContenidos > 0 ? round(($completados / $totalContenidos) * 100) : 0;

            return view('contenidos.show', compact(
                'contenido', 
                'curso', 
                'contenidos', 
                'progreso', 
                'porcentajeProgreso'
            ));

        } catch (\Exception $e) {
            return redirect()->route('cursos.index')
                ->with('error', 'No se pudo cargar el contenido.');
        }
    }

    public function marcarCompletado(Request $request, $id)
    {
        try {
            $contenido = Contenido::findOrFail($id);
            
            // Verificar inscripción
            if (!$contenido->curso->estudiantes()->where('user_id', Auth::id())->exists()) {
                return response()->json(['error' => 'No tienes acceso a este contenido'], 403);
            }

            // Marcar como completado
            $contenido->marcarCompletado(Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Contenido marcado como completado'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al marcar contenido'], 500);
        }
    }
}