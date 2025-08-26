{{-- resources/views/dashboard/maestro.blade.php --}}
@extends('layouts.app')

@section('title', 'Panel Maestro')

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
                <p class="section-subtitle">Información personal y datos del maestro</p>
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
                            <div class="info-label">Mis Cursos:</div>
                            <div class="info-value">{{ $stats['total_cursos'] ?? 0 }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Total Estudiantes:</div>
                            <div class="info-value">{{ $stats['total_estudiantes'] ?? 0 }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Cursos Activos:</div>
                            <div class="info-value">{{ $stats['cursos_activos'] ?? 0 }}</div>
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
                <p class="section-subtitle">Gestión de cursos y calificaciones</p>
            </div>
            <div class="course-management">
                <div class="course-header">
                    <h2 style="color: #2c3e50; margin: 0;">Mis Cursos</h2>
                    <div class="course-actions">
                        <a href="#" class="btn-primary" onclick="showCreateCourseForm()">
                            ➕ Crear Nuevo Curso
                        </a>
                    </div>
                </div>
                
                <!-- Formulario para crear curso -->
                <div id="create-course-form" style="display: none; background: #f8f9fa; padding: 2rem; border-radius: 12px; margin: 2rem 0;">
                    <h3 style="color: #2c3e50; margin-bottom: 1.5rem;">Crear Nuevo Curso</h3>
                    <form id="course-form" method="POST" action="/cursos">
                        @csrf
                        <input type="hidden" name="maestro_id" value="{{ Auth::id() }}">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Nombre del Curso</label>
                                <input type="text" name="nombre" class="form-input" required placeholder="Ej: Física General">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Código del Curso</label>
                                <input type="text" name="codigo" class="form-input" required placeholder="Ej: FIS101">
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
                
                <!-- Lista de cursos del maestro -->
                <div id="courses-list">
                    <div class="courses-grid">
                        <!-- Curso de ejemplo -->
                        <div class="course-card">
                            <div class="course-card-header">
                                <div>
                                    <h3 class="course-title">Física General</h3>
                                    <p class="course-code">FIS101</p>
                                </div>
                                <span class="course-status active">Activo</span>
                            </div>
                            <p class="course-description">Introducción a los conceptos fundamentales de la física: mecánica, termodinámica y ondas.</p>
                            <div class="course-stats">
                                <div class="course-stat">
                                    <div class="course-stat-number">18</div>
                                    <div class="course-stat-label">Estudiantes</div>
                                </div>
                                <div class="course-stat">
                                    <div class="course-stat-number">4</div>
                                    <div class="course-stat-label">Créditos</div>
                                </div>
                                <div class="course-stat">
                                    <div class="course-stat-number">8</div>
                                    <div class="course-stat-label">Lecciones</div>
                                </div>
                            </div>
                            <div class="course-actions-card">
                                <a href="#" class="btn-small btn-primary" onclick="showCourseContent()">Gestionar Contenido</a>
                                <a href="#" class="btn-small btn-outline">Editar Curso</a>
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
                
                <!-- Modal para gestionar contenido del curso -->
                <div id="course-content-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 12px; padding: 2rem; max-width: 800px; width: 90%; max-height: 80%; overflow-y: auto;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                            <h2 style="color: #2c3e50; margin: 0;">Gestionar Contenido - Física General</h2>
                            <button onclick="hideCourseContent()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">✕</button>
                        </div>
                        
                        <div class="content-tabs">
                            <button class="content-tab active" onclick="showContentTab('lessons')">📚 Lecciones</button>
                            <button class="content-tab" onclick="showContentTab('activities')">📝 Actividades</button>
                            <button class="content-tab" onclick="showContentTab('students')">👥 Estudiantes</button>
                        </div>
                        
                        <div id="lessons-content" class="tab-content">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <h3>Lecciones del Curso</h3>
                                <button class="btn-primary" onclick="showAddContentForm()">➕ Agregar Contenido</button>
                            </div>
                            
                            <div class="content-list">
                                <div class="content-item">
                                    <div class="content-icon">🎥</div>
                                    <div class="content-info">
                                        <div class="content-title">Introducción a la Mecánica</div>
                                        <div class="content-description">Video explicativo sobre conceptos básicos</div>
                                    </div>
                                    <div class="content-status">
                                        <span class="status-badge status-completed">Publicado</span>
                                    </div>
                                </div>
                                
                                <div class="content-item">
                                    <div class="content-icon">📄</div>
                                    <div class="content-info">
                                        <div class="content-title">Leyes de Newton</div>
                                        <div class="content-description">Documento PDF con ejercicios</div>
                                    </div>
                                    <div class="content-status">
                                        <span class="status-badge status-pending">Borrador</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="activities-content" class="tab-content" style="display: none;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <h3>Actividades y Evaluaciones</h3>
                                <button class="btn-primary" onclick="showAddActivityForm()">➕ Crear Actividad</button>
                            </div>
                            
                            <div class="content-list">
                                <div class="content-item">
                                    <div class="content-icon">☑️</div>
                                    <div class="content-info">
                                        <div class="content-title">Quiz: Conceptos Básicos</div>
                                        <div class="content-description">10 preguntas de opción múltiple</div>
                                    </div>
                                    <div class="content-status">
                                        <span class="status-badge status-completed">Activo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div id="students-content" class="tab-content" style="display: none;">
                            <h3>Estudiantes Inscritos (18)</h3>
                            <div style="margin-top: 1rem;">
                                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; margin-bottom: 0.5rem;">
                                    <strong>María González</strong> - Progreso: 75%
                                </div>
                                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; margin-bottom: 0.5rem;">
                                    <strong>Juan Pérez</strong> - Progreso: 60%
                                </div>
                                <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; margin-bottom: 0.5rem;">
                                    <strong>Ana López</strong> - Progreso: 90%
                                </div>
                            </div>
                        </div>
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
                <p class="section-subtitle">Gestión de cursos y calificaciones</p>
        <!-- Facturación Section -->
        <div id="facturacion" class="section-content">
            <div class="section-header">
                <h1 class="section-title">Facturación</h1>
                <p class="section-subtitle">Información de pagos y nómina</p>
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
                <p class="section-subtitle">Herramientas administrativas</p>
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
                <p>Contenido de la sección de cursos del maestro...</p>
            </div>
        </div>
<script src="{{ asset('js/dashboard.js') }}"></script>
@endsection