{{-- resources/views/dashboard/alumno/calificaciones.blade.php --}}
@extends('layouts.app')

@section('title', 'Mis Calificaciones - Alumno')

@section('content')
<div class="dashboard-container">
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA">
            <h3>UHTA</h3>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard.alumno') }}" class="nav-item">
                <i class="icon-info"></i>
                Mi información
            </a>
            <a href="{{ route('alumno.cursos') }}" class="nav-item">
                <i class="icon-courses"></i>
                Mis Cursos
            </a>
            <a href="{{ route('alumno.calificaciones') }}" class="nav-item active">
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h1 style="color: #2c3e50;">Mis Calificaciones</h1>
                @if($promedioGeneral)
                <div style="text-align: center; padding: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; color: white;">
                    <div style="font-size: 1.5rem; font-weight: bold;">{{ number_format($promedioGeneral, 1) }}</div>
                    <div style="font-size: 0.9rem; opacity: 0.9;">Promedio General</div>
                </div>
                @endif
            </div>
        </div>
        
        @if($calificaciones->count() > 0)
            <div class="grades-container" style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #dee2e6; color: #2c3e50;">Curso</th>
                                <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #dee2e6; color: #2c3e50;">Tipo de Evaluación</th>
                                <th style="padding: 1rem; text-align: center; border-bottom: 2px solid #dee2e6; color: #2c3e50;">Calificación</th>
                                <th style="padding: 1rem; text-align: center; border-bottom: 2px solid #dee2e6; color: #2c3e50;">Letra</th>
                                <th style="padding: 1rem; text-align: center; border-bottom: 2px solid #dee2e6; color: #2c3e50;">Fecha</th>
                                <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #dee2e6; color: #2c3e50;">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calificaciones as $calificacion)
                            <tr style="border-bottom: 1px solid #dee2e6; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='transparent'">
                                <td style="padding: 1rem;">
                                    <div>
                                        <div style="font-weight: 500; color: #2c3e50;">{{ $calificacion->curso->nombre }}</div>
                                        <div style="font-size: 0.8rem; color: #6c757d;">{{ $calificacion->curso->codigo }}</div>
                                    </div>
                                </td>
                                <td style="padding: 1rem;">
                                    <span style="padding: 0.25rem 0.75rem; background: #e3f2fd; color: #1976d2; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">
                                        {{ ucfirst($calificacion->tipo_evaluacion) }}
                                    </span>
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <span style="padding: 0.5rem 1rem; background: {{ $calificacion->calificacion >= 70 ? '#d4edda' : '#f8d7da' }}; color: {{ $calificacion->calificacion >= 70 ? '#155724' : '#721c24' }}; border-radius: 8px; font-weight: bold; font-size: 1.1rem;">
                                        {{ $calificacion->calificacion }}
                                    </span>
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <span style="padding: 0.5rem; background: {{ $calificacion->calificacion >= 70 ? '#d4edda' : '#f8d7da' }}; color: {{ $calificacion->calificacion >= 70 ? '#155724' : '#721c24' }}; border-radius: 50%; font-weight: bold; width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">
                                        {{ $calificacion->calificacion_letra }}
                                    </span>
                                </td>
                                <td style="padding: 1rem; text-align: center; color: #6c757d;">
                                    {{ $calificacion->fecha_evaluacion->format('d/m/Y') }}
                                </td>
                                <td style="padding: 1rem; color: #6c757d; font-size: 0.9rem;">
                                    {{ $calificacion->observaciones ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Resumen por curso -->
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #e9ecef;">
                    <h3 style="color: #2c3e50; margin-bottom: 1rem;">Resumen por Curso</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem;">
                        @foreach($calificaciones->groupBy('curso_id') as $cursoId => $calificacionesCurso)
                        @php
                            $curso = $calificacionesCurso->first()->curso;
                            $promedioCurso = $calificacionesCurso->avg('calificacion');
                        @endphp
                        <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                            <h4 style="color: #2c3e50; margin-bottom: 0.5rem;">{{ $curso->nombre }}</h4>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #6c757d;">{{ $calificacionesCurso->count() }} evaluaciones</span>
                                <span style="padding: 0.25rem 0.75rem; background: {{ $promedioCurso >= 70 ? '#d4edda' : '#f8d7da' }}; color: {{ $promedioCurso >= 70 ? '#155724' : '#721c24' }}; border-radius: 20px; font-weight: bold;">
                                    Promedio: {{ number_format($promedioCurso, 1) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <h3 style="color: #6c757d; margin-bottom: 1rem;">No tienes calificaciones registradas</h3>
                <p style="color: #6c757d;">Las calificaciones aparecerán aquí cuando tus profesores las registren.</p>
            </div>
        @endif
    </div>
</div>
@endsection