@extends('layouts.dashboard')

@section('title', 'Dashboard - ' . ucfirst(Auth::user()->role))
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Panel de control - ' . ucfirst(Auth::user()->role))

@section('content')
<div class="dashboard-sections">
    <!-- Welcome Section (Default) -->
    <div class="welcome-section active">
        <div class="welcome-content">
            <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA" class="welcome-logo">
            <p class="welcome-text">¡Bienvenido(a) {{ Auth::user()->name }}!</p>
        </div>
    </div>

    <!-- Mi Información Section -->
    <div id="mi-informacion" class="section-content">
        <div class="info-grid">
            <div class="info-card">
                <h3>Datos Personales</h3>
                <div class="info-items">
                    <div class="info-item">
                        <span class="info-label">Nombre:</span>
                        <span class="info-value">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Rol:</span>
                        <span class="info-value">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                    @if(Auth::user()->matricula)
                    <div class="info-item">
                        <span class="info-label">Matrícula:</span>
                        <span class="info-value">{{ Auth::user()->matricula }}</span>
                    </div>
                    @endif
                </div>
            </div>

            @if(Auth::user()->isAdmin())
            <div class="info-card">
                <h3>Estadísticas del Sistema</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\User::alumnos()->count() }}</div>
                        <div class="stat-label">Estudiantes</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\User::maestros()->count() }}</div>
                        <div class="stat-label">Maestros</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\Curso::activos()->count() }}</div>
                        <div class="stat-label">Cursos Activos</div>
                    </div>
                </div>
            </div>
            @elseif(Auth::user()->isMaestro())
            <div class="info-card">
                <h3>Mis Estadísticas</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">{{ Auth::user()->cursosComoMaestro()->count() }}</div>
                        <div class="stat-label">Mis Cursos</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ Auth::user()->cursosComoMaestro()->withCount('estudiantes')->get()->sum('estudiantes_count') }}</div>
                        <div class="stat-label">Estudiantes</div>
                    </div>
                </div>
            </div>
            @else
            <div class="info-card">
                <h3>Mi Progreso Académico</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">{{ Auth::user()->cursosComoEstudiante()->count() }}</div>
                        <div class="stat-label">Cursos Inscritos</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ Auth::user()->calificaciones()->count() }}</div>
                        <div class="stat-label">Calificaciones</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Cursos Section -->
    <div id="cursos" class="section-content">
        <div class="course-management">
            <div class="course-header">
                <div class="header-actions">
                    @if(Auth::user()->isAdmin() || Auth::user()->isMaestro())
                        <a href="{{ route('cursos.create') }}" class="btn btn-primary">
                            <span class="btn-icon">➕</span>
                            Crear Nuevo Curso
                        </a>
                        <a href="{{ route('cursos.index') }}" class="btn btn-secondary">
                            <span class="btn-icon">📋</span>
                            Gestionar Cursos
                        </a>
                    @else
                        <a href="{{ route('cursos.index') }}" class="btn btn-primary">
                            <span class="btn-icon">📚</span>
                            Ver Cursos Disponibles
                        </a>
                    @endif
                </div>
            </div>

            <div class="courses-grid">
                @if(Auth::user()->isAdmin())
                    @foreach(\App\Models\Curso::with(['maestro', 'estudiantes'])->latest()->take(6)->get() as $curso)
                        <div class="course-card">
                            <div class="course-header">
                                <div class="course-title-section">
                                    <h3 class="course-title">{{ $curso->nombre }}</h3>
                                    <p class="course-code">{{ $curso->codigo }}</p>
                                </div>
                                <span class="course-status {{ $curso->activo ? 'active' : 'inactive' }}">
                                    {{ $curso->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                            <p class="course-description">{{ Str::limit($curso->descripcion, 100) }}</p>
                            <div class="course-stats">
                                <div class="course-stat">
                                    <div class="stat-number">{{ $curso->estudiantes->count() }}</div>
                                    <div class="stat-label">Estudiantes</div>
                                </div>
                                <div class="course-stat">
                                    <div class="stat-number">{{ $curso->creditos }}</div>
                                    <div class="stat-label">Créditos</div>
                                </div>
                            </div>
                            <div class="course-teacher">
                                <strong>Profesor:</strong> {{ $curso->maestro->name }}
                            </div>
                            <div class="course-actions">
                                <a href="{{ route('cursos.show', $curso) }}" class="btn btn-primary btn-small">Ver Curso</a>
                                <a href="{{ route('cursos.edit', $curso) }}" class="btn btn-outline btn-small">Editar</a>
                            </div>
                        </div>
                    @endforeach
                @elseif(Auth::user()->isMaestro())
                    @foreach(Auth::user()->cursosComoMaestro()->with(['estudiantes'])->latest()->take(6)->get() as $curso)
                        <div class="course-card">
                            <div class="course-header">
                                <div class="course-title-section">
                                    <h3 class="course-title">{{ $curso->nombre }}</h3>
                                    <p class="course-code">{{ $curso->codigo }}</p>
                                </div>
                                <span class="course-status {{ $curso->activo ? 'active' : 'inactive' }}">
                                    {{ $curso->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                            <p class="course-description">{{ Str::limit($curso->descripcion, 100) }}</p>
                            <div class="course-stats">
                                <div class="course-stat">
                                    <div class="stat-number">{{ $curso->estudiantes->count() }}</div>
                                    <div class="stat-label">Estudiantes</div>
                                </div>
                                <div class="course-stat">
                                    <div class="stat-number">{{ $curso->creditos }}</div>
                                    <div class="stat-label">Créditos</div>
                                </div>
                            </div>
                            <div class="course-actions">
                                <a href="{{ route('cursos.show', $curso) }}" class="btn btn-primary btn-small">Ver Curso</a>
                                <a href="{{ route('cursos.edit', $curso) }}" class="btn btn-outline btn-small">Editar</a>
                            </div>
                        </div>
                    @endforeach
                @else
                    @foreach(\App\Models\Curso::activos()->with(['maestro'])->latest()->take(6)->get() as $curso)
                        <div class="course-card">
                            <div class="course-header">
                                <div class="course-title-section">
                                    <h3 class="course-title">{{ $curso->nombre }}</h3>
                                    <p class="course-code">{{ $curso->codigo }}</p>
                                </div>
                                @if(Auth::user()->cursosComoEstudiante()->where('curso_id', $curso->id)->exists())
                                    <span class="course-status enrolled">Inscrito</span>
                                @else
                                    <span class="course-status available">Disponible</span>
                                @endif
                            </div>
                            <p class="course-description">{{ Str::limit($curso->descripcion, 100) }}</p>
                            <div class="course-stats">
                                <div class="course-stat">
                                    <div class="stat-number">{{ $curso->estudiantes->count() }}/{{ $curso->cupo_maximo }}</div>
                                    <div class="stat-label">Cupo</div>
                                </div>
                                <div class="course-stat">
                                    <div class="stat-number">{{ $curso->creditos }}</div>
                                    <div class="stat-label">Créditos</div>
                                </div>
                            </div>
                            <div class="course-teacher">
                                <strong>Profesor:</strong> {{ $curso->maestro->name }}
                            </div>
                            <div class="course-actions">
                                @if(!Auth::user()->cursosComoEstudiante()->where('curso_id', $curso->id)->exists())
                                    <form method="POST" action="{{ route('cursos.inscribir', $curso) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-small">Inscribirse</button>
                                    </form>
                                @else
                                    <a href="{{ route('cursos.show', $curso) }}" class="btn btn-primary btn-small">Ver Curso</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
                
                @if(Auth::user()->isAdmin() || Auth::user()->isMaestro())
                    <div class="create-course-card" onclick="window.location.href='{{ route('cursos.create') }}'">
                        <div class="create-course-content">
                            <div class="create-course-icon">➕</div>
                            <h3>Crear Nuevo Curso</h3>
                            <p>Haz clic para crear un curso</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Facturación Section -->
    <div id="facturacion" class="section-content">
        <div class="info-card">
            <h3>Información de Facturación</h3>
            <p>Esta sección contendrá la información de pagos y facturación del sistema.</p>
            <div class="placeholder-content">
                <p>Funcionalidad en desarrollo...</p>
            </div>
        </div>
    </div>

    <!-- Control Administrativo Section -->
    <div id="control-administrativo" class="section-content">
        <div class="info-card">
            <h3>Control Administrativo</h3>
            @if(Auth::user()->isAdmin())
                <div class="admin-tools">
                    <button class="btn-primary">Gestionar Usuarios</button>
                    <button class="btn-primary">Reportes del Sistema</button>
                    <button class="btn-primary">Configuración Global</button>
                </div>
            @else
                <p>No tienes permisos para acceder a esta sección.</p>
            @endif
        </div>
    </div>

    <!-- Ajustes Section -->
    <div id="ajustes" class="section-content">
        <div class="ajustes-content">
            <div class="ajustes-tabs">
                <button class="ajustes-tab active" data-tab="perfil">Perfil</button>
                <button class="ajustes-tab" data-tab="seguridad">Seguridad</button>
                <button class="ajustes-tab" data-tab="notificaciones">Notificaciones</button>
            </div>

            <div id="perfil" class="ajustes-section active">
                <form class="ajustes-form" id="perfil-form">
                    <div class="form-group">
                        <label class="form-label">Nombre completo</label>
                        <input type="text" class="form-input" value="{{ Auth::user()->name }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" class="form-input" value="{{ Auth::user()->email }}">
                    </div>
                    @if(Auth::user()->matricula)
                    <div class="form-group">
                        <label class="form-label">Matrícula</label>
                        <input type="text" class="form-input" value="{{ Auth::user()->matricula }}">
                    </div>
                    @endif
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>

            <div id="seguridad" class="ajustes-section">
                <form class="ajustes-form" id="seguridad-form">
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

            <div id="notificaciones" class="ajustes-section">
                <form class="ajustes-form" id="notificaciones-form">
                    <div class="checkbox-group">
                        <input type="checkbox" class="form-checkbox" id="email-notifications" checked>
                        <label class="checkbox-label" for="email-notifications">Recibir notificaciones por email</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" class="form-checkbox" id="course-updates">
                        <label class="checkbox-label" for="course-updates">Notificaciones de actualizaciones de cursos</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" class="form-checkbox" id="grade-notifications" checked>
                        <label class="checkbox-label" for="grade-notifications">Notificaciones de calificaciones</label>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Guardar preferencias</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/dashboard/main.js') }}"></script>
@endpush