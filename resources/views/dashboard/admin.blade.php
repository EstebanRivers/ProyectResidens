{{-- resources/views/dashboard/admin.blade.php --}}
@extends('layouts.app')

@section('title', 'Panel Administrador')

@section('content')
<div class="dashboard-container">
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA">
            <h3>UHTA</h3>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard.admin') }}" class="nav-item active">
                <i class="icon-info"></i>
                Mi información
            </a>
            <a href="{{ route('admin.cursos') }}" class="nav-item">
                <i class="icon-courses"></i>
                Cursos
            </a>
            <a href="{{ route('admin.usuarios') }}" class="nav-item">
                <i class="icon-users"></i>
                Facturación
            </a>
            <a href="{{ route('admin.reportes') }}" class="nav-item">
                <i class="icon-admin"></i>
                Control Administrativo
            </a>
            <a href="#" class="nav-item">
                <i class="icon-students"></i>
                Alumnos
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
                    <h2>¡Bienvenido(a) Usuario {{ Auth::user()->name }}!</h2>
                </div>
            </div>
        </div>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Total Estudiantes</h3>
                <p class="stat-number">1,245</p>
            </div>
            <div class="stat-card">
                <h3>Total Maestros</h3>
                <p class="stat-number">87</p>
            </div>
            <div class="stat-card">
                <h3>Cursos Activos</h3>
                <p class="stat-number">156</p>
            </div>
        </div>
    </div>
</div>
@endsection
