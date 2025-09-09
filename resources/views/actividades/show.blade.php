@extends('layouts.app')

@section('title', $actividad->titulo)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar de navegación -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-tasks"></i>
                        Actividades del Curso
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($actividades as $item)
                            <a href="{{ route('actividades.show', $item) }}" 
                               class="list-group-item list-group-item-action {{ $item->id == $actividad->id ? 'active' : '' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($item->yaRespondidaPor(Auth::id()))
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="fas fa-circle text-muted"></i>
                                        @endif
                                        <span class="ms-2">{{ $item->titulo }}</span>
                                    </div>
                                    <small class="badge bg-primary">{{ $item->puntos }} pts</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ $actividad->titulo }}</h4>
                            <small class="text-muted">
                                <i class="fas fa-star"></i>
                                {{ $actividad->puntos }} puntos
                            </small>
                        </div>
                        <div>
                            @if($yaRespondida)
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> Completada
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock"></i> Pendiente
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if($actividad->descripcion)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            {{ $actividad->descripcion }}
                        </div>
                    @endif

                    <!-- Mostrar resultado si ya respondió -->
                    @if($yaRespondida && $respuestaUsuario)
                        <div class="alert {{ $respuestaUsuario->es_correcta ? 'alert-success' : 'alert-danger' }}">
                            <h5>
                                @if($respuestaUsuario->es_correcta)
                                    <i class="fas fa-check-circle"></i> ¡Respuesta Correcta!
                                @else
                                    <i class="fas fa-times-circle"></i> Respuesta Incorrecta
                                @endif
                            </h5>
                            <p class="mb-2">
                                <strong>Tu respuesta:</strong> 
                                {{ is_array($respuestaUsuario->respuesta) ? implode(', ', $respuestaUsuario->respuesta) : $respuestaUsuario->respuesta }}
                            </p>
                            <p class="mb-2">
                                <strong>Respuesta correcta:</strong> 
                                {{ is_array($actividad->respuesta_correcta) ? implode(', ', $actividad->respuesta_correcta) : $actividad->respuesta_correcta }}
                            </p>
                            <p class="mb-0">
                                <strong>Puntuación:</strong> {{ $respuestaUsuario->puntuacion }}/{{ $actividad->puntos }} puntos
                            </p>
                        </div>
                    @endif

                    <!-- Pregunta -->
                    <div class="question-container">
                        <h5 class="mb-4">{{ $actividad->pregunta }}</h5>

                        @if(!$yaRespondida)
                            <form action="{{ route('actividades.responder', $actividad->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirmarEnvio()">
                                @csrf

                                @if($actividad->tipo == 'multiple')
                                    <!-- Opción múltiple -->
                                    @if($actividad->opciones)
                                        @foreach($actividad->opciones as $index => $opcion)
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" 
                                                       type="radio" 
                                                       name="respuesta" 
                                                       value="{{ $opcion }}" 
                                                       id="opcion{{ $index }}"
                                                       required>
                                                <label class="form-check-label" for="opcion{{ $index }}">
                                                    <strong>{{ chr(65 + $index) }}.</strong> {{ $opcion }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif

                                @elseif($actividad->tipo == 'verdadero_falso')
                                    <!-- Verdadero/Falso -->
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="respuesta" 
                                               value="Verdadero" 
                                               id="verdadero"
                                               required>
                                        <label class="form-check-label" for="verdadero">
                                            <i class="fas fa-check text-success"></i> Verdadero
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="respuesta" 
                                               value="Falso" 
                                               id="falso"
                                               required>
                                        <label class="form-check-label" for="falso">
                                            <i class="fas fa-times text-danger"></i> Falso
                                        </label>
                                    </div>

                                @elseif($actividad->tipo == 'respuesta_corta')
                                    <!-- Respuesta corta -->
                                    <div class="mb-3">
                                        <input type="text" 
                                               class="form-control" 
                                               name="respuesta" 
                                               placeholder="Escribe tu respuesta aquí..."
                                               required>
                                    </div>

                                @endif

                                <div class="d-flex justify-content-between mt-4">
                                    <a href="{{ route('cursos.show', $curso->id) }}" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left"></i> Volver al curso
                                    </a>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Enviar Respuesta
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="text-center mt-4">
                                <a href="{{ route('cursos.show', $curso->id) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-left"></i> Volver al curso
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEnvio() {
    return confirm('¿Estás seguro de que quieres enviar tu respuesta? No podrás cambiarla después.');
}
</script>
@endsection