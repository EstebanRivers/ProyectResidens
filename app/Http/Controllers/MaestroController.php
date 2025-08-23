<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Calificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaestroController extends Controller
{
    public function cursos()
    {
        $cursos = Curso::where('maestro_id', Auth::id())
                      ->with(['estudiantes'])
                      ->get();
        
        return view('dashboard.maestro.cursos', compact('cursos'));
    }

    public function calificaciones()
    {
        $cursos = Curso::where('maestro_id', Auth::id())
                      ->with(['estudiantes', 'calificaciones'])
                      ->get();
        
        return view('dashboard.maestro.calificaciones', compact('cursos'));
    }

    public function registrarCalificacion(Request $request)
    {
        $request->validate([
            'estudiante_id' => 'required|exists:users,id',
            'curso_id' => 'required|exists:cursos,id',
            'tipo_evaluacion' => 'required|in:examen,tarea,proyecto,participacion,final',
            'calificacion' => 'required|numeric|min:0|max:100',
            'fecha_evaluacion' => 'required|date',
            'observaciones' => 'nullable|string'
        ]);

        // Verificar que el maestro sea dueño del curso
        $curso = Curso::where('id', $request->curso_id)
                     ->where('maestro_id', Auth::id())
                     ->firstOrFail();

        Calificacion::create($request->all());

        return redirect()->back()->with('success', 'Calificación registrada exitosamente');
    }
}