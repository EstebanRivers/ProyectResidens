@extends('layouts.dashboard')

@section('title', 'Editar Curso')
@section('page-title', 'Editar Curso')
@section('page-subtitle', $curso->nombre . ' - ' . $curso->codigo)

@section('content')
<div class="form-container">
    <form method="POST" action="{{ route('cursos.update', $curso) }}">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nombre del Curso</label>
                <input type="text" name="nombre" class="form-input" value="{{ old('nombre', $curso->nombre) }}" required placeholder="Ej: Matemáticas I">
                @error('nombre')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Código del Curso</label>
                <input type="text" name="codigo" class="form-input" value="{{ old('codigo', $curso->codigo) }}" required placeholder="Ej: MAT101">
                @error('codigo')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Créditos</label>
                <select name="creditos" class="form-select" required>
                    <option value="">Seleccionar créditos</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ old('creditos', $curso->creditos) == $i ? 'selected' : '' }}>{{ $i }} Crédito{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
                @error('creditos')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Cupo Máximo</label>
                <input type="number" name="cupo_maximo" class="form-input" value="{{ old('cupo_maximo', $curso->cupo_maximo) }}" required min="1" max="100">
                @error('cupo_maximo')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Período Académico</label>
                <input type="text" name="periodo_academico" class="form-input" value="{{ old('periodo_academico', $curso->periodo_academico) }}" required placeholder="Ej: 2025-1">
                @error('periodo_academico')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            @if(Auth::user()->isAdmin())
            <div class="form-group">
                <label class="form-label">Maestro Asignado</label>
                <select name="maestro_id" class="form-select" required>
                    <option value="">Seleccionar maestro</option>
                    @foreach($maestros as $maestro)
                        <option value="{{ $maestro->id }}" {{ old('maestro_id', $curso->maestro_id) == $maestro->id ? 'selected' : '' }}>
                            {{ $maestro->name }}
                        </option>
                    @endforeach
                </select>
                @error('maestro_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            @else
                <input type="hidden" name="maestro_id" value="{{ $curso->maestro_id }}">
            @endif
            
            <div class="form-group">
                <label class="form-label">Estado</label>
                <select name="activo" class="form-select" required>
                    <option value="1" {{ old('activo', $curso->activo) ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ !old('activo', $curso->activo) ? 'selected' : '' }}>Inactivo</option>
                </select>
                @error('activo')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Descripción del Curso</label>
            <textarea name="descripcion" class="form-textarea" rows="4" placeholder="Describe el contenido, objetivos y metodología del curso...">{{ old('descripcion', $curso->descripcion) }}</textarea>
            @error('descripcion')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Actualizar Curso</button>
            <a href="{{ route('cursos.show', $curso) }}" class="btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection