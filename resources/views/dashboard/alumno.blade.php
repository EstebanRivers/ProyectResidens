{{-- resources/views/dashboard/alumno.blade.php --}}
@extends('layouts.app')

@section('title', 'Panel Alumno')

@section('content')
<div class="dashboard-container">
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA">
            <h3>UHTA</h3>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard.alumno') }}" class="nav-item active">
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
            <div class="welcome-message">
                <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA">
                <div>
                    <h3>UMI</h3>
                    <p>SISTEMA DE GESTIÓN ESCOLAR</p>
                    <h2>¡Bienvenido(a) {{ Auth::user()->name }}!</h2>
                </div>
            </div>
        </div>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Cursos Inscritos</h3>
                <p class="stat-number">{{ $stats['cursos_inscritos'] }}</p>
            </div>
            <div class="stat-card">
                <h3>Promedio General</h3>
                <p class="stat-number">{{ $stats['promedio_general'] ?? 'N/A' }}</p>
            </div>
            <div class="stat-card">
                <h3>Calificaciones Recientes</h3>
                <p class="stat-number">{{ $stats['calificaciones_recientes'] }}</p>
            </div>
        </div>

        @if($calificaciones->count() > 0)
        <div class="recent-grades" style="margin-top: 2rem;">
            <h3 style="margin-bottom: 1rem; color: #2c3e50;">Calificaciones Recientes</h3>
            <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                @foreach($calificaciones as $calificacion)
                <div style="padding: 1rem; border-bottom: 1px solid #e9ecef; display: flex; justify-content: between; align-items: center;">
                    <div>
                        <h4 style="color: #2c3e50; margin-bottom: 0.5rem;">{{ $calificacion->curso->nombre }}</h4>
                        <p style="color: #6c757d; font-size: 0.9rem;">{{ $calificacion->tipo_evaluacion }} - {{ $calificacion->fecha_evaluacion->format('d/m/Y') }}</p>
                    </div>
                    <span style="padding: 0.5rem 1rem; background: {{ $calificacion->calificacion >= 70 ? '#d4edda' : '#f8d7da' }}; color: {{ $calificacion->calificacion >= 70 ? '#155724' : '#721c24' }}; border-radius: 20px; font-weight: bold;">
                        {{ $calificacion->calificacion }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection