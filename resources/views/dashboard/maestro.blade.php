{{-- resources/views/dashboard/maestro.blade.php --}}
@extends('layouts.app')

@section('title', 'Panel Maestro')

@section('content')
<div class="dashboard-container">
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA">
            <h3>UHTA</h3>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard.maestro') }}" class="nav-item active">
                <i class="icon-info"></i>
                Mi información
            </a>
            <a href="{{ route('maestro.cursos') }}" class="nav-item">
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
            <div class="welcome-message">
                <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA">
                <div>
                    <h3>UMI</h3>
                    <p>SISTEMA DE GESTIÓN ESCOLAR</p>
                    <h2>¡Bienvenido(a) Maestro {{ Auth::user()->name }}!</h2>
                </div>
            </div>
        </div>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Mis Cursos</h3>
                <p class="stat-number">{{ $stats['total_cursos'] }}</p>
            </div>
            <div class="stat-card">
                <h3>Total Estudiantes</h3>
                <p class="stat-number">{{ $stats['total_estudiantes'] }}</p>
            </div>
            <div class="stat-card">
                <h3>Cursos Activos</h3>
                <p class="stat-number">{{ $stats['cursos_activos'] }}</p>
            </div>
        </div>

        @if($cursos->count() > 0)
        <div class="recent-courses" style="margin-top: 2rem;">
            <h3 style="margin-bottom: 1rem; color: #2c3e50;">Mis Cursos Recientes</h3>
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                @foreach($cursos->take(3) as $curso)
                <div style="padding: 1rem; border-bottom: 1px solid #e9ecef; display: flex; justify-content: between; align-items: center;">
                    <div>
                        <h4 style="color: #2c3e50; margin-bottom: 0.5rem;">{{ $curso->nombre }}</h4>
                        <p style="color: #6c757d; font-size: 0.9rem;">{{ $curso->codigo }} - {{ $curso->estudiantes->count() }} estudiantes</p>
                    </div>
                    <span style="padding: 0.25rem 0.75rem; background: {{ $curso->activo ? '#d4edda' : '#f8d7da' }}; color: {{ $curso->activo ? '#155724' : '#721c24' }}; border-radius: 20px; font-size: 0.8rem;">
                        {{ $curso->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection