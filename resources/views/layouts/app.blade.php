<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema Escolar')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
    @yield('scripts')
</body>
</html>

