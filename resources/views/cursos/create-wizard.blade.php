@extends('layouts.dashboard')

@section('title', 'Crear Curso - Asistente')
@section('page-title', 'Crear Nuevo Curso')
@section('page-subtitle', 'Asistente de creación paso a paso')

@section('content')
<div class="course-wizard">
    <div class="wizard-container">
        <!-- Progress Steps -->
        <div class="wizard-steps">
            <div class="step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-label">Información Básica</div>
            </div>
            <div class="step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-label">Estructura del Curso</div>
            </div>
            <div class="step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-label">Contenido y Actividades</div>
            </div>
            <div class="step" data-step="4">
                <div class="step-number">4</div>
                <div class="step-label">Revisión Final</div>
            </div>
        </div>

        <form id="course-wizard-form" method="POST" action="{{ route('cursos.store-wizard') }}" enctype="multipart/form-data">
            @csrf
            
            <!-- Step 1: Información Básica -->
            <div class="wizard-step active" data-step="1">
                <div class="step-content">
                    <h2>Información Básica del Curso</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Nombre del Curso</label>
                            <input type="text" name="nombre" class="form-input" required placeholder="Ej: Matemáticas Básicas">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Código del Curso</label>
                            <input type="text" name="codigo" class="form-input" required placeholder="Ej: MAT101">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Créditos</label>
                            <select name="creditos" class="form-select" required>
                                <option value="">Seleccionar créditos</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}">{{ $i }} Crédito{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Cupo Máximo</label>
                            <input type="number" name="cupo_maximo" class="form-input" required min="1" max="100" value="30">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Período Académico</label>
                            <input type="text" name="periodo_academico" class="form-input" required placeholder="Ej: 2025-1">
                        </div>
                        
                        @if(Auth::user()->isAdmin())
                        <div class="form-group">
                            <label class="form-label">Maestro Asignado</label>
                            <select name="maestro_id" class="form-select" required>
                                <option value="">Seleccionar maestro</option>
                                @foreach(\App\Models\User::maestros()->get() as $maestro)
                                    <option value="{{ $maestro->id }}">{{ $maestro->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @else
                            <input type="hidden" name="maestro_id" value="{{ Auth::id() }}">
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Descripción del Curso</label>
                        <textarea name="descripcion" class="form-textarea" rows="4" placeholder="Describe el contenido, objetivos y metodología del curso..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Step 2: Estructura del Curso -->
            <div class="wizard-step" data-step="2">
                <div class="step-content">
                    <h2>Estructura del Curso</h2>
                    <p class="step-description">Define cuántas lecciones tendrá tu curso y sus títulos</p>
                    
                    <div class="form-group">
                        <label class="form-label">Número de Lecciones</label>
                        <input type="number" id="num-lecciones" class="form-input" min="1" max="20" value="5" placeholder="¿Cuántas lecciones tendrá el curso?">
                    </div>
                    
                    <div id="lecciones-container" class="lecciones-container">
                        <!-- Las lecciones se generarán dinámicamente aquí -->
                    </div>
                    
                    <button type="button" class="btn-secondary" onclick="generateLessons()">Generar Estructura de Lecciones</button>
                </div>
            </div>

            <!-- Step 3: Contenido y Actividades -->
            <div class="wizard-step" data-step="3">
                <div class="step-content">
                    <h2>Contenido y Actividades</h2>
                    <p class="step-description">Configura el contenido y actividades para cada lección</p>
                    
                    <div id="contenido-actividades-container">
                        <!-- El contenido se generará dinámicamente basado en las lecciones -->
                    </div>
                </div>
            </div>

            <!-- Step 4: Revisión Final -->
            <div class="wizard-step" data-step="4">
                <div class="step-content">
                    <h2>Revisión Final</h2>
                    <p class="step-description">Revisa toda la información antes de crear el curso</p>
                    
                    <div id="review-container" class="review-container">
                        <!-- Resumen del curso se generará aquí -->
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="wizard-navigation">
                <button type="button" id="prev-btn" class="btn-secondary" onclick="previousStep()" style="display: none;">
                    ← Anterior
                </button>
                <button type="button" id="next-btn" class="btn-primary" onclick="nextStep()">
                    Siguiente →
                </button>
                <button type="submit" id="submit-btn" class="btn-success" style="display: none;">
                    🎓 Crear Curso
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard/wizard.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/dashboard/course-wizard.js') }}"></script>
@endpush
@endsection