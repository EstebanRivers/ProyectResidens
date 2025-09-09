@extends('layouts.dashboard')

@section('title', $curso->nombre)
@section('page-title', $curso->nombre)
@section('page-subtitle', $curso->codigo . ' - ' . $curso->periodo_academico)

@section('content')
<div class="course-detail">
    @if(Auth::user()->isAlumno())
        <!-- Progress Indicator for Students -->
        @php
            $totalContenido = $curso->contenidos->count() + $curso->actividades->count();
            $completado = 0;
            if (Auth::user()->cursosComoEstudiante()->where('curso_id', $curso->id)->exists()) {
                $completado = $curso->progreso()->where('estudiante_id', Auth::id())->where('completado', true)->count();
            }
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
                @if(!Auth::user()->cursosComoEstudiante()->where('curso_id', $curso->id)->exists())
                    <form method="POST" action="{{ route('cursos.inscribir', $curso) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-primary">📚 Inscribirse</button>
                    </form>
                @else
                    <span class="status-badge status-completed">✅ Inscrito</span>
                    
                    <!-- Botón para desinscribirse -->
                    <form action="{{ route('cursos.desinscribir', $curso->id) }}" method="POST" class="d-inline" 
                          onsubmit="return confirm('¿Estás seguro de que quieres desinscribirte de este curso?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Desinscribirse
                        </button>
                    </form>
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
                    @if(Auth::user()->isAlumno() && !Auth::user()->cursosComoEstudiante()->where('curso_id', $curso->id)->exists())
                        <div class="content-item locked" style="display: flex; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef; opacity: 0.6; cursor: not-allowed;">
                            <div class="content-icon" style="font-size: 1.5rem; margin-right: 1rem; width: 40px; text-align: center;">🔒</div>
                            <div class="content-info" style="flex: 1;">
                                <div class="content-title" style="font-weight: 600; color: #6c757d; margin-bottom: 0.25rem;">{{ $contenido->titulo }}</div>
                                <div class="content-description" style="font-size: 0.9rem; color: #6c757d;">Debes inscribirte para acceder</div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('contenidos.show', [$curso, $contenido]) }}" class="content-item" style="display: flex; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef; transition: all 0.3s ease; text-decoration: none; color: inherit;">
                            <div class="content-icon" style="font-size: 1.5rem; margin-right: 1rem; width: 40px; text-align: center;">{{ $contenido->icono_tipo }}</div>
                            <div class="content-info" style="flex: 1;">
                                <div class="content-title" style="font-weight: 600; color: #2c3e50; margin-bottom: 0.25rem;">{{ $contenido->titulo }}</div>
                                <div class="content-description" style="font-size: 0.9rem; color: #6c757d;">{{ $contenido->descripcion }}</div>
                            </div>
                            <div class="content-status">
                                @if(Auth::user()->isAlumno())
                                    @php
                                        $progreso = $curso->progreso()->where('estudiante_id', Auth::id())->where('contenido_id', $contenido->id)->first();
                                    @endphp
                                    @if($progreso && $progreso->completado)
                                        <span class="status-badge status-completed" style="padding: 0.25rem 0.75rem; background: #d4edda; color: #155724; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">✅ Completado</span>
                            
                            <!-- Botón para desinscribirse -->
                            <form action="{{ route('cursos.desinscribir', $curso->id) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('¿Estás seguro de que quieres desinscribirte de este curso?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-sign-out-alt"></i> Desinscribirse
                                </button>
                            </form>
                                    @else
                                        <span class="status-badge status-pending" style="padding: 0.25rem 0.75rem; background: #fff3cd; color: #856404; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">⏳ Pendiente</span>
                                    @endif
                                @else
                                    <span class="status-badge status-completed" style="padding: 0.25rem 0.75rem; background: #d4edda; color: #155724; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">📖 Disponible</span>
                                @endif
                            </div>
                        </a>
                    @endif
                @empty
                    <div class="empty-content" style="text-align: center; padding: 2rem; color: #6c757d;">
                        <p>No hay contenido disponible aún.</p>
                        @if(Auth::user()->isAdmin() || (Auth::user()->isMaestro() && $curso->maestro_id === Auth::