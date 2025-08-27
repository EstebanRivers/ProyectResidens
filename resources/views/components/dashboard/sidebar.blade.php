<div class="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/uhta-logo.png') }}" alt="UHTA" class="sidebar-logo">
    </div>
    
    <nav class="sidebar-nav">
        @php
            $menuItems = [
                'mi-informacion' => ['icon' => '👤', 'label' => 'Mi información'],
                'cursos' => ['icon' => '💻', 'label' => 'Cursos'],
                'facturacion' => ['icon' => '📷', 'label' => 'Facturación'],
                'control-administrativo' => ['icon' => '📋', 'label' => 'Control Administrativo'],
                'ajustes' => ['icon' => '👥', 'label' => 'Ajustes']
            ];
        @endphp
        
        @foreach($menuItems as $section => $item)
            <a href="#" class="nav-item" data-section="{{ $section }}">
                <span class="nav-icon">{{ $item['icon'] }}</span>
                <span class="nav-label">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>
    
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <button type="submit" class="logout-btn">
                <span class="logout-icon">🚪</span>
                <span class="logout-label">Cerrar sesión</span>
            </button>
        </form>
        
        <div class="footer-logo">
            <img src="{{ asset('images/LOGO3.png') }}" alt="Mundo Imperial">
        </div>
    </div>
</div>