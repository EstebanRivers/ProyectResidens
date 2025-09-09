@extends('layouts.dashboard')

@section('title', 'Gestión de Cursos')
@section('page-title', 'Cursos')
@section('page-subtitle', 'Gestión de cursos y materias')

@section('content')
<div class="course-management">
    <div class="course-header">
        <div class="course-actions">
            @if(Auth::user()->isAdmin() || Auth::user()->isMaestro())
                <a href="{{ route('cursos.create') }}" class="btn-primary">
                    ➕ Crear Nuevo Curso
                </a>
            @endif
        </div>
    </div>

    <div class="courses-grid">
        @if(Auth::user()->isAlumno())
            <!-- Sección para alumnos -->
            <div class="courses-section">
                <h3 class="section-title">Cursos Disponibles</h3>
                <div class="courses-grid">
                    @forelse($cursosDisponibles as $curso)
                        <div class="course-card">
                            <div class="course-header">
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
                            </div>
                            <div class="course-teacher">
                                <strong>Profesor:</strong> {{ $curso->maestro->name }}
                            </div>
                            <div class="course-actions">
                                <button onclick="showInscriptionModal({{ $curso->id }}, '{{ $curso->nombre }}')" 
                                        class="btn-primary btn-small">📚 Inscribirse</button>
                                <a href="{{ route('cursos.show', $curso->id) }}" class="btn-outline btn-small">👁️ Vista Previa</a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <h3>No hay cursos disponibles</h3>
                            <p>No hay cursos disponibles para inscripción en este momento.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <div class="courses-section">
                <h3 class="section-title">Mis Cursos Inscritos</h3>
                <div class="courses-grid">
                    @forelse($cursosInscritos as $curso)
                        <div class="course-card">
                            <div class="course-header">
                                <div class="course-title-section">
                                    <h3 class="course-title">{{ $curso->nombre }}</h3>
                                    <p class="course-code">{{ $curso->codigo }}</p>
                                </div>
                                <span class="course-status enrolled">Inscrito</span>
                            </div>
                            <p class="course-description">{{ $curso->descripcion }}</p>
                            <div class="course-teacher">
                                <strong>Profesor:</strong> {{ $curso->maestro->name }}
                            </div>
                            <div class="course-actions">
                                <a href="{{ route('cursos.show', $curso) }}" class="btn-primary btn-small">📖 Continuar</a>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <h3>No estás inscrito en ningún curso</h3>
                            <p>Inscríbete en los cursos disponibles arriba.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @else
            <!-- Sección para admin y maestros -->
            @forelse($cursos as $curso)
                <div class="course-card">
                    <div class="course-header">
                        <div class="course-title-section">
                            <h3 class="course-title">{{ $curso->nombre }}</h3>
                            <p class="course-code">{{ $curso->codigo }}</p>
                        </div>
                        <span class="course-status {{ $curso->activo ? 'active' : 'inactive' }}">
                            {{ $curso->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    <p class="course-description">{{ $curso->descripcion }}</p>
                    <div class="course-stats">
                        <div class="course-stat">
                            <div class="stat-number">{{ $curso->estudiantes->count() }}</div>
                            <div class="stat-label">Estudiantes</div>
                        </div>
                        <div class="course-stat">
                            <div class="stat-number">{{ $curso->creditos }}</div>
                            <div class="stat-label">Créditos</div>
                        </div>
                    </div>
                    <div class="course-teacher">
                        <strong>Profesor:</strong> {{ $curso->maestro->name }}<br>
                        <strong>Período:</strong> {{ $curso->periodo_academico }}
                    </div>
                    <div class="course-actions">
                        <a href="{{ route('cursos.show', $curso) }}" class="btn-primary btn-small">Ver Curso</a>
                        <a href="{{ route('cursos.edit', $curso) }}" class="btn-outline btn-small">Editar</a>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <h3>No hay cursos disponibles</h3>
                    <p>
                        @if(Auth::user()->isAdmin() || Auth::user()->isMaestro())
                            Comienza creando tu primer curso.
                        @else
                            No hay cursos disponibles para inscripción en este momento.
                        @endif
                    </p>
                </div>
            @endforelse
        @endif
        
        @if(Auth::user()->isAdmin() || Auth::user()->isMaestro())
            <div class="create-course-card" onclick="window.location.href='{{ route('cursos.create') }}'">
                <div class="create-course-content">
                    <div class="create-course-icon">➕</div>
                    <h3>Crear Nuevo Curso</h3>
                    <p>Haz clic para crear un curso</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection