<div id="cursos" class="section-content">
    <div class="courses-management">
        <div class="courses-header">
            <div class="header-actions">
                <button class="btn btn-primary" onclick="showCreateCourseForm()">
                    <span class="btn-icon">➕</span>
                    Crear Nuevo Curso
                </button>
            </div>
        </div>

        <!-- Create Course Form -->
        @include('dashboard.partials.forms.create-course')

        <!-- My Courses Grid -->
        <div class="courses-grid">
            @include('dashboard.partials.course-card', [
                'course' => (object)[
                    'nombre' => 'Física General',
                    'codigo' => 'FIS101',
                    'descripcion' => 'Introducción a los conceptos fundamentales de la física: mecánica, termodinámica y ondas.',
                    'estudiantes_count' => 18,
                    'creditos' => 4,
                    'lecciones_count' => 8,
                    'progreso' => 60,
                    'activo' => true,
                    'is_teacher' => true
                ]
            ])
            
            @include('dashboard.partials.create-course-card')
        </div>

        <!-- Course Content Management Modal -->
        @include('dashboard.partials.modals.course-content')
    </div>
</div>