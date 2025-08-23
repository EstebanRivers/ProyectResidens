<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


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
            'total_estudiantes' => User::where('role', 'alumno')->count(),
            'total_maestros' => User::where('role', 'maestro')->count(),
            'cursos_activos' => 156 // Esto vendría de una tabla de cursos
        ];
        
        return view('dashboard.admin', compact('stats'));
    }
    
    public function maestro()
    {
        return view('dashboard.maestro');
    }
    
    public function alumno()
    {
        return view('dashboard.alumno');
    }
}
