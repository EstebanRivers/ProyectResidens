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
                    @if(Auth::user()->telefono)
                    <div class="info-item">
                        <span class="info-label">Teléfono:</span>
                        <span class="info-value">{{ Auth::user()->telefono }}</span>
                    </div>
                    @endif
                    @if(Auth::user()->direccion)
                    <div class="info-item">
                        <span class="info-label">Dirección:</span>
                        <span class="info-value">{{ Auth::user()->direccion }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="info-card">
                <h3>Estadísticas</h3>
                <div class="stats-grid">
                    @if(Auth::user()->isAdmin())
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
                        <div class="stat-item">
                            <div class="stat-number">{{ \App\Models\Calificacion::count() }}</div>
                            <div class="stat-label">Calificaciones</div>
                        </div>
                    @elseif(Auth::user()->isMaestro())
                        @php
                            $cursos = Auth::user()->cursosComoMaestro()->with('estudiantes')->get();
                        @endphp
                        <div class="stat-item">
                            <div class="stat-number">{{ $cursos->count() }}</div>
                            <div class="stat-label">Mis Cursos</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $cursos->sum(fn($curso) => $curso->estudiantes->count()) }}</div>
                            <div class="stat-label">Estudiantes</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ $cursos->where('activo', true)->count() }}</div>
                            <div class="stat-label">Cursos Activos</div>
                        </div>
                    @else
                        <div class="stat-item">
                            <div class="stat-number">{{ Auth::user()->cursosComoEstudiante->count() }}</div>
                            <div class="stat-label">Cursos Inscritos</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ Auth::user()->calificaciones()->avg('calificacion') ? round(Auth::user()->calificaciones()->avg('calificacion'), 1) : 'N/A' }}</div>
                            <div class="stat-label">Promedio</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ Auth::user()->calificaciones()->count() }}</div>
                            <div class="stat-label">Calificaciones</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Cursos Section -->
    <div id="cursos" class="section-content">
        @if(Auth::user()->isAdmin())
            @include('dashboard.sections.cursos.administrador')
        @elseif(Auth::user()->isMaestro())
            @include('dashboard.sections.cursos.maestro')
        @else
            @include('dashboard.sections.cursos.alumno')
        @endif
    </div>

    <!-- Facturación Section -->
    <div id="facturacion" class="section-content">
        <div class="info-card">
            <h3>Información de Facturación</h3>
            <p style="color: #6c757d; margin-bottom: 2rem;">Esta sección contendrá la información de pagos y facturación del sistema.</p>
            
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">$0.00</div>
                    <div class="stat-label">Balance Actual</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">0</div>
                    <div class="stat-label">Facturas Pendientes</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">$0.00</div>
                    <div class="stat-label">Total Pagado</div>
                </div>
            </div>
            
            <div style="margin-top: 2rem; padding: 1.5rem; background: #f8f9fa; border-radius: 8px; text-align: center;">
                <p style="color: #6c757d;">No hay transacciones registradas</p>
            </div>
        </div>
    </div>

    <!-- Control Administrativo Section -->
    <div id="control-administrativo" class="section-content">
        <div class="info-card">
            <h3>Control Administrativo</h3>
            <p style="color: #6c757d; margin-bottom: 2rem;">Herramientas administrativas y reportes del sistema.</p>
            
            @if(Auth::user()->isAdmin())
                <div class="stats-grid" style="margin-bottom: 2rem;">
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\User::count() }}</div>
                        <div class="stat-label">Total Usuarios</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\Curso::count() }}</div>
                        <div class="stat-label">Total Cursos</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\Contenido::count() }}</div>
                        <div class="stat-label">Contenidos</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\Actividad::count() }}</div>
                        <div class="stat-label">Actividades</div>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <button class="btn-primary" style="padding: 1rem; background: #1976d2; color: white; border: none; border-radius: 8px; cursor: pointer;">
                        Generar Reportes
                    </button>
                    <button class="btn-secondary" style="padding: 1rem; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer;">
                        Gestionar Usuarios
                    </button>
                    <button class="btn-secondary" style="padding: 1rem; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer;">
                        Gestionar Cursos
                    </button>
                    <button class="btn-secondary" style="padding: 1rem; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer;">
                        Configuración Sistema
                    </button>
                </div>
            @else
                <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 8px;">
                    <p style="color: #6c757d;">No tienes permisos para acceder a las herramientas administrativas.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Ajustes Section -->
    <div id="ajustes" class="section-content">
        <div class="info-card">
            <div class="ajustes-tabs" style="display: flex; border-bottom: 2px solid #e9ecef; margin-bottom: 2rem;">
                <button class="ajustes-tab active" data-tab="perfil-tab" style="padding: 1rem 1.5rem; background: none; border: none; cursor: pointer; font-size: 0.9rem; font-weight: 500; color: #ffa726; border-bottom: 2px solid #ffa726;">Perfil</button>
                <button class="ajustes-tab" data-tab="seguridad-tab" style="padding: 1rem 1.5rem; background: none; border: none; cursor: pointer; font-size: 0.9rem; font-weight: 500; color: #666; border-bottom: 2px solid transparent;">Seguridad</button>
                <button class="ajustes-tab" data-tab="notificaciones-tab" style="padding: 1rem 1.5rem; background: none; border: none; cursor: pointer; font-size: 0.9rem; font-weight: 500; color: #666; border-bottom: 2px solid transparent;">Notificaciones</button>
            </div>
            
            <div id="perfil-tab" class="ajustes-section active">
                <form class="ajustes-form" method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.9rem;">Nombre completo</label>
                        <input type="text" name="name" class="form-input" value="{{ Auth::user()->name }}" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.9rem;">Email</label>
                        <input type="email" name="email" class="form-input" value="{{ Auth::user()->email }}" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                    </div>
                    @if(Auth::user()->isAlumno())
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.9rem;">Matrícula</label>
                        <input type="text" name="matricula" class="form-input" value="{{ Auth::user()->matricula }}" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                    </div>
                    @endif
                    <div class="form-actions" style="margin-top: 2rem; display: flex; gap: 1rem;">
                        <button type="submit" class="btn-primary" style="padding: 0.75rem 1.5rem; background: #ffa726; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 0.9rem; font-weight: 500;">Guardar cambios</button>
                    </div>
                </form>
            </div>
            
            <div id="seguridad-tab" class="ajustes-section" style="display: none;">
                <form class="ajustes-form" method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.9rem;">Contraseña actual</label>
                        <input type="password" name="current_password" class="form-input" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.9rem;">Nueva contraseña</label>
                        <input type="password" name="password" class="form-input" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.9rem;">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-input" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                    </div>
                    <div class="form-actions" style="margin-top: 2rem; display: flex; gap: 1rem;">
                        <button type="submit" class="btn-primary" style="padding: 0.75rem 1.5rem; background: #ffa726; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 0.9rem; font-weight: 500;">Cambiar contraseña</button>
                    </div>
                </form>
            </div>
            
            <div id="notificaciones-tab" class="ajustes-section" style="display: none;">
                <form class="ajustes-form">
                    <div class="checkbox-group" style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <input type="checkbox" id="email-notifications" class="form-checkbox" checked style="margin-right: 0.5rem;">
                        <label for="email-notifications" class="checkbox-label" style="font-weight: normal; margin-bottom: 0; cursor: pointer;">Recibir notificaciones por email</label>
                    </div>
                    <div class="checkbox-group" style="display: flex; align-items: center; margin-bottom: 1rem;">
                        <input type="checkbox" id="sms-notifications" class="form-checkbox" style="margin-right: 0.5rem;">
                        <label for="sms-notifications" class="checkbox-label" style="font-weight: normal; margin-bottom: 0; cursor: pointer;">Recibir notificaciones por SMS</label>
                    </div>
                    <div class="form-actions" style="margin-top: 2rem; display: flex; gap: 1rem;">
                        <button type="submit" class="btn-primary" style="padding: 0.75rem 1.5rem; background: #ffa726; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 0.9rem; font-weight: 500;">Guardar preferencias</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Settings tabs functionality
    document.querySelectorAll('.ajustes-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all tabs and sections
            document.querySelectorAll('.ajustes-tab').forEach(t => {
                t.style.color = '#666';
                t.style.borderBottomColor = 'transparent';
            });
            document.querySelectorAll('.ajustes-section').forEach(s => s.style.display = 'none');
            
            // Activate selected tab
            this.style.color = '#ffa726';
            this.style.borderBottomColor = '#ffa726';
            const targetSection = document.getElementById(targetTab);
            if (targetSection) {
                targetSection.style.display = 'block';
            }
        });
    });
});
</script>
@endpush
@endsection