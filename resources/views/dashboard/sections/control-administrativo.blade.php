<!-- Control Administrativo Section -->
<div id="control-administrativo" class="section-content">
    <div class="info-card">
        <h3>Control Administrativo</h3>
        <p style="color: #6c757d; margin-bottom: 2rem;">Herramientas administrativas y reportes del sistema.</p>
        
        @if(Auth::user()->isAdmin())
            <div class="stats-grid" style="margin-bottom: 2rem;">
                <div class="stat-item">
                    <div class="stat-number">{{ \App\Models\User::count() }}</div>
                    <div class="stat-label">Total Usuarios</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ \App\Models\Curso::count() }}</div>
                    <div class="stat-label">Total Cursos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ \App\Models\Contenido::count() }}</div>
                    <div class="stat-label">Contenidos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ \App\Models\Actividad::count() }}</div>
                    <div class="stat-label">Actividades</div>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <button class="btn-primary" style="padding: 1rem; background: #1976d2; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    📊 Generar Reportes
                </button>
                <button class="btn-secondary" style="padding: 1rem; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    👥 Gestionar Usuarios
                </button>
                <button class="btn-secondary" style="padding: 1rem; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    🏫 Gestionar Cursos
                </button>
                <button class="btn-secondary" style="padding: 1rem; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    ⚙️ Configuración Sistema
                </button>
            </div>
        @else
            <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 8px;">
                <p style="color: #6c757d;">No tienes permisos para acceder a las herramientas administrativas.</p>
            </div>
        @endif
    </div>
</div>