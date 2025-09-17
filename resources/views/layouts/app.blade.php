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
    <!-- Modal de Confirmación de Inscripción -->
    <div id="inscriptionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 transform transition-all">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Confirmar Inscripción</h3>
                    <p class="text-sm text-gray-600">¿Estás seguro de que quieres inscribirte?</p>
                </div>
            </div>
            <div class="mb-4">
                <p class="text-gray-700">
                    <strong>Curso:</strong> <span id="courseNameInscription"></span>
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Al inscribirte tendrás acceso a todos los contenidos y actividades del curso.
                </p>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="closeInscriptionModal()" 
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancelar
                </button>
                <form id="inscriptionForm" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Confirmar Inscripción
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Desinscripción -->
    <div id="unsubscribeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 transform transition-all">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Confirmar Desinscripción</h3>
                    <p class="text-sm text-gray-600">¿Estás seguro de que quieres desinscribirte?</p>
                </div>
            </div>
            <div class="mb-4">
                <p class="text-gray-700">
                    <strong>Curso:</strong> <span id="courseNameUnsubscribe"></span>
                </p>
                <p class="text-sm text-red-600 mt-2">
                    ⚠️ Perderás el acceso a todos los contenidos y tu progreso se mantendrá guardado.
                </p>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="closeUnsubscribeModal()" 
                        class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancelar
                </button>
                <form id="unsubscribeForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        Confirmar Desinscripción
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>

