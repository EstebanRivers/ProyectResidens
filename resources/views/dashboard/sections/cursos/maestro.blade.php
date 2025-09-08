<div id="cursos" class="section-content">
    <div class="course-management">
        <div class="course-header">
            <div class="header-actions">
                <a href="{{ route('cursos.create') }}" class="btn btn-primary">
                    <span class="btn-icon"></span>
                    Crear Nuevo Curso
                </a>
            </div>
        </div>

        <!-- My Courses Grid -->
        <div class="courses-grid">
            @foreach(\App\Models\Curso::where('maestro_id', Auth::id())->with(['estudiantes', 'contenidos', 'actividades'])->get() as $curso)
                @include('dashboard.partials.course-card', [
                    'course' => (object)[
                        'id' => $curso->id,
                        'nombre' => $curso->nombre,
                        'codigo' => $curso->codigo,
                        'descripcion' => $curso->descripcion,
                        'estudiantes_count' => $curso->estudiantes->count(),
                        'creditos' => $curso->creditos,
                        'lecciones_count' => $curso->contenidos->count(),
                        'actividades_count' => $curso->actividades->count(),
                        'periodo' => $curso->periodo_academico,
                        'activo' => $curso->activo,
                        'is_teacher' => true
                    ]
                ])
            @endforeach
            
            @include('dashboard.partials.create-course-card')
        </div>
    </div>
</div>