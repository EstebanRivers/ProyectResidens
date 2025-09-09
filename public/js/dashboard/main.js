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

        // Settings tabs
        document.querySelectorAll('.ajustes-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                this.showSettingsTab(tab.getAttribute('data-tab'));
            });
        });
        
        // Course content tabs
        document.querySelectorAll('.content-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                this.showContentTab(tab.getAttribute('data-tab'));
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
    
    showContentTab(tabId) {
        // Remove active class from all tabs and sections
        document.querySelectorAll('.content-tab').forEach(tab => {
            tab.style.color = '#6c757d';
            tab.style.borderBottomColor = 'transparent';
        });
        document.querySelectorAll('.content-section').forEach(section => {
            section.style.display = 'none';
        });

        // Activate selected tab
        const activeTab = document.querySelector(`[data-tab="${tabId}"]`);
        const activeSection = document.getElementById(tabId);

        if (activeTab) {
            activeTab.style.color = '#ffa726';
            activeTab.style.borderBottomColor = '#ffa726';
        }
        if (activeSection) {
            activeSection.style.display = 'block';
        }
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
}
)