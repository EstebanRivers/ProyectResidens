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
                    <span class="text-gray-500">{{ $actividad->titulo }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="max-w-4xl mx-auto">
        <!-- Header de la Actividad -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-purple-500 to-pink-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">{{ $actividad->titulo }}</h1>
                        <p class="text-purple-100">
                            <i class="fas fa-tasks mr-2"></i>
                            {{ ucfirst($actividad->tipo) }}
                        </p>
                    </div>
                    <div class="text-right">
                        @if($respuestaExistente)
                            <div class="bg-green-500 text-white px-4 py-2 rounded-full text-sm font-medium">
                                <i class="fas fa-check mr-2"></i>
                                Completada ({{ $respuestaExistente->puntuacion }} pts)
                            </div>
                        @else
                            <div class="bg-yellow-500 text-white px-4 py-2 rounded-full text-sm font-medium">
                                <i class="fas fa-clock mr-2"></i>Pendiente
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información de la Actividad -->
            <div class="p-6">
                @if($actividad->descripcion)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Descripción</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $actividad->descripcion }}</p>
                    </div>
                @endif

                <!-- Estadísticas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-star text-blue-500 text-xl mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Puntos Máximos</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $actividad->puntos_maximos }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-users text-green-500 text-xl mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Respuestas</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $totalRespuestas }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-chart-line text-yellow-500 text-xl mr-3"></i>
                            <div>
                                <p class="text-sm text-gray-600">Promedio</p>
                                <p class="text-lg font-semibold text-gray-800">{{ number_format($promedioCalificacion, 1) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($respuestaExistente)
            <!-- Mostrar Resultados -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-chart-bar text-green-500 mr-2"></i>
                    Tus Resultados
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-green-800 mb-2">Puntuación Obtenida</h4>
                        <p class="text-2xl font-bold text-green-600">
                            {{ $respuestaExistente->puntuacion }} / {{ $actividad->puntos_maximos }}
                        </p>
                        <p class="text-sm text-green-600 mt-1">
                            {{ round(($respuestaExistente->puntuacion / $actividad->puntos_maximos) * 100) }}% de acierto
                        </p>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-blue-800 mb-2">Fecha de Completado</h4>
                        <p class="text-lg font-semibold text-blue-600">
                            {{ $respuestaExistente->fecha_completado->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>

                <!-- Mostrar respuestas -->
                <div class="mt-6">
                    <h4 class="font-semibold text-gray-800 mb-4">Tus Respuestas</h4>
                    @foreach($preguntas as $index => $pregunta)
                        @php
                            $respuestaEstudiante = $respuestaExistente->respuestas[$pregunta->id] ?? 'Sin respuesta';
                            $esCorrecta = $pregunta->esRespuestaCorrecta($respuestaEstudiante);
                        @endphp
                        <div class="mb-4 p-4 border rounded-lg {{ $esCorrecta ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                            <div class="flex items-start justify-between mb-2">
                                <h5 class="font-medium text-gray-800">{{ $index + 1 }}. {{ $pregunta->pregunta }}</h5>
                                <span class="text-sm font-medium {{ $esCorrecta ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="fas fa-{{ $esCorrecta ? 'check' : 'times' }} mr-1"></i>
                                    {{ $esCorrecta ? 'Correcta' : 'Incorrecta' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1">
                                <strong>Tu respuesta:</strong> {{ $respuestaEstudiante }}
                            </p>
                            @if(!$esCorrecta)
                                <p class="text-sm text-green-600">
                                    <strong>Respuesta correcta:</strong> {{ $pregunta->respuesta_correcta }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Formulario de Respuestas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">
                    <i class="fas fa-pencil-alt text-purple-500 mr-2"></i>
                    Responder Actividad
                </h3>

                <form action="{{ route('actividades.responder', $actividad) }}" method="POST" id="actividadForm">
                    @csrf
                    
                    @foreach($preguntas as $index => $pregunta)
                        <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                            <h4 class="font-medium text-gray-800 mb-3">
                                {{ $index + 1 }}. {{ $pregunta->pregunta }}
                                <span class="text-sm text-gray-500">({{ $pregunta->puntos }} pts)</span>
                            </h4>

                            @if($pregunta->tipo === 'multiple_choice')
                                @foreach($pregunta->opciones as $opcion)
                                    <label class="flex items-center mb-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                        <input type="radio" 
                                               name="respuestas[{{ $pregunta->id }}]" 
                                               value="{{ $opcion }}" 
                                               class="mr-3 text-purple-600 focus:ring-purple-500"
                                               required>
                                        <span class="text-gray-700">{{ $opcion }}</span>
                                    </label>
                                @endforeach
                            @elseif($pregunta->tipo === 'true_false')
                                <label class="flex items-center mb-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="radio" 
                                           name="respuestas[{{ $pregunta->id }}]" 
                                           value="Verdadero" 
                                           class="mr-3 text-purple-600 focus:ring-purple-500"
                                           required>
                                    <span class="text-gray-700">Verdadero</span>
                                </label>
                                <label class="flex items-center mb-2 cursor-pointer hover:bg-gray-50 p-2 rounded">
                                    <input type="radio" 
                                           name="respuestas[{{ $pregunta->id }}]" 
                                           value="Falso" 
                                           class="mr-3 text-purple-600 focus:ring-purple-500"
                                           required>
                                    <span class="text-gray-700">Falso</span>
                                </label>
                            @else
                                <input type="text" 
                                       name="respuestas[{{ $pregunta->id }}]" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                       placeholder="Escribe tu respuesta..."
                                       required>
                            @endif
                        </div>
                    @endforeach

                    <div class="flex justify-between items-center pt-6 border-t">
                        <a href="{{ route('cursos.show', $curso) }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver al Curso
                        </a>

                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Enviar Respuestas
                        </button>
                    </div>
                </form>
            </div>
        @endif

        @if($respuestaExistente)
            <!-- Botón para volver -->
            <div class="text-center mt-6">
                <a href="{{ route('cursos.show', $curso) }}" 
                   class="inline-flex items-center px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al Curso
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('actividadForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const respuestas = form.querySelectorAll('input[type="radio"]:checked, input[type="text"]');
            const preguntas = form.querySelectorAll('[name^="respuestas"]');
            
            if (respuestas.length < {{ $preguntas->count() }}) {
                e.preventDefault();
                alert('Por favor responde todas las preguntas antes de enviar.');
                return false;
            }
            
            // Confirmación antes de enviar
            if (!confirm('¿Estás seguro de que quieres enviar tus respuestas? No podrás cambiarlas después.')) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>
@endsection