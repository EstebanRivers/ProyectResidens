// Dashboard Main JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard JavaScript loaded');
    
    // Elementos del DOM
    const navItems = document.querySelectorAll('.nav-item[data-section]');
    const sections = document.querySelectorAll('.section-content');
    const welcomeSection = document.querySelector('.welcome-section');
    
    console.log('Nav items found:', navItems.length);
    console.log('Sections found:', sections.length);

    // Función para mostrar una sección específica
    function showSection(sectionId) {
        console.log('Showing section:', sectionId);
        
        // Ocultar la sección de bienvenida
        if (welcomeSection) {
            welcomeSection.style.display = 'none';
        }
        
        // Ocultar todas las secciones
        sections.forEach(section => {
            section.style.display = 'none';
        });
        
        // Mostrar la sección seleccionada
        const targetSection = document.getElementById(sectionId);
        if (targetSection) {
            targetSection.style.display = 'block';
            console.log('Section displayed:', sectionId);
        } else {
            console.error('Section not found:', sectionId);
        }
        
        // Actualizar navegación activa
        navItems.forEach(item => {
            item.classList.remove('active');
        });
        
        // Marcar el elemento de navegación como activo
        const activeNavItem = document.querySelector(`[data-section="${sectionId}"]`);
        if (activeNavItem) {
            activeNavItem.classList.add('active');
        }
        
        // Guardar la sección actual en localStorage
        localStorage.setItem('currentSection', sectionId);
    }

    // Función para mostrar la página de bienvenida
    function showWelcome() {
        console.log('Showing welcome section');
        
        sections.forEach(section => {
            section.style.display = 'none';
        });
        
        if (welcomeSection) {
            welcomeSection.style.display = 'flex';
        }
        
        navItems.forEach(item => {
            item.classList.remove('active');
        });
        
        localStorage.removeItem('currentSection');
    }

    // Event listeners para navegación
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.getAttribute('data-section');
            console.log('Nav item clicked:', sectionId);
            
            if (sectionId) {
                showSection(sectionId);
            }
        });
    });

    // Funcionalidad para ajustes (tabs)
    const ajustesTabs = document.querySelectorAll('.ajustes-tab');
    const ajustesSections = document.querySelectorAll('.ajustes-section');

    ajustesTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remover clase activa de todos los tabs
            ajustesTabs.forEach(t => t.classList.remove('active'));
            ajustesSections.forEach(s => s.classList.remove('active'));
            
            // Activar el tab seleccionado
            this.classList.add('active');
            const targetSection = document.getElementById(targetTab);
            if (targetSection) {
                targetSection.classList.add('active');
            }
        });
    });

    // Restaurar la sección guardada o mostrar bienvenida por defecto
    const savedSection = localStorage.getItem('currentSection');
    if (savedSection && document.getElementById(savedSection)) {
        showSection(savedSection);
    } else {
        showWelcome();
    }

    // Manejar formularios
    const forms = ['perfil-form', 'seguridad-form', 'notificaciones-form'];
    
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Configuración guardada exitosamente');
            });
        }
    });
});