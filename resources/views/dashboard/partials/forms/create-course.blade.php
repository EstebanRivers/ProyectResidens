<div id="create-course-form" class="form-container" style="display: none; margin-bottom: 2rem;">
    <h3 style="color: #2c3e50; margin-bottom: 1.5rem;">Crear Nuevo Curso</h3>
    <form method="POST" action="{{ route('cursos.store') }}">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nombre del Curso</label>
                <input type="text" name="nombre" class="form-input" required placeholder="Ej: Matemáticas I">
            </div>
            
            <div class="form-group">
                <label class="form-label">Código del Curso</label>
                <input type="text" name="codigo" class="form-input" required placeholder="Ej: MAT101">
            </div>
            
            <div class="form-group">
                <label class="form-label">Créditos</label>
                <select name="creditos" class="form-select" required>
                    <option value="">Seleccionar créditos</option>
                    <option value="1">1 Crédito</option>
                    <option value="2">2 Créditos</option>
                    <option value="3">3 Créditos</option>
                    <option value="4">4 Créditos</option>
                    <option value="5">5 Créditos</option>
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
            <textarea name="descripcion" class="form-textarea" rows="4" placeholder="Describe el contenido y objetivos del curso..."></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-primary">Crear Curso</button>
            <button type="button" class="btn-secondary" onclick="hideCreateCourseForm()">Cancelar</button>
        </div>
    </form>
</div>