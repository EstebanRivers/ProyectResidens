<!-- Mi Información Section -->
<div id="mi-informacion" class="section-content">
    <div class="info-grid">
        <div class="info-card">
            <h3>Datos Personales</h3>
            <div class="info-items">
                <div class="info-item">
                    <span class="info-label">Nombre:</span>
                    <span class="info-value">{{ Auth::user()->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ Auth::user()->email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Rol:</span>
                    <span class="info-value">{{ ucfirst(Auth::user()->role) }}</span>
                </div>
                @if(Auth::user()->matricula)
                <div class="info-item">
                    <span class="info-label">Matrícula:</span>
                    <span class="info-value">{{ Auth::user()->matricula }}</span>
                </div>
                @endif
                @if(Auth::user()->telefono)
                <div class="info-item">
                    <span class="info-label">Teléfono:</span>
                    <span class="info-value">{{ Auth::user()->telefono }}</span>
                </div>
                @endif
                @if(Auth::user()->direccion)
                <div class="info-item">
                    <span class="info-label">Dirección:</span>
                    <span class="info-value">{{ Auth::user()->direccion }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="info-card">
            <h3>Estadísticas</h3>
            <div class="stats-grid">
                @if(Auth::user()->isAdmin())
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\User::alumnos()->count() }}</div>
                        <div class="stat-label">Estudiantes</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\User::maestros()->count() }}</div>
                        <div class="stat-label">Maestros</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\Curso::activos()->count() }}</div>
                        <div class="stat-label">Cursos Activos</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\Calificacion::count() }}</div>
                        <div class="stat-label">Calificaciones</div>
                    </div>
                @elseif(Auth::user()->isMaestro())
                    @php
                        $cursos = Auth::user()->cursosComoMaestro()->with('estudiantes')->get();
                    @endphp
                    <div class="stat-item">
                        <div class="stat-number">{{ $cursos->count() }}</div>
                        <div class="stat-label">Mis Cursos</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $cursos->sum(fn($curso) => $curso->estudiantes->count()) }}</div>
                        <div class="stat-label">Estudiantes</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $cursos->where('activo', true)->count() }}</div>
                        <div class="stat-label">Cursos Activos</div>
                    </div>
                @else
                    <div class="stat-item">
                        <div class="stat-number">{{ Auth::user()->cursosComoEstudiante->count() }}</div>
                        <div class="stat-label">Cursos Inscritos</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ Auth::user()->calificaciones()->avg('calificacion') ? round(Auth::user()->calificaciones()->avg('calificacion'), 1) : 'N/A' }}</div>
                        <div class="stat-label">Promedio</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ Auth::user()->calificaciones()->count() }}</div>
                        <div class="stat-label">Calificaciones</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>