{{-- resources/views/dashboard/maestro/calificaciones.blade.php --}}
@extends('layouts.app')

@section('title', 'Calificaciones - Maestro')

@section('content')
<div class="dashboard-container">
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA">
            <h3>UHTA</h3>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard.maestro') }}" class="nav-item">
                <i class="icon-info"></i>
                Mi información
            </a>
            <a href="{{ route('maestro.cursos') }}" class="nav-item">
                <i class="icon-courses"></i>
                Mis Cursos
            </a>
            <a href="{{ route('maestro.calificaciones') }}" class="nav-item active">
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
            <h1 style="color: #2c3e50; margin-bottom: 2rem;">Gestión de Calificaciones</h1>
        </div>

        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif
        
        @if($cursos->count() > 0)
            @foreach($cursos as $curso)
            <div class="course-section" style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 2rem;">
                <div class="course-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #e9ecef;">
                    <div>
                        <h2 style="color: #2c3e50; margin-bottom: 0.5rem;">{{ $curso->nombre }}</h2>
                        <p style="color: #6c757d;">{{ $curso->codigo }} - {{ $curso->estudiantes->count() }} estudiantes</p>
                    </div>
                </div>
                
                @if($curso->estudiantes->count() > 0)
                    <!-- Formulario para registrar nueva calificación -->
                    <div class="grade-form" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
                        <h3 style="color: #2c3e50; margin-bottom: 1rem;">Registrar Nueva Calificación</h3>
                        <form method="POST" action="{{ route('maestro.calificaciones.store') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                            @csrf
                            <input type="hidden" name="curso_id" value="{{ $curso->id }}">
                            
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; color: #2c3e50; font-weight: 500;">Estudiante:</label>
                                <select name="estudiante_id" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                                    <option value="">Seleccionar estudiante</option>
                                    @foreach($curso->estudiantes as $estudiante)
                                        <option value="{{ $estudiante->id }}">{{ $estudiante->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; color: #2c3e50; font-weight: 500;">Tipo:</label>
                                <select name="tipo_evaluacion" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                                    <option value="">Seleccionar tipo</option>
                                    <option value="examen">Examen</option>
                                    <option value="tarea">Tarea</option>
                                    <option value="proyecto">Proyecto</option>
                                    <option value="participacion">Participación</option>
                                    <option value="final">Final</option>
                                </select>
                            </div>
                            
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; color: #2c3e50; font-weight: 500;">Calificación:</label>
                                <input type="number" name="calificacion" min="0" max="100" step="0.01" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                            
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; color: #2c3e50; font-weight: 500;">Fecha:</label>
                                <input type="date" name="fecha_evaluacion" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                            </div>
                            
                            <div>
                                <button type="submit" style="width: 100%; padding: 0.75rem; background: #1976d2; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                    Registrar
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Lista de calificaciones existentes -->
                    @if($curso->calificaciones->count() > 0)
                    <div class="grades-list">
                        <h3 style="color: #2c3e50; margin-bottom: 1rem;">Calificaciones Registradas</h3>
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: #f8f9fa;">
                                        <th style="padding: 0.75rem; text-align: left; border-bottom: 2px solid #dee2e6;">Estudiante</th>
                                        <th style="padding: 0.75rem; text-align: left; border-bottom: 2px solid #dee2e6;">Tipo</th>
                                        <th style="padding: 0.75rem; text-align: center; border-bottom: 2px solid #dee2e6;">Calificación</th>
                                        <th style="padding: 0.75rem; text-align: center; border-bottom: 2px solid #dee2e6;">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($curso->calificaciones->sortByDesc('fecha_evaluacion') as $calificacion)
                                    <tr style="border-bottom: 1px solid #dee2e6;">
                                        <td style="padding: 0.75rem;">{{ $calificacion->estudiante->name }}</td>
                                        <td style="padding: 0.75rem;">
                                            <span style="padding: 0.25rem 0.5rem; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 0.8rem;">
                                                {{ ucfirst($calificacion->tipo_evaluacion) }}
                                            </span>
                                        </td>
                                        <td style="padding: 0.75rem; text-align: center;">
                                            <span style="padding: 0.5rem; background: {{ $calificacion->calificacion >= 70 ? '#d4edda' : '#f8d7da' }}; color: {{ $calificacion->calificacion >= 70 ? '#155724' : '#721c24' }}; border-radius: 8px; font-weight: bold;">
                                                {{ $calificacion->calificacion }}
                                            </span>
                                        </td>
                                        <td style="padding: 0.75rem; text-align: center;">{{ $calificacion->fecha_evaluacion->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                        <p style="color: #6c757d; text-align: center; padding: 1rem;">No hay calificaciones registradas para este curso.</p>
                    @endif
                @else
                    <p style="color: #6c757d; text-align: center; padding: 2rem;">No hay estudiantes inscritos en este curso.</p>
                @endif
            </div>
            @endforeach
        @else
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <h3 style="color: #6c757d; margin-bottom: 1rem;">No tienes cursos asignados</h3>
                <p style="color: #6c757d;">Contacta al administrador para que te asigne cursos.</p>
            </div>
        @endif
    </div>
</div>
@endsection