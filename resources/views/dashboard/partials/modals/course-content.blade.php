<!-- Modal para gestión de contenido del curso -->
<div id="course-content-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Gestión de Contenido del Curso</h2>
            <span class="modal-close" onclick="hideCourseContent()">&times;</span>
        </div>
        
        <div class="modal-body">
            <div class="content-tabs">
                <button class="content-tab active" data-tab="lecciones">📚 Lecciones</button>
                <button class="content-tab" data-tab="actividades">📝 Actividades</button>
                <button class="content-tab" data-tab="estudiantes">👥 Estudiantes</button>
            </div>
            
            <div id="lecciones" class="content-section active">
                <div class="section-header">
                    <h3>Lecciones del Curso</h3>
                    <button class="btn-primary btn-small">➕ Agregar Lección</button>
                </div>
                
                <div class="content-list">
                    <div class="content-item">
                        <div class="content-icon">🎥</div>
                        <div class="content-info">
                            <div class="content-title">Introducción a las Matemáticas</div>
                            <div class="content-description">Video explicativo de 15 minutos</div>
                        </div>
                        <div class="content-status">
                            <span class="status-badge status-completed">Publicado</span>
                        </div>
                    </div>
                    
                    <div class="content-item">
                        <div class="content-icon">📄</div>
                        <div class="content-info">
                            <div class="content-title">Álgebra Básica</div>
                            <div class="content-description">Documento PDF con ejercicios</div>
                        </div>
                        <div class="content-status">
                            <span class="status-badge status-pending">Borrador</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="actividades" class="content-section">
                <div class="section-header">
                    <h3>Actividades y Evaluaciones</h3>
                    <button class="btn-primary btn-small">➕ Crear Actividad</button>
                </div>
                
                <div class="content-list">
                    <div class="content-item">
                        <div class="content-icon">☑️</div>
                        <div class="content-info">
                            <div class="content-title">Quiz: Operaciones Básicas</div>
                            <div class="content-description">10 preguntas de opción múltiple</div>
                        </div>
                        <div class="content-status">
                            <span class="status-badge status-completed">Activo</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div id="estudiantes" class="content-section">
                <div class="section-header">
                    <h3>Estudiantes Inscritos</h3>
                    <span class="student-count">25 estudiantes</span>
                </div>
                
                <div class="students-list">
                    <div class="student-item">
                        <div class="student-avatar">M</div>
                        <div class="student-info">
                            <div class="student-name">María González</div>
                            <div class="student-progress">Progreso: 75%</div>
                        </div>
                        <div class="student-grade">8.5</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 800px;
    max-height: 80vh;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e9ecef;
}

.modal-close {
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
}

.modal-body {
    padding: 1.5rem;
    overflow-y: auto;
    max-height: 60vh;
}

.content-section {
    display: none;
}

.content-section.active {
    display: block;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.students-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.student-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.student-avatar {
    width: 40px;
    height: 40px;
    background: #1976d2;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    margin-right: 1rem;
}

.student-info {
    flex: 1;
}

.student-name {
    font-weight: 600;
    color: #2c3e50;
}

.student-progress {
    font-size: 0.9rem;
    color: #6c757d;
}

.student-grade {
    padding: 0.5rem 1rem;
    background: #d4edda;
    color: #155724;
    border-radius: 20px;
    font-weight: bold;
}
</style>