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
    <link rel="stylesheet" href="{{ asset('css/dashboard/courses.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard/forms.css') }}">
    
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Component -->
        <x-dashboard.sidebar />
        
        <!-- Main Content -->
        <div class="main-content">
            @hasSection('page-title')
                <div class="content-header">
                    <h1 class="page-title">@yield('page-title')</h1>
                    <p class="page-subtitle">@yield('page-subtitle')</p>
                </div>
            @endif
            
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

    <!-- JavaScript del Dashboard -->
    <script src="{{ asset('js/dashboard/main.js') }}"></script>
    @stack('scripts')
</body>
</html>