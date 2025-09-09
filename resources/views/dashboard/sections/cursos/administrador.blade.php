<div id="cursos" class="section-content">
    <div class="course-management">
        <div class="course-header">
            <div class="header-actions">
                <a href="{{ route('cursos.create') }}" class="btn btn-primary">
                    <span class="btn-icon">➕</span>
                    Crear Nuevo Curso
                </a>
                <button class="btn btn-secondary" onclick="showCreateCourseForm()">
                    <span class="btn-icon">📝</span>
                    Crear Rápido
                </button>
            </div>
        </div>

        <!-- Create Course Form (Hidden by default) -->
        <div id="create-course-form" class="create-course-form" style="display: none; background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <div class="form-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #e9ecef;">
                <h3 style="color: #2c3e50; margin: 0;">Crear Nuevo Curso</h3>
                <button onclick="hideCreateCourseForm()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6c757d;">&times;</button>
            </div>
            
            <form method="POST" action="{{ route('cursos.store') }}">
                @csrf
                
                <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <div class="form-group">
                        <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; font-size: 0.9rem;">Nombre del Curso</label>
                        <input type="text" name="nombre" class="form-input" required placeholder="Ej: Matemáticas I" style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 8px; font-size: 0.9rem;">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; font-size: 0.9rem;">Código del Curso</label>
                        <input type="text" name="codigo" class="form-input" required placeholder="Ej: MAT101" style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 8px; font-size: 0.9rem;">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; font-size: 0.9rem;">Créditos</label>
                        <select name="creditos" class="form-select" required style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 8px; font-size: 0.9rem;">
                            <option value="">Seleccionar créditos</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} Crédito{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; font-size: 0.9rem;">Cupo Máximo</label>
                        <input type="number" name="cupo_maximo" class="form-input" required min="1" max="100" value="30" style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 8px; font-size: 0.9rem;">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; font-size: 0.9rem;">Período Académico</label>
                        <input type="text" name="periodo_academico" class="form-input" required placeholder="Ej: 2025-1" value="2025-1" style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 8px; font-size: 0.9rem;">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; font-size: 0.9rem;">Maestro Asignado</label>
                        <select name="maestro_id" class="form-select" required style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 8px; font-size: 0.9rem;">
                            <option value="">Seleccionar maestro</option>
                            @foreach(\App\Models\User::maestros()->get() as $maestro)
                                <option value="{{ $maestro->id }}">{{ $maestro->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 2rem;">
                    <label class="form-label" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; font-size: 0.9rem;">Descripción del Curso</label>
                    <textarea name="descripcion" class="form-textarea" rows="4" placeholder="Describe el contenido, objetivos y metodología del curso..." style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 8px; font-size: 0.9rem; min-height: 120px; resize: vertical;"></textarea>
                </div>
                
                <div class="form-actions" style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <button type="button" onclick="hideCreateCourseForm()" class="btn-secondary" style="padding: 0.75rem 1.5rem; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer;">Cancelar</button>
                    <button type="submit" class="btn-primary" style="padding: 0.75rem 1.5rem; background: #1976d2; color: white; border: none; border-radius: 8px; cursor: pointer;">Crear Curso</button>
                </div>
            </form>
        </div>

        <!-- Courses Grid -->
        <div class="courses-grid">
            @foreach(\App\Models\Curso::with(['maestro', 'estudiantes', 'contenidos', 'actividades'])->get() as $curso)
                <div class="course-card">
                    <div class="course-header">
                        <div class="course-title-section">
                            <h3 class="course-title">{{ $curso->nombre }}</h3>
                            <p class="course-code">{{ $curso->codigo }}</p>
                        </div>
                        <span class="course-status {{ $curso->activo ? 'active' : 'inactive' }}">
                            {{ $curso->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    
                    <p class="course-description">{{ $curso->descripcion }}</p>
                    
                    <div class="course-stats">
                        <div class="course-stat">
                            <div class="stat-number">{{ $curso->estudiantes->count() }}</div>
                            <div class="stat-label">Estudiantes</div>
                        </div>
                        <div class="course-stat">
                            <div class="stat-number">{{ $curso->creditos }}</div>
                            <div class="stat-label">Créditos</div>
                        </div>
                        <div class="course-stat">
                            <div class="stat-number">{{ $curso->contenidos->count() }}</div>
                            <div class="stat-label">Contenidos</div>
                        </div>
                    </div>
                    
                    <div class="course-teacher">
                        <strong>Profesor:</strong> {{ $curso->maestro->name }}<br>
                        <strong>Período:</strong> {{ $curso->periodo_academico }}
                    </div>
                    
                    <div class="course-actions">
                        <a href="{{ route('cursos.show', $curso) }}" class="btn btn-primary btn-small">Ver Curso</a>
                        <a href="{{ route('cursos.edit', $curso) }}" class="btn btn-outline btn-small">Editar</a>
                    </div>
                </div>
            @endforeach
            
            <!-- Create Course Card -->
            <div class="create-course-card" onclick="showCreateCourseForm()">
                <div class="create-course-content">
                    <div class="create-course-icon">➕</div>
                    <h3>Crear Nuevo Curso</h3>
                    <p>Haz clic para crear un curso rápidamente</p>
                </div>
            </div>
        </div>
    </div>
</div>