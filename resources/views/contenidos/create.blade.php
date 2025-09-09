@extends('layouts.dashboard')

@section('title', 'Agregar Contenido')
@section('page-title', 'Agregar Contenido')
@section('page-subtitle', 'Curso: ' . $curso->nombre)

@section('content')
<div class="form-container">
    <form method="POST" action="{{ route('contenidos.store', $curso) }}" enctype="multipart/form-data">
        @csrf
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Título del Contenido</label>
                <input type="text" name="titulo" class="form-input" value="{{ old('titulo') }}" required placeholder="Ej: Introducción a las Ecuaciones">
                @error('titulo')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Tipo de Contenido</label>
                <select name="tipo" class="form-select" required id="tipo-contenido">
                    <option value="">Seleccionar tipo</option>
                    <option value="video" {{ old('tipo') == 'video' ? 'selected' : '' }}>🎥 Video</option>
                    <option value="texto" {{ old('tipo') == 'texto' ? 'selected' : '' }}>📄 Texto</option>
                    <option value="pdf" {{ old('tipo') == 'pdf' ? 'selected' : '' }}>📋 PDF</option>
                    <option value="imagen" {{ old('tipo') == 'imagen' ? 'selected' : '' }}>🖼️ Imagen</option>
                    <option value="audio" {{ old('tipo') == 'audio' ? 'selected' : '' }}>🎵 Audio</option>
                </select>
                @error('tipo')
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
            <textarea name="descripcion" class="form-textarea" rows="3" placeholder="Breve descripción del contenido...">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <!-- Archivo Upload -->
        <div class="form-group" id="archivo-group">
            <label class="form-label">Archivo</label>
            <input type="file" name="archivo" class="form-input" accept=".mp4,.avi,.mov,.pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.mp3,.wav">
            <small class="form-help">Formatos soportados: Videos (MP4, AVI, MOV), PDFs, Documentos, Imágenes, Audio</small>
            @error('archivo')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <!-- Contenido de Texto -->
        <div class="form-group" id="texto-group" style="display: none;">
            <label class="form-label">Contenido de Texto</label>
            <textarea name="contenido_texto" class="form-textarea" rows="10" placeholder="Escribe el contenido del texto aquí...">{{ old('contenido_texto') }}</textarea>
            @error('contenido_texto')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Guardar Contenido</button>
            <a href="{{ route('cursos.show', $curso) }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoSelect = document.getElementById('tipo-contenido');
    const archivoGroup = document.getElementById('archivo-group');
    const textoGroup = document.getElementById('texto-group');
    
    tipoSelect.addEventListener('change', function() {
        if (this.value === 'texto') {
            archivoGroup.style.display = 'none';
            textoGroup.style.display = 'block';
        } else {
            archivoGroup.style.display = 'block';
            textoGroup.style.display = 'none';
        }
    });
});
</script>
@endpush