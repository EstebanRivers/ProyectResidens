// Dashboard Main JavaScript
class DashboardManager {
    constructor() {
        this.currentSection = 'mi-informacion';
        this.currentEnrollmentCourse = null;
        this.showingMyCourses = false;
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

    showWelcome() {
        document.querySelectorAll('.section-content').forEach(section => {
            section.classList.remove('active');
            section.style.display = 'none';
        });

        const welcomeSection = document.querySelector('.welcome-section');
        if (welcomeSection) {
            welcomeSection.classList.add('active');
            welcomeSection.style.display = 'flex';
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
            tab.style.color = '#666';
            tab.style.borderBottomColor = 'transparent';
        });
        document.querySelectorAll('.ajustes-section').forEach(section => {
            section.classList.remove('active');
            section.style.display = 'none';
        });

        // Activate selected tab
        const activeTab = document.querySelector(`[data-tab="${tabId}"]`);
        const activeSection = document.getElementById(tabId);

        if (activeTab) {
            activeTab.classList.add('active');
            activeTab.style.color = '#ffa726';
            activeTab.style.borderBottomColor = '#ffa726';
        }
        if (activeSection) {
            activeSection.classList.add('active');
            activeSection.style.display = 'block';
        }
    }

    loadSavedSection() {
        const savedSection = localStorage.getItem('currentSection');
        if (savedSection && document.getElementById(savedSection)) {
            this.showSection(savedSection);
        } else {
            // Show welcome by default
            this.showWelcome();
        }
    }

    toggleCourseView() {
        const availableCourses = document.getElementById('available-courses');
        const myCourses = document.getElementById('my-courses');
        const toggleText = document.getElementById('toggleText');
        
        if (this.showingMyCourses) {
            // Show available courses
            availableCourses.style.display = 'block';
            myCourses.style.display = 'none';
            toggleText.textContent = 'Ver Mis Cursos';
            this.showingMyCourses = false;
        } else {
            // Show my courses
            availableCourses.style.display = 'none';
            myCourses.style.display = 'block';
            toggleText.textContent = 'Ver Cursos Disponibles';
            this.showingMyCourses = true;
        }
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.dashboard = new DashboardManager();
});

// Enrollment Modal Functions
function showEnrollModal(courseId, courseName) {
    window.dashboard.currentEnrollmentCourse = courseId;
    const modal = document.getElementById('enrollModal');
    const modalText = document.getElementById('enrollModalText');
    modalText.textContent = `¿Estás seguro de que quieres inscribirte en el curso "${courseName}"?`;
    modal.style.display = 'flex';
}

function closeEnrollModal() {
    const modal = document.getElementById('enrollModal');
    modal.style.display = 'none';
    window.dashboard.currentEnrollmentCourse = null;
}

function confirmEnrollment() {
    if (!window.dashboard.currentEnrollmentCourse) return;
    
    const courseId = window.dashboard.currentEnrollmentCourse;
    
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/cursos/${courseId}/inscribir`;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    
    form.appendChild(csrfInput);
    document.body.appendChild(form);
    form.submit();
    
    closeEnrollModal();
}

// Toggle course view function
function toggleCourseView() {
    if (window.dashboard) {
        window.dashboard.toggleCourseView();
    }
}

// Utility functions for course management
function viewGrades(courseId) {
    alert('Mostrando calificaciones del curso ' + courseId);
}

// Make functions globally available
window.showEnrollModal = showEnrollModal;
window.closeEnrollModal = closeEnrollModal;
window.confirmEnrollment = confirmEnrollment;
window.toggleCourseView = toggleCourseView;
window.viewGrades = viewGrades;