// Dashboard Main JavaScript
class DashboardManager {
    constructor() {
        this.currentSection = 'mi-informacion';
        this.currentEnrollmentCourse = null;
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
            section.style.display = 'none';
        });

        // Show target section
        const targetSection = document.getElementById(sectionId);
        if (targetSection) {
            targetSection.classList.add('active');
            targetSection.style.display = 'block';
        } else {
            // If section doesn't exist, show welcome
            const welcomeSection = document.querySelector('.welcome-section');
            if (welcomeSection) {
                welcomeSection.classList.add('active');
                welcomeSection.style.display = 'flex';
            }
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
            this.showSection('mi-informacion');
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
    const modal = document.getElementById('confirmModal');
    const modalBody = modal.querySelector('.modal-body p');
    modalBody.textContent = `¿Estás seguro de que quieres inscribirte en el curso "${courseName}"?`;
    modal.style.display = 'flex';
}

function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
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
    
    closeConfirmModal();
}

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

    previewCourse(courseId) {
        window.location.href = `/cursos/${courseId}`;
    },

    continueCourse(courseId) {
        window.location.href = `/cursos/${courseId}`;
    },

    viewGrades(courseId) {
        alert('Mostrando calificaciones del curso');
    },

    showMyCourses() {
        const availableCourses = document.getElementById('available-courses');
        const myCourses = document.getElementById('my-courses');
        
        if (availableCourses && myCourses) {
            if (myCourses.style.display === 'none') {
                availableCourses.style.display = 'none';
                myCourses.style.display = 'block';
            } else {
                availableCourses.style.display = 'block';
                myCourses.style.display = 'none';
            }
        }
    }
};

// Make functions globally available
Object.assign(window, window.courseUtils);