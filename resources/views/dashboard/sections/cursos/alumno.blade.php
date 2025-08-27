<div id="cursos" class="section-content">
    <div class="courses-management">
        <div class="courses-header">
            <div class="header-actions">
                <button class="btn btn-secondary" onclick="showMyCourses()">
                    <span class="btn-icon">📚</span>
                    Mis Cursos Inscritos
                </button>
            </div>
        </div>

        <!-- Available Courses -->
        <div id="available-courses" class="courses-section">
            <h3 class="section-title">Cursos Disponibles para Inscripción</h3>
            <div class="courses-grid">
                @include('dashboard.partials.course-card', [
                    'course' => (object)[
                        'nombre' => 'Matemáticas I',
                        'codigo' => 'MAT101',
                        'descripcion' => 'Curso básico de matemáticas que cubre álgebra, geometría y trigonometría fundamental.',
                        'cupo' => '25/30',
                        'creditos' => 4,
                        'lecciones_count' => 12,
                        'maestro' => 'Juan Pérez',
                        'periodo' => '2025-1',
                        'available' => true
                    ]
                ])
            </div>
        </div>

        <!-- My Enrolled Courses -->
        <div id="my-courses" class="courses-section" style="display: none;">
            <h3 class="section-title">Mis Cursos Inscritos</h3>
            <div class="courses-grid">
                @include('dashboard.partials.course-card', [
                    'course' => (object)[
                        'nombre' => 'Programación I',
                        'codigo' => 'PRG101',
                        'descripcion' => 'Introducción a la programación con conceptos fundamentales y práctica.',
                        'progreso' => 65,
                        'promedio' => 8.5,
                        'lecciones_progress' => '7/10',
                        'actividades_progress' => '3/5',
                        'enrolled' => true
                    ]
                ])
            </div>
        </div>
    </div>
</div>