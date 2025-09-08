<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - UHTA')</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- CSS del Dashboard -->
    <link rel="stylesheet" href="{{ asset('css/dashboard/main.css') }}">
    
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA" class="sidebar-logo">
            </div>

            <nav class="sidebar-nav">
                <a href="#" class="nav-item active" data-section="mi-informacion">
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

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <span class="logout-icon"></span>
                        Cerrar sesión
                    </button>
                </form>

                <div class="footer-logo">
                    <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA">
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="content-header">
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                <p class="page-subtitle">@yield('page-subtitle', 'Panel de control')</p>
            </div>
            
            <div class="content-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Inscripción -->
    <div id="enrollModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirmar Inscripción</h3>
                <button type="button" class="modal-close" onclick="closeEnrollModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p id="enrollModalText">¿Estás seguro de que quieres inscribirte en este curso?</p>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" onclick="closeEnrollModal()">Cancelar</button>
                <button type="button" class="btn-primary" onclick="confirmEnrollment()">Confirmar Inscripción</button>
            </div>
        </div>
    </div>

    <!-- JavaScript del Dashboard -->
    <script src="{{ asset('js/dashboard/main.js') }}"></script>
    @stack('scripts')
</body>
</html>