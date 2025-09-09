@extends('layouts.dashboard')

@section('title', $contenido->titulo)
@section('page-title', $contenido->titulo)
@section('page-subtitle', 'Curso: ' . $curso->nombre)

@section('content')
<div class="content-detail">
    @if(Auth::user()->isAlumno() && Auth::user()->cursosComoEstudiante()->where('curso_id', $curso->id)->exists())
        <!-- Marcar como completado automáticamente para alumnos inscritos -->
        @php
            $progreso = $curso->progreso()->where('estudiante_id', Auth::id())->where('contenido_id', $contenido->id)->first();
            if (!$progreso) {
                \App\Models\ProgresoCurso::create([
                    'curso_id' => $curso->id,
                    'estudiante_id' => Auth::id(),
                    'contenido_id' => $contenido->id,
                    'tipo' => 'contenido',
                    'completado' => false,
                    'fecha_inicio' => now()
                ]);
            }
        @endphp
    @endif
    
    <!-- Content Header -->
    <div class="content-header" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 2rem;">
        <div class="content-info">
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <span style="font-size: 2rem;">{{ $contenido->icono_tipo }}</span>
                <div>
                    <h1 style="color: #2c3e50; margin: 0;">{{ $contenido->titulo }}</h1>
                    @if($contenido->descripcion)
                        <p style="color: #6c757d; margin: 0;">{{ $contenido->descripcion }}</p>
                    @endif
                </div>
            </div>
            
            <div class="content-meta" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <span style="padding: 0.5rem 1rem; background: #e3f2fd; color: #1976d2; border-radius: 20px; font-size: 0.9rem;">📝 {{ ucfirst($contenido->tipo) }}</span>
                <span style="padding: 0.5rem 1rem; background: #f3e5f5; color: #7b1fa2; border-radius: 20px; font-size: 0.9rem;">📊 Orden: {{ $contenido->orden }}</span>
                <span style="padding: 0.5rem 1rem; background: {{ $contenido->activo ? '#d4edda' : '#f8d7da' }}; color: {{ $contenido->activo ? '#155724' : '#721c24' }}; border-radius: 20px; font-size: 0.9rem;">
                    {{ $contenido->activo ? '✅ Activo' : '❌ Inactivo' }}
                </span>
            </div>
        </div>
        
        @if(Auth::user()->isAdmin() || (Auth::user()->isMaestro() && $curso->maestro_id === Auth::id()))
            <div class="content-actions" style="margin-top: 1rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('contenidos.edit', [$curso, $contenido]) }}" class="btn-primary">✏️ Editar Contenido</a>
                <form method="POST" action="{{ route('contenidos.destroy', [$curso, $contenido]) }}" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este contenido?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-secondary">🗑️ Eliminar</button>
                </form>
            </div>
        @endif
    </div>

    <!-- Content Body -->
    <div class="content-body" style="background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); padding: 2rem;">
        @if($contenido->tipo === 'texto')
            <div class="text-content" style="line-height: 1.8; color: #2c3e50;">
                {!! nl2br(e($contenido->contenido_texto)) !!}
            </div>
        @elseif($contenido->tipo === 'video' && $contenido->archivo_url)
            <div class="video-content" style="text-align: center;">
                <video controls style="width: 100%; max-width: 800px; border-radius: 8px;">
                    <source src="{{ $contenido->archivo_url }}" type="video/mp4">
                    Tu navegador no soporta el elemento de video.
                </video>
            </div>
        @elseif($contenido->tipo === 'imagen' && $contenido->archivo_url)
            <div class="image-content" style="text-align: center;">
                <img src="{{ $contenido->archivo_url }}" alt="{{ $contenido->titulo }}" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
            </div>
        @elseif($contenido->tipo === 'audio' && $contenido->archivo_url)
            <div class="audio-content" style="text-align: center;">
                <audio controls style="width: 100%; max-width: 600px;">
                    <source src="{{ $contenido->archivo_url }}" type="audio/mpeg">
                    Tu navegador no soporta el elemento de audio.
                </audio>
            </div>
        @elseif($contenido->tipo === 'pdf' && $contenido->archivo_url)
            <div class="pdf-content" style="text-align: center;">
                <div style="margin-bottom: 1rem;">
                    <a href="{{ $contenido->archivo_url }}" target="_blank" style="padding: 1rem 2rem; background: #dc3545; color: white; text-decoration: none; border-radius: 8px; display: inline-block;">
                        📄 Abrir PDF en nueva ventana
                    </a>
                </div>
                <iframe src="{{ $contenido->archivo_url }}" style="width: 100%; height: 600px; border: none; border-radius: 8px;"></iframe>
            </div>
        @else
            <div class="no-content" style="text-align: center; padding: 2rem; color: #6c757d;">
                <p>No hay contenido disponible para mostrar.</p>
            </div>
        @endif
    </div>
    
    <!-- Navigation -->
    <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center;">
        @if(Auth::user()->isAlumno() && Auth::user()->cursosComoEstudiante()->where('curso_id', $curso->id)->exists())
            @php
                $progreso = $curso->progreso()->where('estudiante_id', Auth::id())->where('contenido_id', $contenido->id)->first();
            @endphp
            @if(!$progreso || !$progreso->completado)
                <form method="POST" action="{{ route('contenidos.marcar-completado', [$curso, $contenido]) }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="padding: 0.75rem 1.5rem; background: #28a745; color: white; border: none; border-radius: 8px; cursor: pointer; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#218838'" onmouseout="this.style.backgroundColor='#28a745'">
                        ✅ Marcar como Completado
                    </button>
                </form>
            @else
                <span style="padding: 0.75rem 1.5rem; background: #d4edda; color: #155724; border-radius: 8px; font-weight: 500;">
                    ✅ Completado el {{ $progreso->fecha_completado->format('d/m/Y') }}
                </span>
            @endif
        @endif
        
        <a href="{{ route('cursos.show', $curso) }}" style="padding: 0.75rem 1.5rem; background: #6c757d; color: white; text-decoration: none; border-radius: 8px; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#5a6268'" onmouseout="this.style.backgroundColor='#6c757d'">
            ← Volver al Curso
        </a>
    </div>
</div>

@if(Auth::user()->isAlumno() && Auth::user()->cursosComoEstudiante()->where('curso_id', $curso->id)->exists())
<script>
// Marcar progreso automáticamente después de cierto tiempo
setTimeout(function() {
    @if(!$progreso || !$progreso->completado)
        fetch('{{ route("contenidos.actualizar-progreso", [$curso, $contenido]) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                tiempo_dedicado: 5 // 5 minutos mínimo
            })
        });
    @endif
}, 300000); // 5 minutos
</script>
@endif
@endsection