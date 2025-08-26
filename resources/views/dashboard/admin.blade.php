@extends('layouts.app')

@section('title', 'Panel Administrador')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
<div class="dashboard-container">
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/LOGO2.png') }}" alt="UHTA">
        </div>
        
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active" data-section="mi-informacion">
                Mi información
            </a>
            <a href="#" class="nav-item" data-section="cursos">
                Cursos
            </a>
            <a href="#" class="nav-item" data-section="facturacion">
                Facturación
            </a>
            <a href="#" class="nav-item" data-section="control-administrativo">
                Control Administrativo
            </a>
            <a href="#" class="nav-item" data-section="ajustes">
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
                <img src="{{ asset('images/LOGO3.png') }}" alt="UHTA2">
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
                <p class="section-subtitle">Información personal y datos del usuario</p>
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
                            <div class="info-label">Rol:</div>
                            <div class="info-value">{{ ucfirst(Auth::user()->role) }}</div>
                        </div>
                    </div>
                    <div class="info-card">
                        <h3>Estadísticas</h3>
                        <div class="info-item">
                            <div class="info-label">Total Estudiantes:</div>
                            <div class="info-value">{{ $stats['total_estudiantes'] ?? 0 }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Total Maestros:</div>
                            <div class="info-value">{{ $stats['total_maestros'] ?? 0 }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Cursos Activos:</div>
                            <div class="info-value">{{ $stats['cursos_activos'] ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cursos Section -->
        <div id="cursos" class="section-content">
            <div class="section-header">
                <h1 class="section-title">Cursos</h1>
                <p class="section-subtitle">Gestión de cursos y materias</p>
            </div>
            <div class="course-management">
                <div class="course-header">
                    <h2 style="color: #2c3e50; margin: 0;">Gestión de Cursos</h2>
                    <div class="course-actions">
                        <a href="#" class="btn-primary" onclick="showCreateCourseForm()">
                            ➕ Crear Nuevo Curso
                        </a>
                        <a href="#" class="btn-secondary" onclick="loadAllCourses()">
                            📚 Ver Todos los Cursos
                        </a>
                    </div>
                </div>
                
                <!-- Formulario para crear curso -->
                <div id="create-course-form" style="display: none; background: #f8f9fa; padding: 2rem; border-radius: 12px; margin: 2rem 0;">
                    <h3 style="color: #2c3e50; margin-bottom: 1.5rem;">Crear Nuevo Curso</h3>
                    <form id="course-form" method="POST" action="/cursos">
                        @csrf
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Nombre del Curso</label>
                                <input type="text" name="nombre" class="form-input" required placeholder="Ej: Matemáticas Básicas">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Código del Curso</label>
                                <input type="text" name="codigo" class="form-input" required placeholder="Ej: MAT101">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Créditos</label>
                                <select name="creditos" class="form-select" required>
                                    <option value="">Seleccionar créditos</option>
                                    <option value="1">1 Crédito</option>
                                    <option value="2">2 Créditos</option>
                                    <option value="3">3 Créditos</option>
                                    <option value="4">4 Créditos</option>
                                    <option value="5">5 Créditos</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Maestro Asignado</label>
                                <select name="maestro_id" class="form-select" required>
                                    <option value="">Seleccionar maestro</option>
                                    <option value="{{ Auth::id() }}">{{ Auth::user()->name }} (Yo)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cupo Máximo</label>
                                <input type="number" name="cupo_maximo" class="form-input" value="30" min="1" max="100">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Período Académico</label>
                                <input type="text" name="periodo_academico" class="form-input" value="2025-1" placeholder="Ej: 2025-1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-textarea" rows="4" placeholder="Describe el contenido y objetivos del curso..."></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Crear Curso</button>
                            <button type="button" class="btn-secondary" onclick="hideCreateCourseForm()">Cancelar</button>
                        </div>
                    </form>
                </div>
                
                <!-- Lista de cursos -->
                <div id="courses-list">
                    <div class="courses-grid">
                        <!-- Curso de ejemplo -->
                        <div class="course-card">
                            <div class="course-card-header">
                                <div>
                                    <h3 class="course-title">Matemáticas I</h3>
                                    <p class="course-code">MAT101</p>
                                </div>
                                <span class="course-status active">Activo</span>
                            </div>
                            <p class="course-description">Curso básico de matemáticas que cubre álgebra, geometría y trigonometría fundamental.</p>
                            <div class="course-stats">
                                <div class="course-stat">
                                    <div class="course-stat-number">25</div>
                                    <div class="course-stat-label">Estudiantes</div>
                                </div>
                                <div class="course-stat">
                                    <div class="course-stat-number">4</div>
                                    <div class="course-stat-label">Créditos</div>
                                </div>
                                <div class="course-stat">
                                    <div class="course-stat-number">12</div>
                                    <div class="course-stat-label">Lecciones</div>
                                </div>
                            </div>
                            <div class="course-progress">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 75%"></div>
                                </div>
                                <div class="progress-text">75% completado por los estudiantes</div>
                            </div>
                            <div class="course-actions-card">
                                <a href="#" class="btn-small btn-primary">Ver Curso</a>
                                <a href="#" class="btn-small btn-outline">Editar</a>
                            </div>
                        </div>
                        
                        <!-- Tarjeta para crear nuevo curso -->
                        <div class="course-card" style="border: 2px dashed #ddd; display: flex; align-items: center; justify-content: center; min-height: 300px; cursor: pointer;" onclick="showCreateCourseForm()">
                            <div style="text-align: center; color: #6c757d;">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">➕</div>
                                <h3>Crear Nuevo Curso</h3>
                                <p>Haz clic para agregar un nuevo curso</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Facturación Section -->
        <div id="facturacion" class="section-content">
            <div class="section-header">
                <h1 class="section-title">Facturación</h1>
                <p class="section-subtitle">Gestión de pagos y facturación</p>
            </div>
            <div class="facturacion-content">
                <p>Contenido de la sección de facturación...</p>
            </div>
        </div>

        <!-- Control Administrativo Section -->
        <div id="control-administrativo" class="section-content">
            <div class="section-header">
                <h1 class="section-title">Control Administrativo</h1>
                <p class="section-subtitle">Herramientas administrativas y reportes</p>
            </div>
            <div class="control-administrativo-content">
                <p>Contenido de la sección de control administrativo...</p>
            </div>
        </div>

        <!-- Ajustes Section -->
        <div id="ajustes" class="section-content">
            <div class="section-header">
                <h1 class="section-title">Ajustes</h1>
                <p class="section-subtitle">Configuración del sistema y preferencias</p>
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
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection