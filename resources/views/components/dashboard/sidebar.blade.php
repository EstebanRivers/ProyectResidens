<div class="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA" class="sidebar-logo">
    </div>
    
    <nav class="sidebar-nav">
        <a href="#" class="nav-item" data-section="mi-informacion">
            <span class="nav-icon">👤</span>
            <span class="nav-label">Mi información</span>
        </a>
        
        <a href="#" class="nav-item" data-section="cursos">
            <span class="nav-icon">💻</span>
            <span class="nav-label">Cursos</span>
        </a>
        
        <a href="#" class="nav-item" data-section="facturacion">
            <span class="nav-icon">📷</span>
            <span class="nav-label">Facturación</span>
        </a>
        
        <a href="#" class="nav-item" data-section="control-administrativo">
            <span class="nav-icon">📋</span>
            <span class="nav-label">Control Administrativo</span>
        </a>
        
        <a href="#" class="nav-item" data-section="ajustes">
            <span class="nav-icon">👥</span>
            <span class="nav-label">Ajustes</span>
        </a>
    </nav>
    
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">
                <span class="logout-icon">🔓</span>
                <span class="logout-label">Cerrar sesión</span>
            </button>
        </form>
        
        <div class="footer-logo">
            <img src="{{ asset('images/LOGO3.png') }}" alt="Mundo Imperial">
        </div>
    </div>
</div>