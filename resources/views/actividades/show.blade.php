@extends('layouts.dashboard')

@section('title', $actividad->titulo)
@section('page-title', $actividad->titulo)
@section('page-subtitle', 'Curso: ' . $curso->nombre)

@section('content')
<div class="activity-detail">
    <!-- Activity Header -->
    <div class="activity-header" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 2rem;">
        <div class="activity-info">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <span style="font-size: 2rem;">{{ $actividad->icono_tipo }}</span>
                <div>
                    <h1 style="color: #2c3e50; margin: 0;">{{ $actividad->titulo }}</h1>
                    <p style="color: #6c757d; margin: 0;">{{ $actividad->descripcion }}</p>
                </div>
            </div>
            
            <div class="activity-meta" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <span style="padding: 0.5rem 1rem; background: #e3f2fd; color: #1976d2; border-radius: 20px; font-size: 0.9rem;">⭐ {{ $actividad->puntos }} puntos</span>
                <span style="padding: 0.5rem 1rem; background: #f3e5f5; color: #7b1fa2; border-radius: 20px; font-size: 0.9rem;">📝 {{ ucfirst(str_replace('_', ' ', $actividad->tipo)) }}</span>
                @if($actividad->contenido)
                    <span style="padding: 0.5rem 1rem; background: #fff3e0; color: #f57c00; border-radius: 20px; font-size: 0.9rem;">📚 {{ $actividad->contenido->titulo }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Activity Content -->
    <div class="activity-content" style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); padding: 2rem;">
        <div class="question-section" style="margin-bottom: 2rem;">
            <h3 style="color: #2c3e50; margin-bottom: 1rem;">Pregunta:</h3>
            <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #1976d2;">
                <p style="color: #2c3e50; font-size: 1.1rem; line-height: 1.6; margin: 0;">{{ $actividad->pregunta['texto'] }}</p>
            </div>
        </div>

        @if(Auth::user()->isAlumno())
            @if($respuestaUsuario)
                <!-- Mostrar respuesta ya enviada -->
                <div class="response-section" style="margin-bottom: 2rem;">
                    <h3 style="color: #2c3e50; margin-bottom: 1rem;">Tu Respuesta:</h3>
                    <div style="padding: 1.5rem; background: {{ $respuestaUsuario->es_correcta ? '#d4edda' : '#f8d7da' }}; border-radius: 8px; border-left: 4px solid {{ $respuestaUsuario->es_correcta ? '#28a745' : '#dc3545' }};">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <span style="font-size: 1.5rem;">{{ $respuestaUsuario->es_correcta ? '✅' : '❌' }}</span>
                            <div>
                                <strong style="color: {{ $respuestaUsuario->es_correcta ? '#155724' : '#721c24' }};">
                                    {{ $respuestaUsuario->es_correcta ? 'Respuesta Correcta' : 'Respuesta Incorrecta' }}
                                </strong>
                                <p style="margin: 0; color: {{ $respuestaUsuario->es_correcta ? '#155724' : '#721c24' }};">
                                    Puntos obtenidos: {{ $respuestaUsuario->puntos_obtenidos }}/{{ $actividad->puntos }}
                                </p>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <strong>Tu respuesta:</strong>
                            @if($actividad->tipo === 'opcion_multiple')
                                {{ $actividad->opciones[$respuestaUsuario->respuesta[0]] ?? 'Respuesta no válida' }}
                            @elseif($actividad->tipo === 'verdadero_falso')
                                {{ ucfirst($respuestaUsuario->respuesta[0]) }}
                            @else
                                {{ implode(', ', $respuestaUsuario->respuesta) }}
                            @endif
                        </div>
                        
                        @if(!$respuestaUsuario->es_correcta && $actividad->respuesta_correcta)
                            <div style="margin-bottom: 1rem;">
                                <strong>Respuesta correcta:</strong>
                                @if($actividad->tipo === 'opcion_multiple')
                                    @foreach($actividad->respuesta_correcta as $correcta)
                                        {{ $actividad->opciones[$correcta] ?? '' }}{{ !$loop->last ? ', ' : '' }}
                                    @endforeach
                                @else
                                    {{ implode(', ', $actividad->respuesta_correcta) }}
                                @endif
                            </div>
                        @endif
                        
                        @if($actividad->explicacion)
                            <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(0,0,0,0.1);">
                                <strong>Explicación:</strong>
                                <p style="margin: 0.5rem 0 0 0;">{{ $actividad->explicacion }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- Formulario para responder -->
                <form method="POST" action="{{ route('actividades.responder', [$curso, $actividad]) }}" style="margin-bottom: 2rem;">
                    @csrf
                    
                    <div class="answer-section" style="margin-bottom: 2rem;">
                        <h3 style="color: #2c3e50; margin-bottom: 1rem;">Tu Respuesta:</h3>
                        
                        @if($actividad->tipo === 'opcion_multiple')
                            <div style="display: grid; gap: 1rem;">
                                @foreach($actividad->opciones as $index => $opcion)
                                    <label style="display: flex; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; cursor: pointer; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#e3f2fd'" onmouseout="this.style.backgroundColor='#f8f9fa'">
                                        <input type="radio" name="respuesta" value="{{ $index }}" required style="margin-right: 1rem;">
                                        <span>{{ $opcion }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @elseif($actividad->tipo === 'verdadero_falso')
                            <div style="display: grid; gap: 1rem;">
                                <label style="display: flex; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; cursor: pointer; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#e3f2fd'" onmouseout="this.style.backgroundColor='#f8f9fa'">
                                    <input type="radio" name="respuesta" value="verdadero" required style="margin-right: 1rem;">
                                    <span>Verdadero</span>
                                </label>
                                <label style="display: flex; align-items: center; padding: 1rem; background: #f8f9fa; border-radius: 8px; cursor: pointer; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#e3f2fd'" onmouseout="this.style.backgroundColor='#f8f9fa'">
                                    <input type="radio" name="respuesta" value="falso" required style="margin-right: 1rem;">
                                    <span>Falso</span>
                                </label>
                            </div>
                        @elseif($actividad->tipo === 'respuesta_corta')
                            <input type="text" name="respuesta" required style="width: 100%; padding: 1rem; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem;" placeholder="Escribe tu respuesta aquí...">
                        @elseif($actividad->tipo === 'ensayo')
                            <textarea name="respuesta" required rows="8" style="width: 100%; padding: 1rem; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; resize: vertical;" placeholder="Desarrolla tu respuesta aquí..."></textarea>
                        @endif
                    </div>
                    
                    <div style="text-align: center;">
                        <button type="submit" style="padding: 1rem 2rem; background: #1976d2; color: white; border: none; border-radius: 8px; font-size: 1rem; cursor: pointer; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#1565c0'" onmouseout="this.style.backgroundColor='#1976d2'">
                            📤 Enviar Respuesta
                        </button>
                    </div>
                </form>
            @endif
        @else
            <!-- Vista para maestros/admin -->
            <div class="teacher-view" style="margin-bottom: 2rem;">
                <h3 style="color: #2c3e50; margin-bottom: 1rem;">Información de la Actividad:</h3>
                
                @if($actividad->tipo === 'opcion_multiple')
                    <div style="margin-bottom: 1rem;">
                        <strong>Opciones:</strong>
                        <ul style="margin: 0.5rem 0; padding-left: 2rem;">
                            @foreach($actividad->opciones as $index => $opcion)
                                <li style="margin: 0.5rem 0; {{ in_array($index, $actividad->respuesta_correcta) ? 'color: #28a745; font-weight: bold;' : '' }}">
                                    {{ $opcion }} {{ in_array($index, $actividad->respuesta_correcta) ? '✓' : '' }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @elseif($actividad->tipo === 'verdadero_falso')
                    <div style="margin-bottom: 1rem;">
                        <strong>Respuesta correcta:</strong> {{ ucfirst(implode(', ', $actividad->respuesta_correcta)) }}
                    </div>
                @elseif($actividad->tipo === 'respuesta_corta')
                    <div style="margin-bottom: 1rem;">
                        <strong>Respuestas correctas:</strong> {{ implode(', ', $actividad->respuesta_correcta) }}
                    </div>
                @endif
                
                @if($actividad->explicacion)
                    <div style="margin-bottom: 1rem;">
                        <strong>Explicación:</strong>
                        <p style="margin: 0.5rem 0;">{{ $actividad->explicacion }}</p>
                    </div>
                @endif
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-top: 1rem;">
                    <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #1976d2;">{{ $actividad->respuestas()->count() }}</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">Respuestas</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #28a745;">{{ $actividad->respuestas()->where('es_correcta', true)->count() }}</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">Correctas</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                        <div style="font-size: 1.5rem; font-weight: bold; color: #dc3545;">{{ $actividad->respuestas()->where('es_correcta', false)->count() }}</div>
                        <div style="font-size: 0.9rem; color: #6c757d;">Incorrectas</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Navigation -->
    <div style="margin-top: 2rem; text-align: center;">
        <a href="{{ route('cursos.show', $curso) }}" style="padding: 0.75rem 1.5rem; background: #6c757d; color: white; text-decoration: none; border-radius: 8px; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#5a6268'" onmouseout="this.style.backgroundColor='#6c757d'">
            ← Volver al Curso
        </a>
    </div>
</div>
@endsection