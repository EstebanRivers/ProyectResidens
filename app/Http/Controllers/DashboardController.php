<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Curso;
use App\Models\Calificacion;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stats = $this->getStatsForUser($user);
        
        return view('dashboard.index', compact('stats'));
    }
    
    private function getStatsForUser($user)
    {
        switch($user->role) {
            case 'administrador':
                return [
                    'total_estudiantes' => User::alumnos()->count(),
                    'total_maestros' => User::maestros()->count(),
                    'cursos_activos' => Curso::activos()->count(),
                    'total_calificaciones' => Calificacion::count()
                ];
                
            case 'maestro':
                $cursos = $user->cursosComoMaestro()->with('estudiantes')->get();
                return [
                    'total_cursos' => $cursos->count(),
                    'total_estudiantes' => $cursos->sum(fn($curso) => $curso->estudiantes->count()),
                    'cursos_activos' => $cursos->where('activo', true)->count()
                ];
                
            case 'alumno':
                return [
                    'cursos_inscritos' => $user->cursosComoEstudiante->count(),
                    'promedio_general' => round($user->calificaciones()->avg('calificacion') ?? 0, 2),
                    'calificaciones_recientes' => $user->calificaciones()->count()
                ];
                
            default:
                return [];
        }
    }
}