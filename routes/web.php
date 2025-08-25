<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MaestroController;
use App\Http\Controllers\AlumnoController;


Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    
    // Dashboard general
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Dashboard específicos por rol
    Route::middleware('role:administrador')->group(function () {
        Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
        Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
        Route::get('/cursos', [AdminController::class, 'cursos'])->name('admin.cursos');
        Route::get('/reportes', [AdminController::class, 'reportes'])->name('admin.reportes');
    });
    
    Route::middleware('role:maestro')->group(function () {
        Route::get('/dashboard/maestro', [DashboardController::class, 'maestro'])->name('dashboard.maestro');
        Route::get('/maestro/cursos', [MaestroController::class, 'cursos'])->name('maestro.cursos');
        Route::get('/maestro/calificaciones', [MaestroController::class, 'calificaciones'])->name('maestro.calificaciones');
        Route::post('/calificaciones', [MaestroController::class, 'registrarCalificacion'])->name('maestro.calificaciones.store');
    });
    
    Route::middleware('role:alumno')->group(function () {
        Route::get('/dashboard/alumno', [DashboardController::class, 'alumno'])->name('dashboard.alumno');
        Route::get('/alumno/perfil', [AlumnoController::class, 'perfil'])->name('alumno.perfil');
        Route::get('/alumno/calificaciones', [AlumnoController::class, 'calificaciones'])->name('alumno.calificaciones');
        Route::get('/alumno/cursos', [AlumnoController::class, 'cursos'])->name('alumno.cursos');
    });
    
    // Rutas de cursos (accesibles según rol)
    Route::resource('cursos', \App\Http\Controllers\CursoController::class);
    Route::post('/cursos/{curso}/inscribir', [\App\Http\Controllers\CursoController::class, 'inscribir'])->name('cursos.inscribir');
    
    // Rutas de contenido (solo admin y maestros)
    Route::middleware('role:administrador,maestro')->group(function () {
        Route::get('/cursos/{curso}/contenidos/create', [\App\Http\Controllers\ContenidoController::class, 'create'])->name('contenidos.create');
        Route::post('/cursos/{curso}/contenidos', [\App\Http\Controllers\ContenidoController::class, 'store'])->name('contenidos.store');
        Route::get('/cursos/{curso}/contenidos/{contenido}/edit', [\App\Http\Controllers\ContenidoController::class, 'edit'])->name('contenidos.edit');
        Route::put('/cursos/{curso}/contenidos/{contenido}', [\App\Http\Controllers\ContenidoController::class, 'update'])->name('contenidos.update');
        Route::delete('/cursos/{curso}/contenidos/{contenido}', [\App\Http\Controllers\ContenidoController::class, 'destroy'])->name('contenidos.destroy');
    });
    
    // Rutas de contenido (todos los roles autenticados)
    Route::get('/cursos/{curso}/contenidos/{contenido}', [\App\Http\Controllers\ContenidoController::class, 'show'])->name('contenidos.show');
    Route::post('/cursos/{curso}/contenidos/{contenido}/completar', [\App\Http\Controllers\ContenidoController::class, 'marcarCompletado'])->name('contenidos.completar');
    
    // Rutas de actividades (solo admin y maestros)
    Route::middleware('role:administrador,maestro')->group(function () {
        Route::get('/cursos/{curso}/actividades/create', [\App\Http\Controllers\ActividadController::class, 'create'])->name('actividades.create');
        Route::post('/cursos/{curso}/actividades', [\App\Http\Controllers\ActividadController::class, 'store'])->name('actividades.store');
        Route::get('/cursos/{curso}/actividades/{actividad}/edit', [\App\Http\Controllers\ActividadController::class, 'edit'])->name('actividades.edit');
        Route::put('/cursos/{curso}/actividades/{actividad}', [\App\Http\Controllers\ActividadController::class, 'update'])->name('actividades.update');
        Route::delete('/cursos/{curso}/actividades/{actividad}', [\App\Http\Controllers\ActividadController::class, 'destroy'])->name('actividades.destroy');
    });
    
    // Rutas de actividades (todos los roles autenticados)
    Route::get('/cursos/{curso}/actividades/{actividad}', [\App\Http\Controllers\ActividadController::class, 'show'])->name('actividades.show');
    Route::post('/cursos/{curso}/actividades/{actividad}/responder', [\App\Http\Controllers\ActividadController::class, 'responder'])->name('actividades.responder');
    Route::get('/cursos/{curso}/actividades/{actividad}/resultado', [\App\Http\Controllers\ActividadController::class, 'resultado'])->name('actividades.resultado');
});

require __DIR__.'/auth.php';
