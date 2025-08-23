<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Calificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlumnoController extends Controller
{
    public function perfil()
    {
        $usuario = Auth::user();
        $cursosInscritos = $usuario->cursosComoEstudiante()->count();
        
        return view('dashboard.alumno.perfil', compact('usuario', 'cursosInscritos'));
    }

    public function calificaciones()
    {
        $calificaciones = Calificacion::where('estudiante_id', Auth::id())
                                    ->with(['curso'])
                                    ->orderBy('fecha_evaluacion', 'desc')
                                    ->get();
        
        $promedioGeneral = $calificaciones->avg('calificacion');
        
        return view('dashboard.alumno.calificaciones', compact('calificaciones', 'promedioGeneral'));
    }

    public function cursos()
    {
        $cursosInscritos = Auth::user()->cursosComoEstudiante()
                                     ->with(['maestro'])
                                     ->get();
        
        return view('dashboard.alumno.cursos', compact('cursosInscritos'));
    }
}