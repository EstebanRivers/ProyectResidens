@extends('layouts.dashboard')

@section('title', 'Gestión de Cursos')
@section('page-title', 'Cursos')
@section('page-subtitle', 'Gestión de cursos y materias')

@section('content')
<div class="course-management">
    <div class="course-header">
        <div class="course-actions">
            @if(Auth::user()->isAdmin() || Auth::user()->isMaestro())
                <a href="{{ route('cursos.create') }}" class="btn-primary">
                    ➕ Crear Nuevo Curso
                </a>
            @endif
        </div>
    </div>

    <div class="courses-grid">
        @forelse($cursos as $curso)
            @include('dashboard.partials.course-card', ['course' => $curso])
        @empty
            <div class="empty-state">
                <h3>No hay cursos disponibles</h3>
                <p>
                    @if(Auth::user()->isAdmin() || Auth::user()->isMaestro())
                        Comienza creando tu primer curso.
                    @else
                        No hay cursos disponibles para inscripción en este momento.
                    @endif
                </p>
            </div>
        @endforelse
        
        @if(Auth::user()->isAdmin() || Auth::user()->isMaestro())
            @include('dashboard.partials.create-course-card')
        @endif
    </div>
</div>
@endsection