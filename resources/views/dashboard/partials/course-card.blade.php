<div class="course-card">
    <div class="course-header">
        <div class="course-title-section">
            <h3 class="course-title">{{ $course->nombre }}</h3>
            <p class="course-code">{{ $course->codigo }}</p>
        </div>
        
        @if(isset($course->activo))
            <span class="course-status {{ $course->activo ? 'active' : 'inactive' }}">
                {{ $course->activo ? 'Activo' : 'Inactivo' }}
            </span>
        @elseif(isset($course->available))
            <span class="course-status available">Disponible</span>
        @elseif(isset($course->enrolled))
            <span class="course-status enrolled">Inscrito</span>
        @endif
    </div>

    <p class="course-description">{{ $course->descripcion }}</p>

    @if(isset($course->progreso))
        <div class="course-progress">
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $course->progreso }}%"></div>
            </div>
            <div class="progress-text">{{ $course->progreso }}% completado</div>
        </div>
    @endif

    <div class="course-stats">
        @if(isset($course->estudiantes_count))
            <div class="course-stat">
                <div class="stat-number">{{ $course->estudiantes_count }}</div>
                <div class="stat-label">Estudiantes</div>
            </div>
        @endif
        
        @if(isset($course->cupo))
            <div class="course-stat">
                <div class="stat-number">{{ $course->cupo }}</div>
                <div class="stat-label">Cupo</div>
            </div>
        @endif
        
        @if(isset($course->creditos))
            <div class="course-stat">
                <div class="stat-number">{{ $course->creditos }}</div>
                <div class="stat-label">Créditos</div>
            </div>
        @endif
        
        @if(isset($course->lecciones_count))
            <div class="course-stat">
                <div class="stat-number">{{ $course->lecciones_count }}</div>
                <div class="stat-label">Lecciones</div>
            </div>
        @endif
    </div>

    @if(isset($course->maestro))
        <div class="course-teacher">
            <strong>Profesor:</strong> {{ $course->maestro }}<br>
            <strong>Período:</strong> {{ $course->periodo }}
        </div>
    @endif

    <div class="course-actions">
        @if(isset($course->is_teacher) || isset($course->is_admin))
            <a href="{{ route('cursos.show', $course->id) }}" class="btn btn-primary btn-small">Ver Curso</a>
            <button class="btn btn-primary btn-small" onclick="showCourseContent()">Gestionar Contenido</button>
            <a href="{{ route('cursos.edit', $course->id) }}" class="btn btn-outline btn-small">Editar</a>
        @elseif(isset($course->available))
            <form method="POST" action="{{ route('cursos.inscribir', $course->id) }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-primary btn-small">Inscribirse</button>
            </form>
            <a href="{{ route('cursos.show', $course->id) }}" class="btn btn-outline btn-small">Vista Previa</a>
        @elseif(isset($course->enrolled))
            <a href="{{ route('cursos.show', $course->id) }}" class="btn btn-primary btn-small">Continuar</a>
            <button class="btn btn-outline btn-small" onclick="viewGrades({{ $course->id ?? 1 }})">Ver Calificaciones</button>
        @else
            <a href="{{ route('cursos.show', $course->id) }}" class="btn btn-primary btn-small">Ver Curso</a>
        @endif
    </div>
</div>