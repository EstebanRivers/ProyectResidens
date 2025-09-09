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
                </div>
            </div>
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
                    @endif
                </div>
            </div>

            <div class="courses-grid">
                @if(Auth::user()->isAdmin())
                    @foreach(\App\Models\Curso::with(['maestro', 'estudiantes'])->get() as $curso)
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
                            <p class="course-description">{{ $curso->descripcion }}</p>
                            <div class="course-actions">
                                <a href="{{ route('cursos.show', $curso) }}" class="btn btn-primary btn-small">Ver Curso</a>
                                <a href="{{ route('cursos.edit', $curso) }}" class="btn btn-outline btn-small">Editar</a>
                            </div>
                        </div>
                    @endforeach
                @elseif(Auth::user()->isMaestro())
                    @foreach(\App\Models\Curso::where('maestro_id', Auth::id())->with(['estudiantes'])->get() as $curso)
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
                            <p class="course-description">{{ $curso->descripcion }}</p>
                            <div class="course-actions">
                                <a href="{{ route('cursos.show', $curso) }}" class="btn btn-primary btn-small">Ver Curso</a>
                                <a href="{{ route('cursos.edit', $curso) }}" class="btn btn-outline btn-small">Editar</a>
                            </div>
                        </div>
                    @endforeach
                @else
                    @foreach(\App\Models\Curso::activos()->with(['maestro'])->get() as $curso)
                        <div class="course-card">
                            <div class="course-header">
                                <div class="course-title-section">
                                    <h3 class="course-title">{{ $curso->nombre }}</h3>
                                    <p class="course-code">{{ $curso->codigo }}</p>
                                </div>
                                <span class="course-status available">Disponible</span>
                            </div>
                            <p class="course-description">{{ $curso->descripcion }}</p>
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

    <!-- Otras secciones -->
    <div id="facturacion" class="section-content">
        <div class="info-card">
            <h3>Información de Facturación</h3>
            <p>Esta sección contendrá la información de pagos y facturación del sistema.</p>
        </div>
    </div>

    <div id="control-administrativo" class="section-content">
        <div class="info-card">
            <h3>Control Administrativo</h3>
            <p>Herramientas administrativas y reportes del sistema.</p>
        </div>
    </div>

    <div id="ajustes" class="section-content">
        <div class="info-card">
            <h3>Configuración</h3>
            <p>Ajustes de perfil y configuración del sistema.</p>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/dashboard/main.js') }}"></script>
@endpush