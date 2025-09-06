use App\Http\Controllers\ContenidoController;
use App\Http\Controllers\ActividadController;
// Rutas principales
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
require __DIR__.'/auth.php';

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rutas de cursos
    Route::resource('cursos', CursoController::class);
    Route::get('/cursos/create/wizard', [CursoController::class, 'createWizard'])->name('cursos.create-wizard');
    Route::post('/cursos/wizard', [CursoController::class, 'storeWizard'])->name('cursos.store-wizard');
    Route::post('/cursos/{curso}/inscribir', [CursoController::class, 'inscribir'])->name('cursos.inscribir');
    
    // Rutas de contenidos
    Route::get('/cursos/{curso}/contenidos/create', [ContenidoController::class, 'create'])->name('contenidos.create');
    Route::post('/cursos/{curso}/contenidos', [ContenidoController::class, 'store'])->name('contenidos.store');
    Route::get('/cursos/{curso}/contenidos/{contenido}', [ContenidoController::class, 'show'])->name('contenidos.show');
    
    // Rutas de actividades
    Route::get('/cursos/{curso}/actividades/create', [ActividadController::class, 'create'])->name('actividades.create');
    Route::post('/cursos/{curso}/actividades', [ActividadController::class, 'store'])->name('actividades.store');
    Route::get('/cursos/{curso}/actividades/{actividad}', [ActividadController::class, 'show'])->name('actividades.show');
    Route::post('/cursos/{curso}/actividades/{actividad}/responder', [ActividadController::class, 'responder'])->name('actividades.responder');
    
    // Rutas específicas por rol
    Route::middleware('role:administrador')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('dashboard.admin');
    });
    
    Route::middleware('role:maestro')->group(function () {
        Route::get('/maestro/dashboard', [DashboardController::class, 'maestro'])->name('dashboard.maestro');
    });
    
    Route::middleware('role:alumno')->group(function () {
        Route::get('/alumno/dashboard', [DashboardController::class, 'alumno'])->name('dashboard.alumno');
    });
    
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
use App\Http\Controllers\CursoController;
}