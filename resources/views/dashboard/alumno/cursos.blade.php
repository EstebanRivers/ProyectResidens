{{-- resources/views/dashboard/alumno/cursos.blade.php --}}
@extends('layouts.app')

@section('title', 'Mis Cursos - Alumno')

@section('content')
<div class="dashboard-container">
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA">
            <h3>UHTA</h3>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard.alumno') }}" class="nav-item">
                <i class="icon-info"></i>
                Mi información
            </a>
            <a href="{{ route('alumno.cursos') }}" class="nav-item active">
                <i class="icon-courses"></i>
                Mis Cursos
            </a>
            <a href="{{ route('alumno.calificaciones') }}" class="nav-item">
                <i class="icon-grades"></i>
                Mis Calificaciones
            </a>
            <a href="{{ route('alumno.perfil') }}" class="nav-item">
                <i class="icon-profile"></i>
                Mi Perfil
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
        
        @if($cursosInscritos->count() > 0)
            <div class="courses-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem;">
                @foreach($cursosInscritos as $curso)
                <div class="course-card" style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <div class="course-header" style="margin-bottom: 1rem;">
                        <h3 style="color: #2c3e50; margin-bottom: 0.5rem;">{{ $curso->nombre }}</h3>
                        <p style="color: #6c757d; font-size: 0.9rem;">{{ $curso->codigo }}</p>
                    </div>
                    
                    <p style="color: #6c757d; margin-bottom: 1rem; font-size: 0.9rem;">{{ $curso->descripcion }}</p>
                    
                    <div class="course-info" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div style="text-align: center; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                            <div style="font-size: 1.2rem; font-weight: bold; color: #1976d2;">{{ $curso->creditos }}</div>
                            <div style="font-size: 0.8rem; color: #6c757d;">Créditos</div>
                        </div>
                        <div style="text-align: center; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                            <div style="font-size: 1.2rem; font-weight: bold; color: #1976d2;">{{ $curso->periodo_academico }}</div>
                            <div style="font-size: 0.8rem; color: #6c757d;">Período</div>
                        </div>
                    </div>
                    
                    <div class="teacher-info" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; padding: 0.75rem; background: #f8f9fa; border-radius: 8px;">
                        <div style="width: 40px; height: 40px; background: #1976d2; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                            {{ substr($curso->maestro->name, 0, 1) }}
                        </div>
                        <div>
                            <div style="font-weight: 500; color: #2c3e50;">{{ $curso->maestro->name }}</div>
                            <div style="font-size: 0.8rem; color: #6c757d;">Profesor</div>
                        </div>
                    </div>
                    
                    <div class="enrollment-status" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <span style="font-size: 0.9rem; color: #6c757d;">Estado de inscripción:</span>
                        <span style="padding: 0.25rem 0.75rem; background: #d4edda; color: #155724; border-radius: 20px; font-size: 0.8rem;">
                            {{ ucfirst($curso->pivot->estado) }}
                        </span>
                    </div>
                    
                    <div style="font-size: 0.8rem; color: #6c757d; text-align: center;">
                        Inscrito el {{ \Carbon\Carbon::parse($curso->pivot->fecha_inscripcion)->format('d/m/Y') }}
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <h3 style="color: #6c757d; margin-bottom: 1rem;">No estás inscrito en ningún curso</h3>
                <p style="color: #6c757d;">Contacta al administrador para inscribirte en cursos.</p>
            </div>
        @endif
    </div>
</div>
@endsection