@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('cursos.index') }}" class="text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>Cursos
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('cursos.show', $curso) }}" class="text-gray-700 hover:text-blue-600">
                        {{ $curso->nombre }}
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-gray-500">{{ $contenido->titulo }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Progreso del Curso -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                Progreso del Curso
            </h3>
            <span class="text-sm text-gray-600">
                {{ $contenidosCompletados }} de {{ $totalContenidos }} contenidos
            </span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full transition-all duration-300" 
                 style="width: {{ $porcentajeProgreso }}%"></div>
        </div>
        <p class="text-sm text-gray-600 mt-2">{{ $porcentajeProgreso }}% completado</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Navegación de Contenidos -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-4 sticky top-4">
                <h3 class="font-semibold text-gray-800 mb-4">
                    <i class="fas fa-list text-blue-500 mr-2"></i>
                    Contenidos del Curso
                </h3>
                <div class="space-y-2">
                    @foreach($contenidos as $item)
                        @php
                            $completado = $item->completadoPorEstudiante(auth()->id());
                        @endphp
                        <a href="{{ route('contenidos.show', $item) }}" 
                           class="flex items-center p-3 rounded-lg transition-colors {{ $item->id === $contenido->id ? 'bg-blue-100 border-l-4 border-blue-500' : 'hover:bg-gray-50' }}">
                            <div class="flex-shrink-0 mr-3">
                                @if($completado)
                                    <i class="fas fa-check-circle text-green-500"></i>
                                @elseif($item->id === $contenido->id)
                                    <i class="fas fa-play-circle text-blue-500"></i>
                                @else
                                    <i class="fas fa-circle text-gray-300"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $item->titulo }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ ucfirst($item->tipo) }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header del Contenido -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold mb-2">{{ $contenido->titulo }}</h1>
                            <p class="text-blue-100">
                                <i class="fas fa-{{ $contenido->tipo === 'video' ? 'play' : ($contenido->tipo === 'texto' ? 'file-text' : 'file') }} mr-2"></i>
                                {{ ucfirst($contenido->tipo) }}
                            </p>
                        </div>
                        <div class="text-right">
                            @if($progreso->completado)
                                <div class="bg-green-500 text-white px-4 py-2 rounded-full text-sm font-medium">
                                    <i class="fas fa-check mr-2"></i>Completado
                                </div>
                            @else
                                <button id="marcarCompletado" 
                                        class="bg-white text-blue-600 px-4 py-2 rounded-full text-sm font-medium hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-check mr-2"></i>Marcar como Completado
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contenido -->
                <div class="p-6">
                    @if($contenido->descripcion)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Descripción</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $contenido->descripcion }}</p>
                        </div>
                    @endif

                    @if($contenido->tipo === 'video' && $contenido->url_video)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Video</h3>
                            <div class="relative aspect-video bg-black rounded-lg overflow-hidden">
                                <video id="videoPlayer" controls class="w-full h-full">
                                    <source src="{{ $contenido->url_video }}" type="video/mp4">
                                    Tu navegador no soporta el elemento video.
                                </video>
                            </div>
                        </div>
                    @endif

                    @if($contenido->contenido)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Contenido</h3>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($contenido->contenido)) !!}
                            </div>
                        </div>
                    @endif

                    @if($contenido->archivo)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Archivo Adjunto</h3>
                            <a href="{{ Storage::url($contenido->archivo) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                <i class="fas fa-download mr-2"></i>
                                Descargar Archivo
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Navegación -->
                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                    @php
                        $contenidosOrdenados = $contenidos->sortBy('orden');
                        $indiceActual = $contenidosOrdenados->search(function($item) use ($contenido) {
                            return $item->id === $contenido->id;
                        });
                        $anterior = $indiceActual > 0 ? $contenidosOrdenados->values()[$indiceActual - 1] : null;
                        $siguiente = $indiceActual < $contenidosOrdenados->count() - 1 ? $contenidosOrdenados->values()[$indiceActual + 1] : null;
                    @endphp

                    <div>
                        @if($anterior)
                            <a href="{{ route('contenidos.show', $anterior) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                                <i class="fas fa-chevron-left mr-2"></i>
                                Anterior
                            </a>
                        @endif
                    </div>

                    <a href="{{ route('cursos.show', $curso) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al Curso
                    </a>

                    <div>
                        @if($siguiente)
                            <a href="{{ route('contenidos.show', $siguiente) }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                Siguiente
                                <i class="fas fa-chevron-right ml-2"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const marcarBtn = document.getElementById('marcarCompletado');
    const videoPlayer = document.getElementById('videoPlayer');
    let tiempoInicio = Date.now();
    let tiempoDedicado = 0;

    // Marcar como completado
    if (marcarBtn) {
        marcarBtn.addEventListener('click', function() {
            marcarComoCompletado();
        });
    }

    // Auto-completar después de 5 minutos o al terminar video
    if (videoPlayer) {
        videoPlayer.addEventListener('ended', function() {
            if (marcarBtn) {
                marcarComoCompletado();
            }
        });

        // Seguimiento de tiempo de video
        videoPlayer.addEventListener('timeupdate', function() {
            tiempoDedicado = Math.floor(videoPlayer.currentTime);
        });
    }

    // Auto-completar después de 5 minutos
    setTimeout(function() {
        if (marcarBtn && !{{ $progreso->completado ? 'true' : 'false' }}) {
            marcarComoCompletado();
        }
    }, 300000); // 5 minutos

    function marcarComoCompletado() {
        const tiempoTotal = Math.floor((Date.now() - tiempoInicio) / 1000) + tiempoDedicado;
        
        fetch(`{{ route('contenidos.marcar-completado', $contenido) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                tiempo_dedicado: tiempoTotal
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (marcarBtn) {
                    marcarBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Completado';
                    marcarBtn.className = 'bg-green-500 text-white px-4 py-2 rounded-full text-sm font-medium cursor-default';
                    marcarBtn.disabled = true;
                }
                
                // Actualizar progreso visual
                const progressBar = document.querySelector('.bg-gradient-to-r');
                if (progressBar) {
                    progressBar.style.width = data.progreso + '%';
                }
                
                // Mostrar notificación
                showNotification('¡Contenido completado!', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al marcar como completado', 'error');
        });
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : 'times'} mr-2"></i>${message}`;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});
</script>
@endsection