<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use illuminate\support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, array $roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if (!in_array($user->role, $roles)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
