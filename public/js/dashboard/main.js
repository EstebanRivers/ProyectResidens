// Dashboard Main JavaScript
class DashboardManager {
    constructor() {
        this.currentSection = 'mi-informacion';
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

        // Ajustes tabs
        document.querySelectorAll('.ajustes-tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                const targetTab = tab.getAttribute('data-tab');
                this.showAjustesTab(targetTab);
            });
        });
    }

    showSection(sectionId) {
        // Hide welcome section
        const welcomeSection = document.querySelector('.welcome-section');
        if (welcomeSection) {
            welcomeSection.classList.remove('active');
            welcomeSection.style.display = 'none';
        }

        // Hide all sections
        document.querySelectorAll('.section-content').forEach(section => {
            section.classList.remove('active');
            section.style.display = 'none';
        });

        // Show target section
        const targetSection = document.getElementById(sectionId);
        if (targetSection) {
            targetSection.classList.add('active');
            targetSection.style.display = 'block';
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
    }

    showAjustesTab(tabId) {
        // Hide all ajustes sections
        document.querySelectorAll('.ajustes-section').forEach(section => {
            section.classList.remove('active');
        });

        // Show target tab
        const targetTab = document.getElementById(tabId);
        if (targetTab) {
            targetTab.classList.add('active');
        }

        // Update tab navigation
        document.querySelectorAll('.ajustes-tab').forEach(tab => {
            tab.classList.remove('active');
        });

        const activeTab = document.querySelector(`[data-tab="${tabId}"]`);
        if (activeTab) {
            activeTab.classList.add('active');
        }
    }

    loadSavedSection() {
        const savedSection = localStorage.getItem('currentSection');
        if (savedSection && document.getElementById(savedSection)) {
            this.showSection(savedSection);
        } else {
            // Show welcome by default
            const welcomeSection = document.querySelector('.welcome-section');
            if (welcomeSection) {
                welcomeSection.classList.add('active');
                welcomeSection.style.display = 'flex';
            }
        }
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.dashboard = new DashboardManager();
});

// Form handlers
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submissions
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