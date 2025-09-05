<div id="cursos" class="section-content">
    <div class="courses-management">
        <div class="courses-header">
            <div class="header-actions">
                <a href="{{ route('cursos.create-wizard') }}" class="btn btn-primary">
                    <span class="btn-icon">🧙‍♂️</span>
                    Crear con Asistente
                </a>
                <a href="{{ route('cursos.create') }}" class="btn btn-secondary">
                    <span class="btn-icon">➕</span>
                    Crear Rápido
                </a>
                <button class="btn btn-secondary" onclick="loadAllCourses()">
                    <span class="btn-icon">📚</span>
                    Ver Todos los Cursos
                </button>
            </div>
        </div>

        <!-- Create Course Form -->
        @include('dashboard.partials.forms.create-course')

        <!-- Courses Grid -->
        <div class="courses-grid">
            @foreach(\App\Models\Curso::with(['maestro', 'estudiantes', 'contenidos', 'actividades'])->get() as $curso)
                @include('dashboard.partials.course-card', [
                    'course' => (object)[
                        'id' => $curso->id,
                        'nombre' => $curso->nombre,
                        'codigo' => $curso->codigo,
                        'descripcion' => $curso->descripcion,
                        'estudiantes_count' => $curso->estudiantes->count(),
                        'creditos' => $curso->creditos,
                        'lecciones_count' => $curso->contenidos->count(),
                        'actividades_count' => $curso->actividades->count(),
                        'maestro' => $curso->maestro->name,
                        'periodo' => $curso->periodo_academico,
                        'activo' => $curso->activo,
                        'is_admin' => true
                    ]
                ])
            @endforeach
            
            @include('dashboard.partials.create-course-card')
        </div>
    </div>
</div>