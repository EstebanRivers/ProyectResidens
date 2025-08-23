{{-- resources/views/dashboard/alumno/perfil.blade.php --}}
@extends('layouts.app')

@section('title', 'Mi Perfil - Alumno')

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
            <a href="{{ route('alumno.cursos') }}" class="nav-item">
                <i class="icon-courses"></i>
                Mis Cursos
            </a>
            <a href="{{ route('alumno.calificaciones') }}" class="nav-item">
                <i class="icon-grades"></i>
                Mis Calificaciones
            </a>
            <a href="{{ route('alumno.perfil') }}" class="nav-item active">
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
            <h1 style="color: #2c3e50; margin-bottom: 2rem;">Mi Perfil</h1>
        </div>
        
        <div class="profile-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Información Personal -->
            <div class="profile-card" style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <h2 style="color: #2c3e50; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="icon-user"></i>
                    Información Personal
                </h2>
                
                <div class="profile-info">
                    <div class="info-item" style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #e9ecef;">
                        <label style="display: block; color: #6c757d; font-size: 0.9rem; margin-bottom: 0.25rem;">Nombre Completo:</label>
                        <span style="color: #2c3e50; font-weight: 500;">{{ $usuario->name }}</span>
                    </div>
                    
                    <div class="info-item" style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #e9ecef;">
                        <label style="display: block; color: #6c757d; font-size: 0.9rem; margin-bottom: 0.25rem;">Correo Electrónico:</label>
                        <span style="color: #2c3e50; font-weight: 500;">{{ $usuario->email }}</span>
                    </div>
                    
                    <div class="info-item" style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #e9ecef;">
                        <label style="display: block; color: #6c757d; font-size: 0.9rem; margin-bottom: 0.25rem;">Matrícula:</label>
                        <span style="color: #2c3e50; font-weight: 500;">{{ $usuario->matricula ?? 'No asignada' }}</span>
                    </div>
                    
                    <div class="info-item" style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #e9ecef;">
                        <label style="display: block; color: #6c757d; font-size: 0.9rem; margin-bottom: 0.25rem;">Teléfono:</label>
                        <span style="color: #2c3e50; font-weight: 500;">{{ $usuario->telefono ?? 'No registrado' }}</span>
                    </div>
                    
                    <div class="info-item">
                        <label style="display: block; color: #6c757d; font-size: 0.9rem; margin-bottom: 0.25rem;">Dirección:</label>
                        <span style="color: #2c3e50; font-weight: 500;">{{ $usuario->direccion ?? 'No registrada' }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Estadísticas Académicas -->
            <div class="academic-stats" style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <h2 style="color: #2c3e50; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="icon-stats"></i>
                    Estadísticas Académicas
                </h2>
                
                <div class="stats-grid" style="display: grid; gap: 1rem;">
                    <div class="stat-item" style="text-align: center; padding: 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; color: white;">
                        <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $cursosInscritos }}</div>
                        <div style="font-size: 0.9rem; opacity: 0.9;">Cursos Inscritos</div>
                    </div>
                    
                    <div class="stat-item" style="text-align: center; padding: 1.5rem; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; color: white;">
                        <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $usuario->calificaciones->count() }}</div>
                        <div style="font-size: 0.9rem; opacity: 0.9;">Calificaciones</div>
                    </div>
                    
                    <div class="stat-item" style="text-align: center; padding: 1.5rem; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; color: white;">
                        <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">
                            {{ $usuario->calificaciones->avg('calificacion') ? number_format($usuario->calificaciones->avg('calificacion'), 1) : 'N/A' }}
                        </div>
                        <div style="font-size: 0.9rem; opacity: 0.9;">Promedio General</div>
                    </div>
                </div>
                
                <div class="academic-status" style="margin-top: 2rem; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                    <h3 style="color: #2c3e50; margin-bottom: 1rem; font-size: 1.1rem;">Estado Académico</h3>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="width: 12px; height: 12px; background: #28a745; border-radius: 50%; display: inline-block;"></span>
                        <span style="color: #2c3e50; font-weight: 500;">Activo</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Botón de editar perfil -->
        <div style="margin-top: 2rem; text-align: center;">
            <button style="padding: 0.75rem 2rem; background: #1976d2; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 1rem;">
                Editar Perfil
            </button>
        </div>
    </div>
</div>
@endsection