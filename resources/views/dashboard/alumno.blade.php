{{-- resources/views/dashboard/alumno.blade.php --}}
@extends('layouts.app')

@section('title', 'Panel Alumno')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
@endsection

@section('content')
<div class="dashboard-container">
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA">
        </div>
        
        <nav class="sidebar-nav">
            <a href="#" class="nav-item info active" data-section="mi-informacion">
                Mi información
            </a>
            <a href="#" class="nav-item courses" data-section="cursos">
                Cursos
            </a>
            <a href="#" class="nav-item billing" data-section="facturacion">
                Facturación
            </a>
            <a href="#" class="nav-item admin" data-section="control-administrativo">
                Control Administrativo
            </a>
            <a href="#" class="nav-item settings" data-section="ajustes">
                Ajustes
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    Cerrar sesión
                </button>
            </form>
            <div class="footer-logo">
                <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA">
            </div>
        </div>
    </div>
    
    <div class="main-content">
        <div class="settings-icon"></div>
        
        <div class="content-area">
            <div class="welcome-section">
                <img src="{{ asset('images/uhta-logo.png') }}" alt="UMI" class="main-logo">
                <p class="welcome-text">¡Bienvenido(a) Usuario Master!</p>
            </div>
        </div>
        
        <!-- Mi Información Section -->
        <div id="mi-informacion" class="section-content">
            <div class="section-header">
                <h1 class="section-title">Mi Información</h1>
                <p class="section-subtitle">Información personal y académica</p>
            </div>
            <div class="mi-informacion-content">
                <div class="info-grid">
                    <div class="info-card">
                        <h3>Datos Personales</h3>
                        <div class="info-item">
                            <div class="info-label">Nombre:</div>
                            <div class="info-value">{{ Auth::user()->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Email:</div>
                            <div class="info-value">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Matrícula:</div>
                            <div class="info-value">{{ Auth::user()->matricula ?? 'No asignada' }}</div>
                        </div>
                    </div>
                    <div class="info-card">
                        <h3>Estadísticas Académicas</h3>
                        <div class="info-item">
                            <div class="info-label">Cursos Inscritos:</div>
                            <div class="info-value">{{ $stats['cursos_inscritos'] ?? 0 }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Promedio General:</div>
                            <div class="info-value">{{ $stats['promedio_general'] ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Calificaciones:</div>
                            <div class="info-value">{{ $stats['calificaciones_recientes'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>

        <!-- Cursos Section -->
        <div id="cursos" class="section-content">
            <div class="section-header">
                <h1 class="section-title">Mis Cursos</h1>
                <p class="section-subtitle">Cursos inscritos y calificaciones</p>
            </div>
            <div class="cursos-content">
                <p>Contenido de la sección de cursos del alumno...</p>
            </div>
        </div>
        </div>
    </div>
</div>

        <!-- Cursos Section -->
        <div id="cursos" class="section-content">
            <div class="section-header">
                <h1 class="section-title">Mis Cursos</h1>
                <p class="section-subtitle">Cursos inscritos y calificaciones</p>
        <!-- Facturación Section -->
        <div id="facturacion" class="section-content">
            <div class="section-header">
                <h1 class="section-title">Facturación</h1>
                <p class="section-subtitle">Pagos y estados de cuenta</p>
            </div>
            <div class="facturacion-content">
                <p>Contenido de la sección de facturación...</p>
            </div>
        </div>
            </div>
        <!-- Control Administrativo Section -->
        <div id="control-administrativo" class="section-content">
            <div class="section-header">
                <h1 class="section-title">Control Administrativo</h1>
                <p class="section-subtitle">Trámites y documentos</p>
            </div>
            <div class="control-administrativo-content">
                <p>Contenido de la sección de control administrativo...</p>
            </div>
        </div>
            <div class="cursos-content">
        <!-- Ajustes Section -->
        <div id="ajustes" class="section-content">
            <div class="section-header">
                <h1 class="section-title">Ajustes</h1>
                <p class="section-subtitle">Configuración personal</p>
            </div>
            <div class="ajustes-content">
                <div class="ajustes-tabs">
                    <button class="ajustes-tab active" data-tab="perfil-tab">Perfil</button>
                    <button class="ajustes-tab" data-tab="seguridad-tab">Seguridad</button>
                    <button class="ajustes-tab" data-tab="notificaciones-tab">Notificaciones</button>
                </div>
                
                <div id="perfil-tab" class="ajustes-section active">
                    <form id="perfil-form" class="ajustes-form" method="POST" action="#">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Nombre completo</label>
                            <input type="text" class="form-input" value="{{ Auth::user()->name }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-input" value="{{ Auth::user()->email }}">
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
                
                <div id="seguridad-tab" class="ajustes-section">
                    <form id="seguridad-form" class="ajustes-form" method="POST" action="#">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Contraseña actual</label>
                            <input type="password" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nueva contraseña</label>
                            <input type="password" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirmar contraseña</label>
                            <input type="password" class="form-input">
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Cambiar contraseña</button>
                        </div>
                    </form>
                </div>
                
                <div id="notificaciones-tab" class="ajustes-section">
                    <form id="notificaciones-form" class="ajustes-form" method="POST" action="#">
                        @csrf
                        <div class="checkbox-group">
                            <input type="checkbox" id="email-notifications" class="form-checkbox" checked>
                            <label for="email-notifications" class="checkbox-label">Recibir notificaciones por email</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="sms-notifications" class="form-checkbox">
                            <label for="sms-notifications" class="checkbox-label">Recibir notificaciones por SMS</label>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Guardar preferencias</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                <p>Contenido de la sección de cursos del alumno...</p>
            </div>
        </div>
<script src="{{ asset('js/dashboard.js') }}"></script>
@endsection