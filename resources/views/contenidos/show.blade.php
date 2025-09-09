@extends('layouts.app')

@section('title', $contenido->titulo)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar de navegación -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-book"></i>
                        {{ $curso->nombre }}
                    </h6>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar bg-success" 
                             style="width: {{ $porcentajeProgreso }}%"></div>
                    </div>
                    <small class="text-muted">{{ $porcentajeProgreso }}% completado</small>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($contenidos as $item)
                            <a href="{{ route('contenidos.show', $item->id) }}" 
                               class="list-group-item list-group-item-action {{ $item->id == $contenido->id ? 'active' : '' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($item->completadoPor(Auth::id()))
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="fas fa-circle text-muted"></i>
                                        @endif
                                        <span class="ms-2">{{ $item->titulo }}</span>
                                    </div>
                                    <small class="text-muted">
                                        @if($item->tipo == 'video')
                                            <i class="fas fa-play"></i>
                                        @elseif($item->tipo == 'texto')
                                            <i class="fas fa-file-text"></i>
                                        @else
                                            <i class="fas fa-file"></i>
                                        @endif
                                    </small>
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
                            <h4 class="mb-0">{{ $contenido->titulo }}</h4>
                            <small class="text-muted">
                                <i class="fas fa-clock"></i>
                                @if($contenido->duracion)
                                    {{ $contenido->duracion }} minutos
                                @else
                                    Duración estimada
                                @endif
                            </small>
                        </div>
                        <div>
                            @if($progreso && $progreso->completado)
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> Completado
                                </span>
                            @else
                                <button type="button" 
                                        class="btn btn-outline-success btn-sm"
                                        onclick="marcarCompletado({{ $contenido->id }})">
                                    <i class="fas fa-check"></i> Marcar como completado
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if($contenido->descripcion)
                        <p class="text-muted mb-4">{{ $contenido->descripcion }}</p>
                    @endif

                    <!-- Contenido según tipo -->
                    @if($contenido->tipo == 'video')
                        <div class="ratio ratio-16x9 mb-4">
                            <video controls class="rounded">
                                <source src="{{ $contenido->archivo_url }}" type="video/mp4">
                                Tu navegador no soporta el elemento video.
                            </video>
                        </div>
                    @elseif($contenido->tipo == 'texto')
                        <div class="content-text">
                            {!! nl2br(e($contenido->contenido)) !!}
                        </div>
                    @elseif($contenido->archivo_url)
                        <div class="text-center mb-4">
                            <a href="{{ $contenido->archivo_url }}" 
                               class="btn btn-primary" 
                               target="_blank">
                                <i class="fas fa-download"></i>
                                Descargar archivo
                            </a>
                        </div>
                    @endif

                    @if($contenido->contenido && $contenido->tipo != 'texto')
                        <div class="mt-4">
                            <h5>Descripción:</h5>
                            <p>{!! nl2br(e($contenido->contenido)) !!}</p>
                        </div>
                    @endif
                </div>

                <!-- Navegación entre contenidos -->
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        @php
                            $currentIndex = $contenidos->search(function($item) use ($contenido) {
                                return $item->id == $contenido->id;
                            });
                            $prevContenido = $currentIndex > 0 ? $contenidos[$currentIndex - 1] : null;
                            $nextContenido = $currentIndex < $contenidos->count() - 1 ? $contenidos[$currentIndex + 1] : null;
                        @endphp

                        <div>
                            @if($prevContenido)
                                <a href="{{ route('contenidos.show', $prevContenido->id) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-chevron-left"></i> Anterior
                                </a>
                            @endif
                        </div>

                        <div>
                            <a href="{{ route('cursos.show', $curso->id) }}" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> Volver al curso
                            </a>
                        </div>

                        <div>
                            @if($nextContenido)
                                <a href="{{ route('contenidos.show', $nextContenido->id) }}" 
                                   class="btn btn-outline-secondary">
                                    Siguiente <i class="fas fa-chevron-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function marcarCompletado(contenidoId) {
    fetch(`/contenidos/${contenidoId}/completar`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error al marcar como completado');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al marcar como completado');
    });
}
</script>
@endsection