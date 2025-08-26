// Dashboard Navigation JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const navItems = document.querySelectorAll('.nav-item');
    const sections = document.querySelectorAll('.section-content');
    const welcomeSection = document.querySelector('.welcome-section');

    // Función para mostrar una sección específica
    function showSection(sectionId) {
        // Ocultar todas las secciones
        sections.forEach(section => {
            section.classList.remove('active');
        });
        
        // Ocultar la sección de bienvenida
        if (welcomeSection) {
            welcomeSection.style.display = 'none';
        }
        
        // Mostrar la sección seleccionada
        const targetSection = document.getElementById(sectionId);
        if (targetSection) {
            targetSection.classList.add('active');
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
    }

    // Función para mostrar la página de inicio
    function showWelcome() {
        sections.forEach(section => {
            section.classList.remove('active');
        });
        
        if (welcomeSection) {
            welcomeSection.style.display = 'block';
        }
        
        navItems.forEach(item => {
            item.classList.remove('active');
        });
        
        // Marcar "Mi Información" como activo por defecto
        const defaultNavItem = document.querySelector('[data-section="mi-informacion"]');
        if (defaultNavItem) {
            defaultNavItem.classList.add('active');
        }
    }

    // Event listeners para navegación
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.getAttribute('data-section');
            
            if (sectionId) {
                showSection(sectionId);
                
                // Guardar la sección actual en localStorage
                localStorage.setItem('currentSection', sectionId);
            }
        });
    });

    // Restaurar la sección guardada o mostrar bienvenida por defecto
    const savedSection = localStorage.getItem('currentSection');
    if (savedSection) {
        showSection(savedSection);
    } else {
        showWelcome();
    }

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

    // Activar el primer tab de ajustes por defecto
    if (ajustesTabs.length > 0) {
        ajustesTabs[0].classList.add('active');
        if (ajustesSections.length > 0) {
            ajustesSections[0].classList.add('active');
        }
    }
});

// Función para manejar formularios
function handleFormSubmit(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Aquí puedes agregar la lógica para enviar los datos
            console.log('Formulario enviado:', formId);
            
            // Mostrar mensaje de éxito (temporal)
            alert('Configuración guardada exitosamente');
        });
    }
}

// Inicializar formularios
document.addEventListener('DOMContentLoaded', function() {
    handleFormSubmit('perfil-form');
    handleFormSubmit('seguridad-form');
    handleFormSubmit('notificaciones-form');
});

// Funciones para gestión de cursos
function showCreateCourseForm() {
    document.getElementById('create-course-form').style.display = 'block';
}

function hideCreateCourseForm() {
    document.getElementById('create-course-form').style.display = 'none';
}

function loadAllCourses() {
    alert('Cargando todos los cursos...');
}

function showCourseContent() {
    document.getElementById('course-content-modal').style.display = 'block';
}

function hideCourseContent() {
    document.getElementById('course-content-modal').style.display = 'none';
}

function showContentTab(tabName) {
    // Ocultar todos los tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.style.display = 'none';
    });
    
    // Remover clase activa de todos los botones
    document.querySelectorAll('.content-tab').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Mostrar tab seleccionado
    document.getElementById(tabName + '-content').style.display = 'block';
    
    // Activar botón correspondiente
    event.target.classList.add('active');
}

function showAddContentForm() {
    alert('Formulario para agregar contenido (video, PDF, texto, etc.)');
}

function showAddActivityForm() {
    alert('Formulario para crear actividad (quiz, tarea, etc.)');
}

// Funciones para alumnos
function showMyCourses() {
    document.getElementById('available-courses').style.display = 'none';
    document.getElementById('my-courses').style.display = 'block';
}

function enrollInCourse(courseId) {
    if (confirm('¿Estás seguro de que quieres inscribirte en este curso?')) {
        alert('Te has inscrito exitosamente en el curso!');
    }
}

function previewCourse(courseId) {
    alert('Vista previa del curso - Mostrando contenido disponible');
}

function continueCourse(courseId) {
    alert('Continuando con el curso...');
}

function viewGrades(courseId) {
    alert('Mostrando calificaciones del curso');
}