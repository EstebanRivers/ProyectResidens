<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirigir según el rol
            $user = Auth::user();
            switch($user->role) {
                case 'administrador':
                    return redirect()->route('dashboard.admin');
                case 'maestro':
                    return redirect()->route('dashboard.maestro');
                case 'alumno':
                    return redirect()->route('dashboard.alumno');
                default:
                    return redirect()->route('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden.',
        ]);
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
