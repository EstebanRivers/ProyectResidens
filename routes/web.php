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
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
});

require __DIR__.'/auth.php';
