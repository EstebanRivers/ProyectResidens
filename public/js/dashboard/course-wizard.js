// Course Creation Wizard JavaScript
class CourseWizard {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 4;
        this.courseData = {};
        this.init();
    }

    init() {
        this.bindEvents();
        this.updateNavigation();
    }

    bindEvents() {
        // Form validation on input change
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('change', () => this.validateCurrentStep());
        });
    }

    nextStep() {
        if (this.validateCurrentStep()) {
            if (this.currentStep < this.totalSteps) {
                this.currentStep++;
                this.showStep(this.currentStep);
                this.updateNavigation();
                
                // Special handling for step 3
                if (this.currentStep === 3) {
                    this.generateContentAndActivities();
                }
                
                // Special handling for step 4
                if (this.currentStep === 4) {
                    this.generateReview();
                }
            }
        }
    }

    previousStep() {
        if (this.currentStep > 1) {
            this.currentStep--;
            this.showStep(this.currentStep);
            this.updateNavigation();
        }
    }

    showStep(stepNumber) {
        // Hide all steps
        document.querySelectorAll('.wizard-step').forEach(step => {
            step.classList.remove('active');
        });
        
        // Show current step
        const currentStepElement = document.querySelector(`[data-step="${stepNumber}"].wizard-step`);
        if (currentStepElement) {
            currentStepElement.classList.add('active');
        }
        
        // Update step indicators
        document.querySelectorAll('.step').forEach((step, index) => {
            step.classList.remove('active', 'completed');
            if (index + 1 === stepNumber) {
                step.classList.add('active');
            } else if (index + 1 < stepNumber) {
                step.classList.add('completed');
            }
        });
    }

    updateNavigation() {
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const submitBtn = document.getElementById('submit-btn');

        // Show/hide previous button
        prevBtn.style.display = this.currentStep > 1 ? 'block' : 'none';
        
        // Show/hide next/submit buttons
        if (this.currentStep === this.totalSteps) {
            nextBtn.style.display = 'none';
            submitBtn.style.display = 'block';
        } else {
            nextBtn.style.display = 'block';
            submitBtn.style.display = 'none';
        }
    }

    validateCurrentStep() {
        const currentStepElement = document.querySelector(`[data-step="${this.currentStep}"].wizard-step`);
        const requiredInputs = currentStepElement.querySelectorAll('[required]');
        
        let isValid = true;
        requiredInputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.style.borderColor = '#dc3545';
            } else {
                input.style.borderColor = '#e9ecef';
            }
        });
        
        return isValid;
    }

    generateContentAndActivities() {
        const container = document.getElementById('contenido-actividades-container');
        const lecciones = document.querySelectorAll('.leccion-item');
        
        container.innerHTML = '';
        
        lecciones.forEach((leccion, index) => {
            const titulo = leccion.querySelector('.leccion-title-input').value;
            if (titulo) {
                const leccionSection = this.createContentActivitySection(index + 1, titulo);
                container.appendChild(leccionSection);
            }
        });
    }

    createContentActivitySection(numero, titulo) {
        const section = document.createElement('div');
        section.className = 'contenido-actividad-section';
        section.innerHTML = `
            <div class="section-header">
                <div class="section-icon">${numero}</div>
                <div class="section-title">Lección ${numero}: ${titulo}</div>
            </div>
            
            <div class="content-configuration">
                <h4 style="color: #2c3e50; margin-bottom: 1rem;">📚 Tipo de Contenido</h4>
                <div class="content-type-selector">
                    <div class="content-type-option" data-type="video" onclick="selectContentType(this, ${numero}, 'video')">
                        <div class="content-type-icon">🎥</div>
                        <div class="content-type-label">Video</div>
                    </div>
                    <div class="content-type-option" data-type="texto" onclick="selectContentType(this, ${numero}, 'texto')">
                        <div class="content-type-icon">📄</div>
                        <div class="content-type-label">Texto</div>
                    </div>
                    <div class="content-type-option" data-type="pdf" onclick="selectContentType(this, ${numero}, 'pdf')">
                        <div class="content-type-icon">📋</div>
                        <div class="content-type-label">PDF</div>
                    </div>
                </div>
                
                <div id="content-details-${numero}" class="content-details" style="display: none; margin-top: 1rem;">
                    <!-- Content details will be populated based on selection -->
                </div>
                
                <h4 style="color: #2c3e50; margin: 2rem 0 1rem 0;">📝 Tipo de Actividad</h4>
                <div class="activity-type-selector">
                    <div class="activity-type-option" data-type="opcion_multiple" onclick="selectActivityType(this, ${numero}, 'opcion_multiple')">
                        <div class="content-type-icon">☑️</div>
                        <div class="content-type-label">Opción Múltiple</div>
                    </div>
                    <div class="activity-type-option" data-type="verdadero_falso" onclick="selectActivityType(this, ${numero}, 'verdadero_falso')">
                        <div class="content-type-icon">✅</div>
                        <div class="content-type-label">Verdadero/Falso</div>
                    </div>
                    <div class="activity-type-option" data-type="respuesta_corta" onclick="selectActivityType(this, ${numero}, 'respuesta_corta')">
                        <div class="content-type-icon">✏️</div>
                        <div class="content-type-label">Respuesta Corta</div>
                    </div>
                    <div class="activity-type-option" data-type="ensayo" onclick="selectActivityType(this, ${numero}, 'ensayo')">
                        <div class="content-type-icon">📝</div>
                        <div class="content-type-label">Ensayo</div>
                    </div>
                </div>
                
                <div id="activity-details-${numero}" class="activity-details" style="display: none; margin-top: 1rem;">
                    <!-- Activity details will be populated based on selection -->
                </div>
            </div>
            
            <input type="hidden" name="lecciones[${numero}][titulo]" value="${titulo}">
            <input type="hidden" name="lecciones[${numero}][orden]" value="${numero}">
        `;
        
        return section;
    }

    generateReview() {
        const container = document.getElementById('review-container');
        const formData = new FormData(document.getElementById('course-wizard-form'));
        
        let reviewHTML = `
            <div class="review-section">
                <div class="review-title">📋 Información del Curso</div>
                <div class="review-item">
                    <span class="review-label">Nombre:</span>
                    <span class="review-value">${formData.get('nombre') || 'No especificado'}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Código:</span>
                    <span class="review-value">${formData.get('codigo') || 'No especificado'}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Créditos:</span>
                    <span class="review-value">${formData.get('creditos') || 'No especificado'}</span>
                </div>
                <div class="review-item">
                    <span class="review-label">Cupo Máximo:</span>
                    <span class="review-value">${formData.get('cupo_maximo') || 'No especificado'}</span>
                </div>
            </div>
            
            <div class="review-section">
                <div class="review-title">📚 Estructura del Curso</div>
        `;
        
        const lecciones = document.querySelectorAll('.leccion-item');
        lecciones.forEach((leccion, index) => {
            const titulo = leccion.querySelector('.leccion-title-input').value;
            if (titulo) {
                reviewHTML += `
                    <div class="review-item">
                        <span class="review-label">Lección ${index + 1}:</span>
                        <span class="review-value">${titulo}</span>
                    </div>
                `;
            }
        });
        
        reviewHTML += `</div>`;
        container.innerHTML = reviewHTML;
    }
}

// Global functions for content and activity selection
function selectContentType(element, leccionNum, type) {
    // Remove selected class from siblings
    element.parentElement.querySelectorAll('.content-type-option').forEach(option => {
        option.classList.remove('selected');
    });
    
    // Add selected class to clicked element
    element.classList.add('selected');
    
    // Show content details
    const detailsContainer = document.getElementById(`content-details-${leccionNum}`);
    detailsContainer.style.display = 'block';
    
    let detailsHTML = '';
    switch(type) {
        case 'video':
            detailsHTML = `
                <div class="form-group">
                    <label class="form-label">URL del Video o Subir Archivo</label>
                    <input type="url" name="lecciones[${leccionNum}][contenido][video_url]" class="form-input" placeholder="https://youtube.com/watch?v=...">
                    <div style="margin: 1rem 0; text-align: center; color: #6c757d;">O</div>
                    <input type="file" name="lecciones[${leccionNum}][contenido][video_file]" class="form-input" accept="video/*">
                </div>
            `;
            break;
        case 'texto':
            detailsHTML = `
                <div class="form-group">
                    <label class="form-label">Contenido del Texto</label>
                    <textarea name="lecciones[${leccionNum}][contenido][texto]" class="form-textarea" rows="8" placeholder="Escribe el contenido de la lección aquí..."></textarea>
                </div>
            `;
            break;
        case 'pdf':
            detailsHTML = `
                <div class="form-group">
                    <label class="form-label">Subir Archivo PDF</label>
                    <input type="file" name="lecciones[${leccionNum}][contenido][pdf_file]" class="form-input" accept=".pdf" required>
                </div>
            `;
            break;
    }
    
    detailsContainer.innerHTML = detailsHTML;
    
    // Store content type
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = `lecciones[${leccionNum}][contenido][tipo]`;
    hiddenInput.value = type;
    detailsContainer.appendChild(hiddenInput);
}

function selectActivityType(element, leccionNum, type) {
    // Remove selected class from siblings
    element.parentElement.querySelectorAll('.activity-type-option').forEach(option => {
        option.classList.remove('selected');
    });
    
    // Add selected class to clicked element
    element.classList.add('selected');
    
    // Show activity details
    const detailsContainer = document.getElementById(`activity-details-${leccionNum}`);
    detailsContainer.style.display = 'block';
    
    let detailsHTML = `
        <div class="form-group">
            <label class="form-label">Pregunta de la Actividad</label>
            <textarea name="lecciones[${leccionNum}][actividad][pregunta]" class="form-textarea" rows="3" placeholder="Escribe la pregunta aquí..." required></textarea>
        </div>
    `;
    
    switch(type) {
        case 'opcion_multiple':
            detailsHTML += `
                <div class="form-group">
                    <label class="form-label">Opciones de Respuesta</label>
                    <div class="opciones-container">
                        ${[1,2,3,4].map(i => `
                            <div class="opcion-item" style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                                <input type="text" name="lecciones[${leccionNum}][actividad][opciones][${i-1}]" class="form-input" placeholder="Opción ${i}" style="margin-right: 0.5rem;">
                                <label style="display: flex; align-items: center; margin-left: 0.5rem;">
                                    <input type="radio" name="lecciones[${leccionNum}][actividad][respuesta_correcta]" value="${i-1}" style="margin-right: 0.25rem;">
                                    Correcta
                                </label>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
            break;
        case 'verdadero_falso':
            detailsHTML += `
                <div class="form-group">
                    <label class="form-label">Respuesta Correcta</label>
                    <div class="radio-group" style="display: flex; gap: 1rem;">
                        <label style="display: flex; align-items: center;">
                            <input type="radio" name="lecciones[${leccionNum}][actividad][respuesta_correcta]" value="verdadero" style="margin-right: 0.5rem;">
                            Verdadero
                        </label>
                        <label style="display: flex; align-items: center;">
                            <input type="radio" name="lecciones[${leccionNum}][actividad][respuesta_correcta]" value="falso" style="margin-right: 0.5rem;">
                            Falso
                        </label>
                    </div>
                </div>
            `;
            break;
        case 'respuesta_corta':
            detailsHTML += `
                <div class="form-group">
                    <label class="form-label">Respuestas Correctas (una por línea)</label>
                    <textarea name="lecciones[${leccionNum}][actividad][respuestas_correctas]" class="form-textarea" rows="4" placeholder="Escribe las posibles respuestas correctas, una por línea..."></textarea>
                </div>
            `;
            break;
        case 'ensayo':
            detailsHTML += `
                <div class="form-group">
                    <label class="form-label">Criterios de Evaluación</label>
                    <textarea name="lecciones[${leccionNum}][actividad][criterios]" class="form-textarea" rows="4" placeholder="Describe los criterios que se usarán para evaluar el ensayo..."></textarea>
                </div>
            `;
            break;
    }
    
    detailsHTML += `
        <div class="form-group">
            <label class="form-label">Puntos de la Actividad</label>
            <input type="number" name="lecciones[${leccionNum}][actividad][puntos]" class="form-input" min="1" max="100" value="10">
        </div>
        <div class="form-group">
            <label class="form-label">Explicación (Opcional)</label>
            <textarea name="lecciones[${leccionNum}][actividad][explicacion]" class="form-textarea" rows="3" placeholder="Explicación de la respuesta correcta..."></textarea>
        </div>
    `;
    
    detailsContainer.innerHTML = detailsHTML;
    
    // Store activity type
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = `lecciones[${leccionNum}][actividad][tipo]`;
    hiddenInput.value = type;
    detailsContainer.appendChild(hiddenInput);
}

function generateLessons() {
    const numLecciones = document.getElementById('num-lecciones').value;
    const container = document.getElementById('lecciones-container');
    
    if (!numLecciones || numLecciones < 1) {
        alert('Por favor, especifica un número válido de lecciones');
        return;
    }
    
    container.innerHTML = '';
    
    for (let i = 1; i <= numLecciones; i++) {
        const leccionItem = document.createElement('div');
        leccionItem.className = 'leccion-item';
        leccionItem.innerHTML = `
            <div class="leccion-header">
                <div class="leccion-number">${i}</div>
                <input type="text" class="leccion-title-input" name="lecciones[${i}][titulo]" placeholder="Título de la Lección ${i}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Descripción de la Lección</label>
                <textarea name="lecciones[${i}][descripcion]" class="form-textarea" rows="2" placeholder="Breve descripción de lo que se aprenderá en esta lección..."></textarea>
            </div>
        `;
        container.appendChild(leccionItem);
    }
}

// Initialize wizard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.courseWizard = new CourseWizard();
});

// Global functions
window.generateLessons = generateLessons;
window.selectContentType = selectContentType;
window.selectActivityType = selectActivityType;
window.nextStep = () => window.courseWizard.nextStep();
window.previousStep = () => window.courseWizard.previousStep();