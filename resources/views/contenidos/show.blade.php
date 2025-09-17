@extends('layouts.app')

@section('title', $contenido->titulo)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cursos.index') }}">Cursos</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cursos.show', $contenido->curso->id) }}">{{ $contenido->curso->nombre }}</a></li>
                    <li class="breadcrumb-item active">{{ $contenido->titulo }}</li>
                </ol>
            </nav>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $contenido->titulo }}</h4>
                    
                    @if(auth()->user()->rol === 'estudiante' && isset($progreso))
                        <div class="d-flex align-items-center">
                            @if($progreso && $progreso->completado)
                                <span class="badge badge-success me-2">
                                    <i class="fas fa-check"></i> Completado
                                </span>
                            @else
                                <button type="button" class="btn btn-success btn-sm" id="marcarCompletado">
                                    <i class="fas fa-check"></i> Marcar como Completado
                                </button>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <!-- Información del contenido -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <p class="text-muted mb-2">
                                <i class="fas fa-book"></i> Curso: {{ $contenido->curso->nombre }}
                            </p>
                            @if($contenido->descripcion)
                                <p class="mb-3">{{ $contenido->descripcion }}</p>
                            @endif
                        </div>
                        <div class="col-md-4">
                            @if(auth()->user()->rol === 'estudiante' && isset($progreso) && $progreso)
                                <div class="card bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2">Tu Progreso</h6>
                                        <p class="mb-1">
                                            <small class="text-muted">Estado:</small>
                                            @if($progreso->completado)
                                                <span class="badge badge-success">Completado</span>
                                            @else
                                                <span class="badge badge-warning">En progreso</span>
                                            @endif
                                        </p>
                                        @if($progreso->fecha_completado)
                                            <p class="mb-1">
                                                <small class="text-muted">Completado:</small>
                                                {{ $progreso->fecha_completado->format('d/m/Y H:i') }}
                                            </p>
                                        @endif
                                        @if($progreso->tiempo_dedicado > 0)
                                            <p class="mb-0">
                                                <small class="text-muted">Tiempo:</small>
                                                {{ gmdate('H:i:s', $progreso->tiempo_dedicado) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contenido principal -->
                    <div class="content-area mb-4">
                        @if($contenido->tipo === 'video' && $contenido->url)
                            <div class="video-container mb-3">
                                <div class="embed-responsive embed-responsive-16by9">
                                    @if(str_contains($contenido->url, 'youtube.com') || str_contains($contenido->url, 'youtu.be'))
                                        @php
                                            $videoId = '';
                                            if (str_contains($contenido->url, 'youtube.com/watch?v=')) {
                                                $videoId = substr($contenido->url, strpos($contenido->url, 'v=') + 2);
                                            } elseif (str_contains($contenido->url, 'youtu.be/')) {
                                                $videoId = substr($contenido->url, strrpos($contenido->url, '/') + 1);
                                            }
                                            if (str_contains($videoId, '&')) {
                                                $videoId = substr($videoId, 0, strpos($videoId, '&'));
                                            }
                                        @endphp
                                        <iframe class="embed-responsive-item" 
                                                src="https://www.youtube.com/embed/{{ $videoId }}" 
                                                allowfullscreen id="videoPlayer"></iframe>
                                    @else
                                        <video controls class="w-100" id="videoPlayer">
                                            <source src="{{ $contenido->url }}" type="video/mp4">
                                            Tu navegador no soporta el elemento de video.
                                        </video>
                                    @endif
                                </div>
                            </div>
                        @elseif($contenido->tipo === 'documento' && $contenido->url)
                            <div class="document-container mb-3">
                                <a href="{{ $contenido->url }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="fas fa-file-download"></i> Descargar Documento
                                </a>
                            </div>
                        @endif

                        @if($contenido->contenido)
                            <div class="content-text">
                                {!! nl2br(e($contenido->contenido)) !!}
                            </div>
                        @endif
                    </div>

                    <!-- Actividades relacionadas -->
                    @if($contenido->actividades->count() > 0)
                        <div class="activities-section">
                            <h5 class="mb-3">
                                <i class="fas fa-tasks"></i> Actividades
                            </h5>
                            <div class="row">
                                @foreach($contenido->actividades as $actividad)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-left-primary">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $actividad->titulo }}</h6>
                                                @if($actividad->descripcion)
                                                    <p class="card-text text-muted small">{{ Str::limit($actividad->descripcion, 100) }}</p>
                                                @endif
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        <i class="fas fa-question-circle"></i> 
                                                        {{ $actividad->preguntas->count() }} preguntas
                                                    </small>
                                                    <a href="{{ route('actividades.show', $actividad->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-play"></i> Realizar
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Botones de acción -->
                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ route('cursos.show', $contenido->curso->id) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al Curso
                        </a>
                        
                        @if(auth()->user()->rol === 'admin' || (auth()->user()->rol === 'maestro' && $contenido->curso->maestro_id === auth()->id()))
                            <div>
                                <a href="{{ route('contenidos.edit', $contenido->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('contenidos.destroy', $contenido->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este contenido?')">
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

@if(auth()->user()->rol === 'estudiante')
<script>
let tiempoInicio = Date.now();
let tiempoTotal = 0;

// Marcar como completado manualmente
document.getElementById('marcarCompletado')?.addEventListener('click', function() {
    marcarComoCompletado();
});

// Marcar como completado automáticamente después de 5 minutos
setTimeout(function() {
    @if(!isset($progreso) || !$progreso || !$progreso->completado)
        marcarComoCompletado();
    @endif
}, 300000); // 5 minutos

// Seguimiento de tiempo para videos
const videoPlayer = document.getElementById('videoPlayer');
if (videoPlayer) {
    videoPlayer.addEventListener('play', function() {
        tiempoInicio = Date.now();
    });
    
    videoPlayer.addEventListener('pause', function() {
        tiempoTotal += (Date.now() - tiempoInicio) / 1000;
    });
    
    videoPlayer.addEventListener('ended', function() {
        tiempoTotal += (Date.now() - tiempoInicio) / 1000;
        marcarComoCompletado();
    });
}

function marcarComoCompletado() {
    const tiempoFinal = tiempoTotal + (Date.now() - tiempoInicio) / 1000;
    
    fetch('{{ route("contenidos.completar", $contenido->id) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            tiempo_dedicado: Math.round(tiempoFinal)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

// Actualizar tiempo al salir de la página
window.addEventListener('beforeunload', function() {
    tiempoTotal += (Date.now() - tiempoInicio) / 1000;
});
</script>
@endif
@endsection