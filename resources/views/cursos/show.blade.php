@extends('layouts.dashboard')

@section('title', $curso->nombre)
@section('page-title', $curso->nombre)
@section('page-subtitle', $curso->codigo . ' - ' . $curso->periodo_academico)

@section('content')
<div class="course-detail">
    <!-- Course Header -->
    <div class="course-header">
        <div class="course-info">
            <h1>{{ $curso->nombre }}</h1>
            <p>{{ $curso->descripcion }}</p>
            <div class="course-meta">
                <span>👨‍🏫 {{ $curso->maestro->name }}</span>
                <span>📅 {{ $curso->periodo_academico }}</span>
                <span>⭐ {{ $curso->creditos }} créditos</span>
                <span>👥 {{ $curso->estudiantes->count() }}/{{ $curso->cupo_maximo }} estudiantes</span>
            </div>
        </div>
        
        <div class="course-actions">
            @if(Auth::user()->isAdmin() || (Auth::user()->isMaestro() && $curso->maestro_id === Auth::id()))
                <a href="{{ route('contenidos.create', $curso) }}" class="btn-primary">➕ Agregar Contenido</a>
                <a href="{{ route('actividades.create', $curso) }}" class="btn-secondary">📝 Crear Actividad</a>
                <a href="{{ route('cursos.edit', $curso) }}" class="btn-outline">✏️ Editar Curso</a>
            @elseif(Auth::user()->isAlumno())
                @if(!Auth::user()->cursosComoEstudiante()->where('curso_id', $curso->id)->exists())
                    <form method="POST" action="{{ route('cursos.inscribir', $curso) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-primary">📚 Inscribirse</button>
                    </form>
                @else
                    <span class="status-badge status-completed">✅ Inscrito</span>
                @endif
            @endif
        </div>
    </div>

    <!-- Course Content -->
    <div class="course-content">
        <div class="content-tabs">
            <button class="content-tab active" data-tab="contenidos">📚 Contenidos</button>
            <button class="content-tab" data-tab="actividades">📝 Actividades</button>
            @if(Auth::user()->isAdmin() || (Auth::user()->isMaestro() && $curso->maestro_id === Auth::id()))
                <button class="content-tab" data-tab="estudiantes">👥 Estudiantes</button>
            @endif
        </div>

        <!-- Contenidos Tab -->
        <div id="contenidos" class="content-section active">
            <div class="content-list">
                @forelse($curso->contenidos as $contenido)
                    <a href="{{ route('contenidos.show', [$curso, $contenido]) }}" class="content-item">
                        <div class="content-icon">{{ $contenido->icono_tipo }}</div>
                        <div class="content-info">
                            <div class="content-title">{{ $contenido->titulo }}</div>
                            <div class="content-description">{{ $contenido->descripcion }}</div>
                        </div>
                        <div class="content-status">
                            @if(Auth::user()->isAlumno() && isset($progreso))
                                @php
                                    $progresoItem = $progreso['contenido_' . $contenido->id] ?? null;
                                @endphp
                                @if($progresoItem && $progresoItem->completado)
                                    <span class="status-badge status-completed">✅ Completado</span>
                                @else
                                    <span class="status-badge status-pending">⏳ Pendiente</span>
                                @endif
                            @else
                                <span class="status-badge status-completed">📖 Disponible</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="empty-content">
                        <p>No hay contenido disponible aún.</p>
                        @if(Auth::user()->isAdmin() || (Auth::user()->isMaestro() && $curso->maestro_id === Auth::id()))
                            <a href="{{ route('contenidos.create', $curso) }}" class="btn-primary">Agregar Primer Contenido</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Actividades Tab -->
        <div id="actividades" class="content-section">
            <div class="content-list">
                @forelse($curso->actividades as $actividad)
                    <a href="{{ route('actividades.show', [$curso, $actividad]) }}" class="content-item">
                        <div class="content-icon">{{ $actividad->icono_tipo }}</div>
                        <div class="content-info">
                            <div class="content-title">{{ $actividad->titulo }}</div>
                            <div class="content-description">{{ $actividad->descripcion }}</div>
                        </div>
                        <div class="content-status">
                            @if(Auth::user()->isAlumno())
                                @php
                                    $respuesta = $actividad->respuestas()->where('estudiante_id', Auth::id())->first();
                                @endphp
                                @if($respuesta)
                                    <span class="status-badge {{ $respuesta->es_correcta ? 'status-completed' : 'status-pending' }}">
                                        {{ $respuesta->es_correcta ? '✅ Correcto' : '❌ Incorrecto' }}
                                    </span>
                                @else
                                    <span class="status-badge status-locked">🔒 Sin responder</span>
                                @endif
                            @else
                                <span class="status-badge status-completed">{{ $actividad->puntos }} pts</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="empty-content">
                        <p>No hay actividades disponibles aún.</p>
                        @if(Auth::user()->isAdmin() || (Auth::user()->isMaestro() && $curso->maestro_id === Auth::id()))
                            <a href="{{ route('actividades.create', $curso) }}" class="btn-primary">Crear Primera Actividad</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Estudiantes Tab (Solo para Admin/Maestro) -->
        @if(Auth::user()->isAdmin() || (Auth::user()->isMaestro() && $curso->maestro_id === Auth::id()))
        <div id="estudiantes" class="content-section">
            <div class="students-grid">
                @forelse($curso->estudiantes as $estudiante)
                    <div class="student-card">
                        <div class="student-avatar">{{ substr($estudiante->name, 0, 1) }}</div>
                        <div class="student-info">
                            <h4>{{ $estudiante->name }}</h4>
                            <p>{{ $estudiante->email }}</p>
                            <p>Matrícula: {{ $estudiante->matricula ?? 'N/A' }}</p>
                        </div>
                        <div class="student-stats">
                            @php
                                $progresoEstudiante = $curso->progreso()->where('estudiante_id', $estudiante->id)->where('completado', true)->count();
                                $totalContenido = $curso->contenidos->count() + $curso->actividades->count();
                                $porcentaje = $totalContenido > 0 ? round(($progresoEstudiante / $totalContenido) * 100) : 0;
                            @endphp
                            <div class="progress-circle" data-progress="{{ $porcentaje }}">{{ $porcentaje }}%</div>
                        </div>
                    </div>
                @empty
                    <div class="empty-content">
                        <p>No hay estudiantes inscritos en este curso.</p>
                    </div>
                @endforelse
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    document.querySelectorAll('.content-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all tabs and sections
            document.querySelectorAll('.content-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
            
            // Activate selected tab
            this.classList.add('active');
            const targetSection = document.getElementById(targetTab);
            if (targetSection) {
                targetSection.classList.add('active');
            }
        });
    });
});
</script>
@endpush