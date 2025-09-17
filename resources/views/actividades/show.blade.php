@extends('layouts.app')

@section('title', $actividad->titulo)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cursos.index') }}">Cursos</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cursos.show', $actividad->contenido->curso->id) }}">{{ $actividad->contenido->curso->nombre }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('contenidos.show', $actividad->contenido->id) }}">{{ $actividad->contenido->titulo }}</a></li>
                    <li class="breadcrumb-item active">{{ $actividad->titulo }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ $actividad->titulo }}</h4>
                    <p class="text-muted mb-0">{{ $actividad->contenido->titulo }} - {{ $actividad->contenido->curso->nombre }}</p>
                </div>

                <div class="card-body">
                    @if($actividad->descripcion)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            {{ $actividad->descripcion }}
                        </div>
                    @endif

                    @if(auth()->user()->rol === 'estudiante')
                        <!-- Formulario para estudiantes -->
                        <form action="{{ route('actividades.responder', $actividad->id) }}" method="POST" id="actividadForm">
                            @csrf
                            
                            @foreach($actividad->preguntas as $index => $pregunta)
                                <div class="question-card mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                Pregunta {{ $index + 1 }}
                                                @if(isset($respuestasEstudiante[$pregunta->id]))
                                                    @if($respuestasEstudiante[$pregunta->id]->es_correcta)
                                                        <span class="badge badge-success float-right">
                                                            <i class="fas fa-check"></i> Correcta
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger float-right">
                                                            <i class="fas fa-times"></i> Incorrecta
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-secondary float-right">
                                                        <i class="fas fa-clock"></i> Sin responder
                                                    </span>
                                                @endif
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="question-text mb-3">{{ $pregunta->pregunta }}</p>
                                            
                                            <div class="options">
                                                @foreach($pregunta->opciones as $opcion)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" 
                                                               type="radio" 
                                                               name="respuestas[{{ $pregunta->id }}]" 
                                                               id="opcion_{{ $opcion->id }}" 
                                                               value="{{ $opcion->id }}"
                                                               @if(isset($respuestasEstudiante[$pregunta->id]) && $respuestasEstudiante[$pregunta->id]->opcion_id == $opcion->id) checked @endif>
                                                        <label class="form-check-label" for="opcion_{{ $opcion->id }}">
                                                            {{ $opcion->opcion }}
                                                            @if(isset($respuestasEstudiante[$pregunta->id]))
                                                                @if($opcion->es_correcta)
                                                                    <i class="fas fa-check text-success ml-2"></i>
                                                                @elseif($respuestasEstudiante[$pregunta->id]->opcion_id == $opcion->id && !$opcion->es_correcta)
                                                                    <i class="fas fa-times text-danger ml-2"></i>
                                                                @endif
                                                            @endif
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>

                                            @if(isset($respuestasEstudiante[$pregunta->id]))
                                                <div class="mt-3">
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock"></i>
                                                        Respondida el {{ $respuestasEstudiante[$pregunta->id]->fecha_respuesta->format('d/m/Y H:i') }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if($actividad->preguntas->count() > 0)
                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane"></i>
                                        @if($respuestasEstudiante->count() > 0)
                                            Actualizar Respuestas
                                        @else
                                            Enviar Respuestas
                                        @endif
                                    </button>
                                </div>
                            @endif
                        </form>

                        @if($respuestasEstudiante->count() > 0)
                            <!-- Mostrar estadísticas -->
                            <div class="mt-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-chart-bar"></i> Tus Resultados
                                        </h6>
                                        @php
                                            $correctas = $respuestasEstudiante->where('es_correcta', true)->count();
                                            $total = $actividad->preguntas->count();
                                            $porcentaje = $total > 0 ? ($correctas / $total) * 100 : 0;
                                        @endphp
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="text-success">{{ $correctas }}</h4>
                                                    <small class="text-muted">Correctas</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="text-danger">{{ $total - $correctas }}</h4>
                                                    <small class="text-muted">Incorrectas</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="text-primary">{{ $total }}</h4>
                                                    <small class="text-muted">Total</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="text-center">
                                                    <h4 class="@if($porcentaje >= 70) text-success @elseif($porcentaje >= 50) text-warning @else text-danger @endif">
                                                        {{ number_format($porcentaje, 1) }}%
                                                    </h4>
                                                    <small class="text-muted">Puntuación</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Vista para maestros y administradores -->
                        <div class="questions-preview">
                            @foreach($actividad->preguntas as $index => $pregunta)
                                <div class="question-card mb-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Pregunta {{ $index + 1 }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="question-text mb-3">{{ $pregunta->pregunta }}</p>
                                            
                                            <div class="options">
                                                @foreach($pregunta->opciones as $opcion)
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="radio" disabled>
                                                        <label class="form-check-label">
                                                            {{ $opcion->opcion }}
                                                            @if($opcion->es_correcta)
                                                                <i class="fas fa-check text-success ml-2"></i>
                                                                <small class="text-success">(Respuesta correcta)</small>
                                                            @endif
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($actividad->preguntas->count() === 0)
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle"></i>
                            Esta actividad aún no tiene preguntas configuradas.
                        </div>
                    @endif

                    <!-- Botones de navegación -->
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('contenidos.show', $actividad->contenido->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al Contenido
                        </a>
                        
                        @if(auth()->user()->rol === 'admin' || (auth()->user()->rol === 'maestro' && $actividad->contenido->curso->maestro_id === auth()->id()))
                            <div>
                                <a href="{{ route('actividades.edit', $actividad->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Editar Actividad
                                </a>
                                <form action="{{ route('actividades.destroy', $actividad->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar esta actividad?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Confirmar antes de enviar respuestas
document.getElementById('actividadForm')?.addEventListener('submit', function(e) {
    const respuestasCompletas = document.querySelectorAll('input[type="radio"]:checked').length;
    const totalPreguntas = {{ $actividad->preguntas->count() }};
    
    if (respuestasCompletas < totalPreguntas) {
        if (!confirm(`Has respondido ${respuestasCompletas} de ${totalPreguntas} preguntas. ¿Deseas continuar?`)) {
            e.preventDefault();
        }
    }
});
</script>
@endsection