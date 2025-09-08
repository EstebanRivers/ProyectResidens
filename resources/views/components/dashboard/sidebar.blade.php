<div class="sidebar">
    <!-- Header del Sidebar -->
    <div class="sidebar-header">
        <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA" class="sidebar-logo">
    </div>

    <!-- Navegación -->
    <nav class="sidebar-nav">
        <a href="#" class="nav-item" data-section="mi-informacion">
            <span class="nav-icon"></span>
            Mi información
        </a>
        
        <a href="#" class="nav-item" data-section="cursos">
            <span class="nav-icon"></span>
            Cursos
        </a>
        
        <a href="#" class="nav-item" data-section="facturacion">
            <span class="nav-icon"></span>
            Facturación
        </a>
        
        <a href="#" class="nav-item" data-section="control-administrativo">
            <span class="nav-icon"></span>
            Control Administrativo
        </a>
        
        <a href="#" class="nav-item" data-section="ajustes">
            <span class="nav-icon"></span>
            Ajustes
        </a>
    </nav>

    <!-- Footer del Sidebar -->
    <div class="sidebar-footer">
        <!-- Botón de Logout -->
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">
                <span class="logout-icon"></span>
                Cerrar sesión
            </button>
        </form>

        <!-- Logo del Footer -->
        <div class="footer-logo">
            <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA">
        </div>
    </div>
</div>