@extends('layouts.dashboard')

@section('title', $curso->nombre)
@section('page-title', $curso->nombre)
@section('page-subtitle', $curso->codigo . ' - ' . $curso->periodo_academico)

@section('content')
<div class="course-detail">
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
                    <div class="content-item" style="display: flex; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.3s ease; text-decoration: none; color: inherit;">
                        <div class="content-icon" style="font-size: 1.5rem; margin-right: 1rem; width: 40px; text-align: center;">{{ $contenido->icono_tipo }}</div>
                        <div class="content-info" style="flex: 1;">
                            <div class="content-title" style="font-weight: 600; color: #2c3e50; margin-bottom: 0.25rem;">{{ $contenido->titulo }}</div>
                            <div class="content-description" style="font-size: 0.9rem; color: #6c757d;">{{ $contenido->descripcion }}</div>
                        </div>
                        <div class="content-status">
                            @if(Auth::user()->isAlumno())
                                <span class="status-badge status-pending" style="padding: 0.25rem 0.75rem; background: #fff3cd; color: #856404; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">⏳ Pendiente</span>
                            @else
                                <span class="status-badge status-completed" style="padding: 0.25rem 0.75rem; background: #d4edda; color: #155724; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">📖 Disponible</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-content" style="text-align: center; padding: 2rem; color: #6c757d;">
                        <p>No hay contenido disponible aún.</p>
                        @if(Auth::user()->isAdmin() || (Auth::user()->isMaestro() && $curso->maestro_id === Auth::id()))
                            <a href="{{ route('contenidos.create', $curso) }}" class="btn-primary" style="margin-top: 1rem; display: inline-block; padding: 0.75rem 1.5rem; background: #1976d2; color: white; text-decoration: none; border-radius: 8px;">Agregar Primer Contenido</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Actividades Tab -->
        <div id="actividades" class="content-section" style="display: none;">
            <div class="content-list" style="display: grid; gap: 1rem;">
                @forelse($curso->actividades as $actividad)
                    <div class="content-item" style="display: flex; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                        <div class="content-icon" style="font-size: 1.5rem; margin-right: 1rem; width: 40px; text-align: center;">{{ $actividad->icono_tipo }}</div>
                        <div class="content-info" style="flex: 1;">
                            <div class="content-title" style="font-weight: 600; color: #2c3e50; margin-bottom: 0.25rem;">{{ $actividad->titulo }}</div>
                            <div class="content-description" style="font-size: 0.9rem; color: #6c757d;">{{ $actividad->descripcion }}</div>
                        </div>
                        <div class="content-status">
                            @if(Auth::user()->isAlumno())
                                @php
                                    $respuesta = $actividad->respuestas()->where('estudiante_id', Auth::id())->first();
                                @endphp
                                @if($respuesta)
                                    <span class="status-badge {{ $respuesta->es_correcta ? 'status-completed' : 'status-pending' }}" style="padding: 0.25rem 0.75rem; background: {{ $respuesta->es_correcta ? '#d4edda' : '#f8d7da' }}; color: {{ $respuesta->es_correcta ? '#155724' : '#721c24' }}; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">
                                        {{ $respuesta->es_correcta ? '✅ Correcto' : '❌ Incorrecto' }}
                                    </span>
                                @else
                                    <span class="status-badge status-locked" style="padding: 0.25rem 0.75rem; background: #f8d7da; color: #721c24; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">🔒 Sin responder</span>
                                @endif
                            @else
                                <span class="status-badge status-completed" style="padding: 0.25rem 0.75rem; background: #d4edda; color: #155724; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">{{ $actividad->puntos }} pts</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-content" style="text-align: center; padding: 2rem; color: #6c757d;">
                        <p>No hay actividades disponibles aún.</p>
                        @if(Auth::user()->isAdmin() || (Auth::user()->isMaestro() && $curso->maestro_id === Auth::id()))
                            <a href="{{ route('actividades.create', $curso) }}" class="btn-primary" style="margin-top: 1rem; display: inline-block; padding: 0.75rem 1.5rem; background: #1976d2; color: white; text-decoration: none; border-radius: 8px;">Crear Primera Actividad</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Estudiantes Tab (Solo para Admin/Maestro) -->
        @if(Auth::user()->isAdmin() || (Auth::user()->isMaestro() && $curso->maestro_id === Auth::id()))
        <div id="estudiantes" class="content-section" style="display: none;">
            <div class="students-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
                @forelse($curso->estudiantes as $estudiante)
                    <div class="student-card" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border: 1px solid #e9ecef;">
                        <div class="student-avatar" style="width: 50px; height: 50px; background: #1976d2; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-bottom: 1rem;">{{ substr($estudiante->name, 0, 1) }}</div>
                        <div class="student-info">
                            <h4 style="color: #2c3e50; margin-bottom: 0.5rem;">{{ $estudiante->name }}</h4>
                            <p style="color: #6c757d; font-size: 0.9rem;">{{ $estudiante->email }}</p>
                            <p style="color: #6c757d; font-size: 0.9rem;">Matrícula: {{ $estudiante->matricula ?? 'N/A' }}</p>
                        </div>
                        <div class="student-stats" style="margin-top: 1rem;">
                            @php
                                $progresoEstudiante = $curso->progreso()->where('estudiante_id', $estudiante->id)->where('completado', true)->count();
                                $totalContenido = $curso->contenidos->count() + $curso->actividades->count();
                                $porcentaje = $totalContenido > 0 ? round(($progresoEstudiante / $totalContenido) * 100) : 0;
                            @endphp
                            <div class="progress-circle" style="text-align: center; padding: 0.5rem; background: #e3f2fd; color: #1976d2; border-radius: 8px; font-weight: bold;">{{ $porcentaje }}% completado</div>
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
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    document.querySelectorAll('.content-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all tabs and sections
            document.querySelectorAll('.content-tab').forEach(t => {
                t.style.color = '#6c757d';
                t.style.borderBottomColor = 'transparent';
            });
            document.querySelectorAll('.content-section').forEach(s => s.style.display = 'none');
            
            // Activate selected tab
            this.style.color = '#ffa726';
            this.style.borderBottomColor = '#ffa726';
            const targetSection = document.getElementById(targetTab);
            if (targetSection) {
                targetSection.style.display = 'block';
            }
        });
    });
});
</script>
@endpush