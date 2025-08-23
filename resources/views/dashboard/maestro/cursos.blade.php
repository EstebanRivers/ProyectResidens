{{-- resources/views/dashboard/maestro/cursos.blade.php --}}
@extends('layouts.app')

@section('title', 'Mis Cursos - Maestro')

@section('content')
<div class="dashboard-container">
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA">
            <h3>UHTA</h3>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard.maestro') }}" class="nav-item">
                <i class="icon-info"></i>
                Mi información
            </a>
            <a href="{{ route('maestro.cursos') }}" class="nav-item active">
                <i class="icon-courses"></i>
                Mis Cursos
            </a>
            <a href="{{ route('maestro.calificaciones') }}" class="nav-item">
                <i class="icon-grades"></i>
                Calificaciones
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="icon-logout"></i>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>
    
    <div class="main-content">
        <div class="content-header">
            <h1 style="color: #2c3e50; margin-bottom: 2rem;">Mis Cursos</h1>
        </div>
        
        @if($cursos->count() > 0)
            <div class="courses-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
                @foreach($cursos as $curso)
                <div class="course-card" style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <div class="course-header" style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div>
                            <h3 style="color: #2c3e50; margin-bottom: 0.5rem;">{{ $curso->nombre }}</h3>
                            <p style="color: #6c757d; font-size: 0.9rem;">{{ $curso->codigo }}</p>
                        </div>
                        <span style="padding: 0.25rem 0.75rem; background: {{ $curso->activo ? '#d4edda' : '#f8d7da' }}; color: {{ $curso->activo ? '#155724' : '#721c24' }}; border-radius: 20px; font-size: 0.8rem;">
                            {{ $curso->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    
                    <p style="color: #6c757d; margin-bottom: 1rem; font-size: 0.9rem;">{{ $curso->descripcion }}</p>
                    
                    <div class="course-stats" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div style="text-align: center; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                            <div style="font-size: 1.5rem; font-weight: bold; color: #1976d2;">{{ $curso->estudiantes->count() }}</div>
                            <div style="font-size: 0.8rem; color: #6c757d;">Estudiantes</div>
                        </div>
                        <div style="text-align: center; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                            <div style="font-size: 1.5rem; font-weight: bold; color: #1976d2;">{{ $curso->creditos }}</div>
                            <div style="font-size: 0.8rem; color: #6c757d;">Créditos</div>
                        </div>
                    </div>
                    
                    <div class="course-actions" style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('maestro.calificaciones') }}" style="flex: 1; padding: 0.5rem; background: #1976d2; color: white; text-decoration: none; border-radius: 6px; text-align: center; font-size: 0.9rem;">
                            Ver Calificaciones
                        </a>
                    </div>
                    
                    @if($curso->estudiantes->count() > 0)
                    <div class="students-preview" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e9ecef;">
                        <h4 style="color: #2c3e50; font-size: 0.9rem; margin-bottom: 0.5rem;">Estudiantes Inscritos:</h4>
                        <div style="max-height: 100px; overflow-y: auto;">
                            @foreach($curso->estudiantes->take(3) as $estudiante)
                            <div style="padding: 0.25rem 0; font-size: 0.8rem; color: #6c757d;">
                                • {{ $estudiante->name }} ({{ $estudiante->matricula }})
                            </div>
                            @endforeach
                            @if($curso->estudiantes->count() > 3)
                            <div style="padding: 0.25rem 0; font-size: 0.8rem; color: #1976d2;">
                                + {{ $curso->estudiantes->count() - 3 }} más...
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <h3 style="color: #6c757d; margin-bottom: 1rem;">No tienes cursos asignados</h3>
                <p style="color: #6c757d;">Contacta al administrador para que te asigne cursos.</p>
            </div>
        @endif
    </div>
</div>
@endsection