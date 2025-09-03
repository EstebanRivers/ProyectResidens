<div id="cursos" class="section-content">
    <div class="courses-management">
        <div class="courses-header">
            <div class="header-actions">
                <a href="{{ route('cursos.create') }}" class="btn btn-primary">
                    <span class="btn-icon">➕</span>
                    Crear Nuevo Curso
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
            @include('dashboard.partials.course-card', [
                'course' => (object)[
                    'nombre' => 'Matemáticas I',
                    'codigo' => 'MAT101',
                    'descripcion' => 'Curso básico de matemáticas que cubre álgebra, geometría y trigonometría fundamental.',
                    'estudiantes_count' => 25,
                    'creditos' => 4,
                    'lecciones_count' => 12,
                    'progreso' => 75,
                    'activo' => true
                ]
            ])
            
            @include('dashboard.partials.create-course-card')
        </div>
    </div>
</div>