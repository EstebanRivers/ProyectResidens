<x-app-layout>
    <x-slot name="title">Dashboard - {{ ucfirst(Auth::user()->role) }}</x-slot>
    
    <div class="dashboard-container">
        <!-- Sidebar -->
        <x-dashboard.sidebar />
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="content-header">
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">Panel de control principal</p>
            </div>
            
            <div class="content-body">
<div class="dashboard-sections">
    <!-- Welcome Section (Default) -->
    <div class="welcome-section active">
        <div class="welcome-content">
            <img src="{{ asset('images/uhta-logo.png') }}" alt="UMI" class="welcome-logo">
            <p class="welcome-text">¡Bienvenido(a) {{ Auth::user()->name }}!</p>
        </div>
    </div>

    <!-- Dynamic Sections -->
    @include('dashboard.sections.mi-informacion')
    @include('dashboard.sections.cursos.' . Auth::user()->role)
    @include('dashboard.sections.facturacion')
    @include('dashboard.sections.control-administrativo')
    @include('dashboard.sections.ajustes')
</div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script src="{{ asset('js/dashboard/main.js') }}"></script>
    @endpush
</x-app-layout>