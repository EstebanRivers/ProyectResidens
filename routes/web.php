<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use app\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;


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
        Route::get('/mis-cursos', [MaestroController::class, 'cursos'])->name('maestro.cursos');
        Route::get('/calificaciones', [MaestroController::class, 'calificaciones'])->name('maestro.calificaciones');
    });
    
    Route::middleware('role:alumno')->group(function () {
        Route::get('/dashboard/alumno', [DashboardController::class, 'alumno'])->name('dashboard.alumno');
        Route::get('/mi-informacion', [AlumnoController::class, 'perfil'])->name('alumno.perfil');
        Route::get('/mis-calificaciones', [AlumnoController::class, 'calificaciones'])->name('alumno.calificaciones');
    });
});

/*
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/
require __DIR__.'/auth.php';
