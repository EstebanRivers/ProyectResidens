<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Curso;
use App\Models\Calificacion;


class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Redirigir según el rol
        switch($user->role) {
            case 'administrador':
                return redirect()->route('dashboard.admin');
            case 'maestro':
                return redirect()->route('dashboard.maestro');
            case 'alumno':
                return redirect()->route('dashboard.alumno');
            default:
                return view('dashboard.general');
        }
    }
    
    public function admin()
    {
        $stats = [
            'total_estudiantes' => User::alumnos()->count(),
            'total_maestros' => User::maestros()->count(),
            'cursos_activos' => Curso::activos()->count(),
            'total_calificaciones' => Calificacion::count()
        ];
        
        return view('dashboard.admin', compact('stats'));
    }
    
    public function maestro()
    {
        $maestro = auth()->user();
        $cursos = $maestro->cursosComoMaestro()->with('estudiantes')->get();
        $totalEstudiantes = $cursos->sum(function($curso) {
            return $curso->estudiantes->count();
        });
        
        $stats = [
            'total_cursos' => $cursos->count(),
            'total_estudiantes' => $totalEstudiantes,
            'cursos_activos' => $cursos->where('activo', true)->count()
        ];
        
        return view('dashboard.maestro', compact('stats', 'cursos'));
    }
    
    public function alumno()
    {
        $alumno = auth()->user();
        $cursosInscritos = $alumno->cursosComoEstudiante;
        $calificaciones = $alumno->calificaciones()->with('curso')->latest()->take(5)->get();
        $promedioGeneral = $alumno->calificaciones()->avg('calificacion');
        
        $stats = [
            'cursos_inscritos' => $cursosInscritos->count(),
            'promedio_general' => round($promedioGeneral, 2),
            'calificaciones_recientes' => $calificaciones->count()
        ];
        
        return view('dashboard.alumno', compact('stats', 'cursosInscritos', 'calificaciones'));
    }
}
