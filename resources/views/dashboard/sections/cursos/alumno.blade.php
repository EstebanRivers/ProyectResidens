<div class="course-management">
    <div class="course-header">
        <div class="header-actions">
            <button class="btn btn-secondary" onclick="toggleCourseView()">
                <span class="btn-icon"></span>
                <span id="toggleText">Ver Mis Cursos</span>
            </button>
        </div>
    </div>

    <!-- Available Courses -->
    <div id="available-courses" class="courses-section">
        <h3 class="section-title">Cursos Disponibles para Inscripción</h3>
        <div class="courses-grid">
            @foreach(\App\Models\Curso::activos()->whereDoesntHave('estudiantes', function($query) {
                $query->where('estudiante_id', Auth::id());
            })->with(['maestro', 'estudiantes', 'contenidos'])->get() as $curso)
                <div class="course-card">
                    <div class="course-card-header">
                        <div class="course-title-section">
                            <h3 class="course-title">{{ $curso->nombre }}</h3>
                            <p class="course-code">{{ $curso->codigo }}</p>
                        </div>
                        <span class="course-status available">Disponible</span>
                    </div>

                    <p class="course-description">{{ $curso->descripcion }}</p>

                    <div class="course-stats">
                        <div class="course-stat">
                            <div class="stat-number">{{ $curso->estudiantes->count() }}/{{ $curso->cupo_maximo }}</div>
                            <div class="stat-label">Cupo</div>
                        </div>
                        <div class="course-stat">
                            <div class="stat-number">{{ $curso->creditos }}</div>
                            <div class="stat-label">Créditos</div>
                        </div>
                        <div class="course-stat">
                            <div class="stat-number">{{ $curso->contenidos->count() }}</div>
                            <div class="stat-label">Lecciones</div>
                        </div>
                    </div>

                    <div class="course-teacher">
                        <strong>Profesor:</strong> {{ $curso->maestro->name }}<br>
                        <strong>Período:</strong> {{ $curso->periodo_academico }}
                    </div>

                    <div class="course-actions">
                        <button class="btn btn-primary btn-small" onclick="showEnrollModal({{ $curso->id }}, '{{ $curso->nombre }}')">
                            Inscribirse
                        </button>
                        <a href="{{ route('cursos.show', $curso) }}" class="btn btn-outline btn-small">Vista Previa</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- My Enrolled Courses -->
    <div id="my-courses" class="courses-section" style="display: none;">
        <h3 class="section-title">Mis Cursos Inscritos</h3>
        <div class="courses-grid">
            @foreach(Auth::user()->cursosComoEstudiante()->with(['maestro', 'contenidos', 'actividades'])->get() as $curso)
                @php
                    $totalContenido = $curso->contenidos->count() + $curso->actividades->count();
                    $completado = $curso->progreso()->where('estudiante_id', Auth::id())->where('completado', true)->count();
                    $progreso = $totalContenido > 0 ? round(($completado / $totalContenido) * 100) : 0;
                @endphp
                <div class="course-card">
                    <div class="course-card-header">
                        <div class="course-title-section">
                            <h3 class="course-title">{{ $curso->nombre }}</h3>
                            <p class="course-code">{{ $curso->codigo }}</p>
                        </div>
                        <span class="course-status enrolled">Inscrito</span>
                    </div>

                    <p class="course-description">{{ $curso->descripcion }}</p>

                    <div class="course-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $progreso }}%"></div>
                        </div>
                        <div class="progress-text">{{ $progreso }}% completado</div>
                    </div>

                    <div class="course-teacher">
                        <strong>Profesor:</strong> {{ $curso->maestro->name }}<br>
                        <strong>Período:</strong> {{ $curso->periodo_academico }}
                    </div>

                    <div class="course-actions">
                        <a href="{{ route('cursos.show', $curso) }}" class="btn btn-primary btn-small">
                            Entrar al Curso
                        </a>
                        <button class="btn btn-outline btn-small" onclick="viewGrades({{ $curso->id }})">Ver Calificaciones</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>