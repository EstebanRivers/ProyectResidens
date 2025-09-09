<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema Escolar')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
    
    <style>
        /* Modal Styles */
        .modal {
            transition: all 0.3s ease;
        }
        
        .modal.hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        .modal.flex {
            opacity: 1;
            pointer-events: all;
        }
        
        .modal-content {
            transform: scale(0.95);
            transition: transform 0.3s ease;
        }
        
        .modal.flex .modal-content {
            transform: scale(1);
        }
        
        /* Alert Styles */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .alert-info {
            background: #cce5ff;
            color: #0066cc;
            border: 1px solid #99d6ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Mostrar mensajes de sesión -->
        @if(session('success'))
            <div class="alert alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                ❌ {{ session('error') }}
            </div>
        @endif
        
        @if(session('warning'))
            <div class="alert alert-warning">
                ⚠️ {{ session('warning') }}
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info">
                ℹ️ {{ session('info') }}
            </div>
        @endif
        
        @yield('content')
    </div>

    <!-- Modal de Confirmación de Inscripción -->
    <div id="inscriptionModal" class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="modal-content bg-white rounded-lg p-6 max-w-md w-full mx-4">
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
    <div id="unsubscribeModal" class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="modal-content bg-white rounded-lg p-6 max-w-md w-full mx-4">
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

    @yield('scripts')
    
    <script>
        // Funciones para modales de inscripción
        function showInscriptionModal(courseId, courseName) {
            const modal = document.getElementById('inscriptionModal');
            const form = document.getElementById('inscriptionForm');
            const courseNameSpan = document.getElementById('courseNameInscription');
            
            courseNameSpan.textContent = courseName;
            form.action = `/cursos/${courseId}/inscribir`;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeInscriptionModal() {
            const modal = document.getElementById('inscriptionModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function showUnsubscribeModal(courseId, courseName) {
            const modal = document.getElementById('unsubscribeModal');
            const form = document.getElementById('unsubscribeForm');
            const courseNameSpan = document.getElementById('courseNameUnsubscribe');
            
            courseNameSpan.textContent = courseName;
            form.action = `/cursos/${courseId}/desinscribir`;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeUnsubscribeModal() {
            const modal = document.getElementById('unsubscribeModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Cerrar modales al hacer clic fuera de ellos
        document.addEventListener('click', function(event) {
            const inscriptionModal = document.getElementById('inscriptionModal');
            const unsubscribeModal = document.getElementById('unsubscribeModal');
            
            if (event.target === inscriptionModal) {
                closeInscriptionModal();
            }
            
            if (event.target === unsubscribeModal) {
                closeUnsubscribeModal();
            }
        });

        // Cerrar modales con la tecla Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeInscriptionModal();
                closeUnsubscribeModal();
            }
        });

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
</body>
</html>