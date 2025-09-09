<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\ContenidoController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\MaestroController;
use Illuminate\Support\Facades\Route;

// Ruta raíz redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación (definidas en auth.php)
require __DIR__.'/auth.php';

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas de cursos
    Route::resource('cursos', CursoController::class);
    Route::post('/cursos/{curso}/inscribir', [CursoController::class, 'inscribir'])->name('cursos.inscribir');
    Route::delete('cursos/{curso}/desinscribir', [CursoController::class, 'desinscribir'])->name('cursos.desinscribir');
    Route::delete('cursos/{curso}/desinscribir', [CursoController::class, 'desinscribir'])->name('cursos.desinscribir');
    
    // Rutas de contenidos
    Route::get('/cursos/{curso}/contenidos/create', [ContenidoController::class, 'create'])->name('contenidos.create');
    Route::post('/cursos/{curso}/contenidos', [ContenidoController::class, 'store'])->name('contenidos.store');
    Route::get('/cursos/{curso}/contenidos/{contenido}', [ContenidoController::class, 'show'])->name('contenidos.show');
    Route::get('/cursos/{curso}/contenidos/{contenido}/edit', [ContenidoController::class, 'edit'])->name('contenidos.edit');
    Route::put('/cursos/{curso}/contenidos/{contenido}', [ContenidoController::class, 'update'])->name('contenidos.update');
    Route::delete('/cursos/{curso}/contenidos/{contenido}', [ContenidoController::class, 'destroy'])->name('contenidos.destroy');
    Route::post('/contenidos/{contenido}/marcar-completado', [ContenidoController::class, 'marcarCompletado'])->name('contenidos.marcar-completado');
    Route::post('/contenidos/{id}/completar', [ContenidoController::class, 'marcarCompletado'])->name('contenidos.completar');
    
    // Rutas de actividades
    Route::get('/cursos/{curso}/actividades/create', [ActividadController::class, 'create'])->name('actividades.create');
    Route::post('/cursos/{curso}/actividades', [ActividadController::class, 'store'])->name('actividades.store');
    Route::get('/cursos/{curso}/actividades/{actividad}', [ActividadController::class, 'show'])->name('actividades.show');
    Route::post('/cursos/{curso}/actividades/{actividad}/responder', [ActividadController::class, 'responder'])->name('actividades.responder');
    Route::post('/actividades/{id}/responder', [ActividadController::class, 'responder'])->name('actividades.responder');
    
    // Rutas específicas por rol
    Route::middleware('role:administrador')->group(function () {
        // Rutas administrativas adicionales pueden ir aquí
    });
    
    Route::middleware('role:maestro')->group(function () {
        Route::get('/maestro/cursos', [MaestroController::class, 'cursos'])->name('maestro.cursos');
        Route::get('/maestro/calificaciones', [MaestroController::class, 'calificaciones'])->name('maestro.calificaciones');
        Route::post('/maestro/calificaciones', [MaestroController::class, 'registrarCalificacion'])->name('maestro.calificaciones.store');
    });
    
    Route::middleware('role:alumno')->group(function () {
        Route::get('/alumno/cursos', [AlumnoController::class, 'cursos'])->name('alumno.cursos');
        Route::get('/alumno/calificaciones', [AlumnoController::class, 'calificaciones'])->name('alumno.calificaciones');
        Route::get('/alumno/perfil', [AlumnoController::class, 'perfil'])->name('alumno.perfil');
    });
    
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});