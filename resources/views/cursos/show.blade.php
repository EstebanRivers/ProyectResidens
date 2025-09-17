@extends('layouts.dashboard')

@section('title', $curso->nombre)
@section('page-title', $curso->nombre)
@section('page-subtitle', $curso->codigo . ' - ' . $curso->periodo_academico)

@section('content')
<div class="course-detail">
    @if(Auth::user()->isAlumno() && $estaInscrito)
        <!-- Progress Indicator for Students -->
        @php
            $totalContenido = $curso->contenidos->count() + $curso->actividades->count();
            $completado = $progreso ? $progreso->where('completado', true)->count() : 0;
            $porcentaje = $totalContenido > 0 ? round(($completado / $totalContenido) * 100) : 0;
        @endphp
        <div class="progress-indicator">
            <div class="progress-circle {{ $porcentaje == 100 ? 'completed' : ($porcentaje > 0 ? 'pending' : 'locked') }}">
                {{ $porcentaje }}%
            </div>
            <div>
                <strong>Tu Progreso en el Curso</strong>
                <div style="font-size: 0.9rem; color: #6c757d;">
                    {{ $completado }} de {{ $totalContenido }} elementos completados
                </div>
            </div>
        </div>
    @endif
    
    <!-- Course Header -->
    <div class="course-header" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 2rem;">
        <div class="course-info">
            <h1 style="color: #2c3e50; margin-bottom: 1rem;">{{ $curso->nombre }}</h1>
            <p style="color: #6c757d; margin-bottom: 1rem;">{{ $curso->descripcion }}</p>
            <div class="course-meta" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <span style="padding: 0.5rem 1rem; background: #e3f2fd; color: #1976d2; border-radius: 20px; font-size: 0.9rem;">👨‍🏫 {{ $curso->maestro->name }}</span>
                <span style="padding: 0.5rem 1rem; background: #f3e5f5; color: #7b1fa2; border-radius: 20px; font-size: 0.9rem;">📅 {{ $curso->periodo_academico }}</span>
                <span style="padding: 0.5rem 1rem; background: #fff3e0; color: #f57c00; border-radius: 20px; font-size: 0.9rem;">⭐ {{ $curso->creditos }} créditos</span>
                <span style="padding: 0.5rem 1rem; background: #e8f5e8; color: #2e7d32; border-radius: 20px; font-size: 0.9rem;">👥 {{ $curso->estudiantes->count() }}/{{ $curso->cupo_maximo }} estudiantes</span>
            </div>
        </div>
        
        <div class="course-actions" style="margin-top: 1rem; display: flex; gap: 1rem; flex-wrap: wrap;">
            @if(Auth::user()->isAdmin() || (Auth::user()->isMaestro() && $curso->maestro_id === Auth::id()))
                <a href="{{ route('contenidos.create', $curso) }}" class="btn-primary">➕ Agregar Contenido</a>
                <a href="{{ route('actividades.create', $curso) }}" class="btn-secondary">📝 Crear Actividad</a>
                <a href="{{ route('cursos.edit', $curso) }}" class="btn-outline">✏️ Editar Curso</a>
            @elseif(Auth::user()->isAlumno())
                @if(!$estaInscrito)
                    <button onclick="showInscriptionModal({{ $curso->id }}, '{{ $curso->nombre }}')" 
                            class="btn-primary">📚 Inscribirse</button>
                @else
                    <span class="status-badge status-completed">✅ Inscrito</span>
                    <button onclick="showUnsubscribeModal({{ $curso->id }}, '{{ $curso->nombre }}')" 
                            class="btn-outline" style="background: #f8d7da; color: #721c24; border-color: #f5c6cb;">
                        🚪 Desinscribirse
                    </button>
                @endif
            @endif
        </div>
    </div>

    <!-- Course Content -->
    <div class="course-content" style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); padding: 2rem;">
        <div class="content-tabs" style="display: flex; border-bottom: 2px solid #e9ecef; margin-bottom: 2rem;">
            <button class="content-tab active" data-tab="contenidos" style="padding: 1rem 1.5rem; background: none; border: none; cursor: pointer; font-size: 0.9rem; font-weight: 500; color: #ffa726; border-bottom: 2px solid #ffa726;">📚 Contenidos</button>
            <button class="content-tab" data-tab="actividades" style="padding: 1rem 1.5rem; background: none; border: none; cursor: pointer; font-size: 0.9rem; font-weight: 500; color: #6c757d; border-bottom: 2px solid transparent;">📝 Actividades</button>
            @if(Auth::user()->isAdmin() || (Auth::user()->isMaestro() && $curso->maestro_id === Auth::id()))
                <button class="content-tab" data-tab="estudiantes" style="padding: 1rem 1.5rem; background: none; border: none; cursor: pointer; font-size: 0.9rem; font-weight: 500; color: #6c757d; border-bottom: 2px solid transparent;">👥 Estudiantes</button>
            @endif
        </div>

        <!-- Contenidos Tab -->
        <div id="contenidos" class="content-section active">
            <div class="content-list" style="display: grid; gap: 1rem;">
                @forelse($curso->contenidos as $contenido)
                    @if(Auth::user()->isAlumno() && !$estaInscrito)
                        <div class="content-item locked" style="display: flex; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef; opacity: 0.6; cursor: not-allowed;">
                            <div class="content-icon" style="font-size: 1.5rem; margin-right: 1rem; width: 40px; text-align: center;">
                                @if($contenido->tipo === 'video')
                                    🎥
                                @elseif($contenido->tipo === 'texto')
                                    📄
                                @elseif($contenido->tipo === 'pdf')
                                    📋
                                @elseif($contenido->tipo === 'imagen')
                                    🖼️
                                @elseif($contenido->tipo === 'audio')
                                    🎵
                                @else
                                    📄
                                @endif
                            </div>
                            <div class="content-info" style="flex: 1;">
                                <div class="content-title" style="font-weight: 600; color: #6c757d; margin-bottom: 0.25rem;">{{ $contenido->titulo }}</div>
                                <div class="content-description" style="font-size: 0.9rem; color: #6c757d;">Debes inscribirte para acceder</div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('contenidos.show', $contenido) }}" class="content-item" style="display: flex; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.3s ease; text-decoration: none; color: inherit;">
                            <div class="content-icon" style="font-size: 1.5rem; margin-right: 1rem; width: 40px; text-align: center;">
                                @if($contenido->tipo === 'video')
                                    🎥
                                @elseif($contenido->tipo === 'texto')
                                    📄
                                @elseif($contenido->tipo === 'pdf')
                                    📋
                                @elseif($contenido->tipo === 'imagen')
                                    🖼️
                                @elseif($contenido->tipo === 'audio')
                                    🎵
                                @else
                                    📄
                                @endif
                            </div>
                            <div class="content-info" style="flex: 1;">
                                <div class="content-title" style="font-weight: 600; color: #2c3e50; margin-bottom: 0.25rem;">{{ $contenido->titulo }}</div>
                                <div class="content-description" style="font-size: 0.9rem; color: #6c757d;">{{ $contenido->descripcion }}</div>
                            </div>
                            <div class="content-status">
                                @if(Auth::user()->isAlumno() && $progreso && isset($progreso['contenido_' . $contenido->id]))
                                    @if($progreso['contenido_' . $contenido->id]->completado)
                                        <span class="status-badge status-completed" style="padding: 0.25rem 0.75rem; background: #d4edda; color: #155724; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">✅ Completado</span>
                                    @else
                                        <span class="status-badge status-pending" style="padding: 0.25rem 0.75rem; background: #fff3cd; color: #856404; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">⏳ En Progreso</span>
                                    @endif
                                @else
                                    <span class="status-badge status-available" style="padding: 0.25rem 0.75rem; background: #cce5ff; color: #0066cc; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">📖 Disponible</span>
                                @endif
                            </div>
                        </a>
                    @endif
                @empty
                    <div class="empty-content" style="text-align: center; padding: 2rem; color: #6c757d;">
                        <p>No hay contenido disponible aún.</p>
                        @if(Auth::user()->isAdmin() || (Auth::user()->isMaestro() && $curso->maestro_id === Auth::id()))
                            <a href="{{ route('contenidos.create', $curso) }}" class="btn-primary" style="margin-top: 1rem; display: inline-block; padding: 0.75rem 1.5rem; background: #1976d2; color: white; text-decoration: none; border-radius: 8px;">➕ Agregar Primer Contenido</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Actividades Tab -->
        <div id="actividades" class="content-section" style="display: none;">
            <div class="content-list" style="display: grid; gap: 1rem;">
                @forelse($curso->actividades as $actividad)
                    @if(Auth::user()->isAlumno() && !$estaInscrito)
                        <div class="content-item locked" style="display: flex; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef; opacity: 0.6; cursor: not-allowed;">
                            <div class="content-icon" style="font-size: 1.5rem; margin-right: 1rem; width: 40px; text-align: center;">🔒</div>
                            <div class="content-info" style="flex: 1;">
                                <div class="content-title" style="font-weight: 600; color: #6c757d; margin-bottom: 0.25rem;">{{ $actividad->titulo }}</div>
                                <div class="content-description" style="font-size: 0.9rem; color: #6c757d;">Debes inscribirte para acceder</div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('actividades.show', $actividad) }}" class="content-item" style="display: flex; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.3s ease; text-decoration: none; color: inherit;">
                            <div class="content-icon" style="font-size: 1.5rem; margin-right: 1rem; width: 40px; text-align: center;">
                                @if($actividad->tipo === 'opcion_multiple')
                                    ☑️
                                @elseif($actividad->tipo === 'verdadero_falso')
                                    ✅
                                @elseif($actividad->tipo === 'respuesta_corta')
                                    ✏️
                                @elseif($actividad->tipo === 'ensayo')
                                    📝
                                @else
                                    📝
                                @endif
                            </div>
                            <div class="content-info" style="flex: 1;">
                                <div class="content-title" style="font-weight: 600; color: #2c3e50; margin-bottom: 0.25rem;">{{ $actividad->titulo }}</div>
                                <div class="content-description" style="font-size: 0.9rem; color: #6c757d;">{{ $actividad->descripcion }}</div>
                                <div style="font-size: 0.8rem; color: #1976d2; margin-top: 0.25rem;">🎯 {{ $actividad->puntos }} puntos</div>
                            </div>
                            <div class="content-status">
                                @if(Auth::user()->isAlumno() && $progreso && isset($progreso['actividad_' . $actividad->id]))
                                    @if($progreso['actividad_' . $actividad->id]->completado)
                                        <span class="status-badge status-completed" style="padding: 0.25rem 0.75rem; background: #d4edda; color: #155724; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">✅ Completado</span>
                                    @else
                                        <span class="status-badge status-pending" style="padding: 0.25rem 0.75rem; background: #fff3cd; color: #856404; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">⏳ En Progreso</span>
                                    @endif
                                @else
                                    <span class="status-badge status-available" style="padding: 0.25rem 0.75rem; background: #cce5ff; color: #0066cc; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">📝 Disponible</span>
                                @endif
                            </div>
                        </a>
                    @endif
                @empty
                    <div class="empty-content" style="text-align: center; padding: 2rem; color: #6c757d;">
                        <p>No hay actividades disponibles aún.</p>
                        @if(Auth::user()->isAdmin() || (Auth::user()->isMaestro() && $curso->maestro_id === Auth::id()))
                            <a href="{{ route('actividades.create', $curso) }}" class="btn-primary" style="margin-top: 1rem; display: inline-block; padding: 0.75rem 1.5rem; background: #1976d2; color: white; text-decoration: none; border-radius: 8px;">➕ Crear Primera Actividad</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Estudiantes Tab (Solo para Admin y Maestros) -->
        @if(Auth::user()->isAdmin() || (Auth::user()->isMaestro() && $curso->maestro_id === Auth::id()))
        <div id="estudiantes" class="content-section" style="display: none;">
            <div class="students-list" style="display: grid; gap: 1rem;">
                @forelse($curso->estudiantes as $estudiante)
                    <div class="student-item" style="display: flex; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
                        <div class="student-avatar" style="width: 40px; height: 40px; background: #1976d2; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 1rem;">
                            {{ substr($estudiante->name, 0, 1) }}
                        </div>
                        <div class="student-info" style="flex: 1;">
                            <div class="student-name" style="font-weight: 600; color: #2c3e50;">{{ $estudiante->name }}</div>
                            <div class="student-email" style="font-size: 0.9rem; color: #6c757d;">{{ $estudiante->email }}</div>
                            @if($estudiante->matricula)
                                <div class="student-matricula" style="font-size: 0.8rem; color: #6c757d;">Matrícula: {{ $estudiante->matricula }}</div>
                            @endif
                        </div>
                        <div class="student-status">
                            <span style="padding: 0.25rem 0.75rem; background: #d4edda; color: #155724; border-radius: 20px; font-size: 0.8rem;">
                                {{ ucfirst($estudiante->pivot->estado) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="empty-content" style="text-align: center; padding: 2rem; color: #6c757d;">
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
// Funcionalidad de tabs
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.content-tab');
    const sections = document.querySelectorAll('.content-section');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remover clase activa de todos los tabs
            tabs.forEach(t => {
                t.style.color = '#6c757d';
                t.style.borderBottomColor = 'transparent';
            });
            
            // Ocultar todas las secciones
            sections.forEach(s => {
                s.style.display = 'none';
            });
            
            // Activar el tab seleccionado
            this.style.color = '#ffa726';
            this.style.borderBottomColor = '#ffa726';
            
            // Mostrar la sección correspondiente
            const targetSection = document.getElementById(targetTab);
            if (targetSection) {
                targetSection.style.display = 'block';
            }
        });
    });
});
</script>
@endpush
@endsection