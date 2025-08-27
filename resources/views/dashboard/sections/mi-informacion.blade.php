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
            </div>
        </div>

        <div class="info-card">
            <h3>Estadísticas</h3>
            <div class="stats-grid">
                @if(Auth::user()->isAdmin())
                    @include('dashboard.partials.stats.admin')
                @elseif(Auth::user()->isMaestro())
                    @include('dashboard.partials.stats.maestro')
                @else
                    @include('dashboard.partials.stats.alumno')
                @endif
            </div>
        </div>
    </div>
</div>