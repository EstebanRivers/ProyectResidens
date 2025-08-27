// Dashboard Main JavaScript
class DashboardManager {
    constructor() {
        this.currentSection = 'welcome';
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadSavedSection();
    }

    bindEvents() {
        // Navigation items
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const section = item.getAttribute('data-section');
                if (section) {
                    this.showSection(section);
                }
            });
        });

        // Settings tabs
        document.querySelectorAll('.ajustes-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                this.showSettingsTab(tab.getAttribute('data-tab'));
            });
        });
    }

    showSection(sectionId) {
        // Hide all sections
        document.querySelectorAll('.section-content, .welcome-section').forEach(section => {
            section.classList.remove('active');
        });

        // Show target section
        const targetSection = document.getElementById(sectionId);
        if (targetSection) {
            targetSection.classList.add('active');
        }

        // Update navigation
        document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('active');
        });

        const activeNavItem = document.querySelector(`[data-section="${sectionId}"]`);
        if (activeNavItem) {
            activeNavItem.classList.add('active');
        }

        // Save current section
        this.currentSection = sectionId;
        localStorage.setItem('currentSection', sectionId);

        // Update page title
        this.updatePageTitle(sectionId);
    }

    showWelcome() {
        document.querySelectorAll('.section-content').forEach(section => {
            section.classList.remove('active');
        });

        const welcomeSection = document.querySelector('.welcome-section');
        if (welcomeSection) {
            welcomeSection.classList.add('active');
        }

        document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('active');
        });

        this.currentSection = 'welcome';
        localStorage.removeItem('currentSection');
    }

    showSettingsTab(tabId) {
        // Remove active class from all tabs and sections
        document.querySelectorAll('.ajustes-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelectorAll('.ajustes-section').forEach(section => {
            section.classList.remove('active');
        });

        // Activate selected tab
        const activeTab = document.querySelector(`[data-tab="${tabId}"]`);
        const activeSection = document.getElementById(tabId);

        if (activeTab) activeTab.classList.add('active');
        if (activeSection) activeSection.classList.add('active');
    }

    loadSavedSection() {
        const savedSection = localStorage.getItem('currentSection');
        if (savedSection && document.getElementById(savedSection)) {
            this.showSection(savedSection);
        } else {
            this.showWelcome();
        }
    }

    updatePageTitle(sectionId) {
        const titles = {
            'mi-informacion': 'Mi Información',
            'cursos': 'Cursos',
            'facturacion': 'Facturación',
            'control-administrativo': 'Control Administrativo',
            'ajustes': 'Ajustes'
        };

        const titleElement = document.querySelector('.page-title');
        const subtitleElement = document.querySelector('.page-subtitle');

        if (titleElement && titles[sectionId]) {
            titleElement.textContent = titles[sectionId];
        }

        if (subtitleElement) {
            const subtitles = {
                'mi-informacion': 'Información personal y datos del usuario',
                'cursos': 'Gestión de cursos y materias',
                'facturacion': 'Gestión de pagos y facturación',
                'control-administrativo': 'Herramientas administrativas y reportes',
                'ajustes': 'Configuración del sistema y preferencias'
            };
            subtitleElement.textContent = subtitles[sectionId] || '';
        }
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.dashboard = new DashboardManager();
});

// Utility functions for course management
window.courseUtils = {
    showCreateCourseForm() {
        const form = document.getElementById('create-course-form');
        if (form) {
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }
    },

    hideCreateCourseForm() {
        const form = document.getElementById('create-course-form');
        if (form) {
            form.style.display = 'none';
        }
    },

    enrollInCourse(courseId) {
        if (confirm('¿Estás seguro de que quieres inscribirte en este curso?')) {
            // Here you would make an AJAX call to enroll
            alert('Te has inscrito exitosamente en el curso!');
        }
    },

    previewCourse(courseId) {
        alert('Vista previa del curso - Mostrando contenido disponible');
    },

    continueCourse(courseId) {
        alert('Continuando con el curso...');
    },

    viewGrades(courseId) {
        alert('Mostrando calificaciones del curso');
    },

    showCourseContent() {
        const modal = document.getElementById('course-content-modal');
        if (modal) {
            modal.style.display = 'block';
        }
    },

    hideCourseContent() {
        const modal = document.getElementById('course-content-modal');
        if (modal) {
            modal.style.display = 'none';
        }
    },

    showMyCourses() {
        document.getElementById('available-courses').style.display = 'none';
        document.getElementById('my-courses').style.display = 'block';
    },

    loadAllCourses() {
        alert('Cargando todos los cursos...');
    }
};

// Make functions globally available
Object.assign(window, window.courseUtils);