@extends('layouts.dashboard')

@section('title', 'Crear Actividad')
@section('page-title', 'Crear Nueva Actividad')
@section('page-subtitle', 'Curso: ' . $curso->nombre)

@section('content')
<div class="form-container">
    <form method="POST" action="{{ route('actividades.store', $curso) }}">
        @csrf
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Título de la Actividad</label>
                <input type="text" name="titulo" class="form-input" value="{{ old('titulo') }}" required placeholder="Ej: Quiz de Álgebra Básica">
                @error('titulo')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Tipo de Actividad</label>
                <select name="tipo" class="form-select" required id="tipo-actividad">
                    <option value="">Seleccionar tipo</option>
                    <option value="opcion_multiple" {{ old('tipo') == 'opcion_multiple' ? 'selected' : '' }}>☑️ Opción Múltiple</option>
                    <option value="verdadero_falso" {{ old('tipo') == 'verdadero_falso' ? 'selected' : '' }}>✅ Verdadero/Falso</option>
                    <option value="respuesta_corta" {{ old('tipo') == 'respuesta_corta' ? 'selected' : '' }}>✏️ Respuesta Corta</option>
                    <option value="ensayo" {{ old('tipo') == 'ensayo' ? 'selected' : '' }}>📝 Ensayo</option>
                </select>
                @error('tipo')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Contenido Relacionado (Opcional)</label>
                <select name="contenido_id" class="form-select">
                    <option value="">Sin contenido relacionado</option>
                    @foreach($contenidos as $contenido)
                        <option value="{{ $contenido->id }}" {{ old('contenido_id') == $contenido->id ? 'selected' : '' }}>
                            {{ $contenido->icono_tipo }} {{ $contenido->titulo }}
                        </option>
                    @endforeach
                </select>
                @error('contenido_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Puntos</label>
                <input type="number" name="puntos" class="form-input" value="{{ old('puntos', 10) }}" required min="1" max="100">
                @error('puntos')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Orden</label>
                <input type="number" name="orden" class="form-input" value="{{ old('orden', 0) }}" required min="0">
                @error('orden')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-textarea" rows="3" required placeholder="Describe qué evaluará esta actividad...">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label class="form-label">Pregunta</label>
            <textarea name="pregunta[texto]" class="form-textarea" rows="4" required placeholder="Escribe la pregunta aquí...">{{ old('pregunta.texto') }}</textarea>
            @error('pregunta')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <!-- Opciones para Opción Múltiple -->
        <div id="opciones-group" class="form-group" style="display: none;">
            <label class="form-label">Opciones de Respuesta</label>
            <div class="opciones-container">
                @for($i = 0; $i < 4; $i++)
                    <div class="opcion-item">
                        <input type="text" name="opciones[{{ $i }}]" class="form-input" placeholder="Opción {{ $i + 1 }}" value="{{ old('opciones.' . $i) }}">
                        <input type="checkbox" name="respuesta_correcta[]" value="{{ $i }}" {{ in_array($i, old('respuesta_correcta', [])) ? 'checked' : '' }}>
                        <label>Correcta</label>
                    </div>
                @endfor
            </div>
        </div>
        
        <!-- Respuesta para Verdadero/Falso -->
        <div id="verdadero-falso-group" class="form-group" style="display: none;">
            <label class="form-label">Respuesta Correcta</label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="respuesta_correcta[]" value="verdadero" {{ in_array('verdadero', old('respuesta_correcta', [])) ? 'checked' : '' }}>
                    Verdadero
                </label>
                <label>
                    <input type="radio" name="respuesta_correcta[]" value="falso" {{ in_array('falso', old('respuesta_correcta', [])) ? 'checked' : '' }}>
                    Falso
                </label>
            </div>
        </div>
        
        <!-- Respuestas para Respuesta Corta -->
        <div id="respuesta-corta-group" class="form-group" style="display: none;">
            <label class="form-label">Respuestas Correctas (una por línea)</label>
            <textarea name="respuestas_cortas" class="form-textarea" rows="4" placeholder="Escribe las posibles respuestas correctas, una por línea...">{{ old('respuestas_cortas') }}</textarea>
            <small class="form-help">Puedes agregar múltiples respuestas correctas, una por línea</small>
        </div>
        
        <div class="form-group">
            <label class="form-label">Explicación (Opcional)</label>
            <textarea name="explicacion" class="form-textarea" rows="3" placeholder="Explicación de la respuesta correcta...">{{ old('explicacion') }}</textarea>
            @error('explicacion')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Crear Actividad</button>
            <a href="{{ route('cursos.show', $curso) }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.getElementById('tipo-actividad');
    const opcionesGroup = document.getElementById('opciones-group');
    const verdaderoFalsoGroup = document.getElementById('verdadero-falso-group');
    const respuestaCortaGroup = document.getElementById('respuesta-corta-group');
    
    tipoSelect.addEventListener('change', function() {
        // Ocultar todos los grupos
        opcionesGroup.style.display = 'none';
        verdaderoFalsoGroup.style.display = 'none';
        respuestaCortaGroup.style.display = 'none';
        
        // Mostrar el grupo correspondiente
        switch(this.value) {
            case 'opcion_multiple':
                opcionesGroup.style.display = 'block';
                break;
            case 'verdadero_falso':
                verdaderoFalsoGroup.style.display = 'block';
                break;
            case 'respuesta_corta':
                respuestaCortaGroup.style.display = 'block';
                break;
        }
    });
    
    // Trigger change event if there's an old value
    if (tipoSelect.value) {
        tipoSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush