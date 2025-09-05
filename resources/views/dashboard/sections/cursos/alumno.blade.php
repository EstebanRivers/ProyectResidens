<div id="cursos" class="section-content">
    <div class="courses-management">
        <div class="courses-header">
            <div class="header-actions">
                <button class="btn btn-secondary" onclick="showMyCourses()">
                    <span class="btn-icon">📚</span>
                    Mis Cursos Inscritos
                </button>
            </div>
        </div>

        <!-- Available Courses -->
        <div id="available-courses" class="courses-section">
            <h3 class="section-title">Cursos Disponibles para Inscripción</h3>
            <div class="courses-grid">
                @foreach(\App\Models\Curso::activos()->whereDoesntHave('estudiantes', function($query) {
                    $query->where('estudiante_id', Auth::id());
                })->with(['maestro', 'estudiantes', 'contenidos'])->get() as $curso)
                    @include('dashboard.partials.course-card', [
                        'course' => (object)[
                            'id' => $curso->id,
                            'nombre' => $curso->nombre,
                            'codigo' => $curso->codigo,
                            'descripcion' => $curso->descripcion,
                            'cupo' => $curso->estudiantes->count() . '/' . $curso->cupo_maximo,
                            'creditos' => $curso->creditos,
                            'lecciones_count' => $curso->contenidos->count(),
                            'maestro' => $curso->maestro->name,
                            'periodo' => $curso->periodo_academico,
                            'available' => true
                        ]
                    ])
                @endforeach
            </div>
        </div>

        <!-- My Enrolled Courses -->
        <div id="my-courses" class="courses-section" style="display: none;">
            <h3 class="section-title">Mis Cursos Inscritos</h3>
            <div class="courses-grid">
                @foreach(Auth::user()->cursosComoEstudiante()->with(['maestro', 'contenidos', 'actividades'])->get() as $curso)
                    @php
                        $totalContenido = $curso->contenidos->count() + $curso->actividades->count();
                        $completado = $curso->progreso()->where('estudiante_id', Auth::id())->where('completado', true)->count();
                        $progreso = $totalContenido > 0 ? round(($completado / $totalContenido) * 100) : 0;
                    @endphp
                    @include('dashboard.partials.course-card', [
                        'course' => (object)[
                            'id' => $curso->id,
                            'nombre' => $curso->nombre,
                            'codigo' => $curso->codigo,
                            'descripcion' => $curso->descripcion,
                            'progreso' => $progreso,
                            'lecciones_progress' => $completado . '/' . $totalContenido,
                            'maestro' => $curso->maestro->name,
                            'periodo' => $curso->periodo_academico,
                            'enrolled' => true
                        ]
                    ])
                @endforeach
            </div>
        </div>
    </div>
</div>