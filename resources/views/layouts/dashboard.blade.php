<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - UHTA')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/dashboard/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard/courses.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard/forms.css') }}">
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar Component -->
        @include('components.dashboard.sidebar')
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="content-header">
                <h1 class="page-title">@yield('page-title')</h1>
                <p class="page-subtitle">@yield('page-subtitle')</p>
            </div>
            
            <div class="content-body">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset('js/dashboard/main.js') }}"></script>
    @stack('scripts')
</body>
</html>