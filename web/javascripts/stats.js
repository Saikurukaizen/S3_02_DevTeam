
'use strict';

/**
 * Gestión de estadísticas del Kanban
 * Actualiza los contadores en tiempo real y mejora la experiencia de usuario
 * Comentarios explicativos en español para facilitar el mantenimiento
 */

document.addEventListener('DOMContentLoaded', function() {
    // Detecta si está en la página del Kanban
    const isKanbanPage = !!document.querySelector('.kanban-board');
    if (isKanbanPage) {
        updateStats();
        setupDragAndDropEvents();
    } else {
        // Para otras páginas, buscar estadísticas vía AJAX de forma segura
        fetch(buildUrl('task/index'))
            .then(response => response.text())
            .then(html => {
                // Parse del HTML para extraer información de las tareas
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const pending = doc.querySelectorAll('#pending-tasks .task-card').length;
                const progress = doc.querySelectorAll('#progress-tasks .task-card').length;
                const completed = doc.querySelectorAll('#completed-tasks .task-card').length;
                const total = pending + progress + completed;
                updateStatsDisplay(pending, progress, completed, total);
            })
            .catch(() => {
                // Si falla, muestra todo en cero
                updateStatsDisplay(0, 0, 0, 0);
            });
    }
    setupDragAndDropEvents(); // Inicializa los listeners de eventos
});

/**
 * Actualiza los contadores de tareas por columna en el Kanban
 */
function updateStats() {
    // Cuenta las tareas por columna
    const pendingTasks = document.querySelectorAll('#pending-tasks .task-card').length;
    const progressTasks = document.querySelectorAll('#progress-tasks .task-card').length;
    const completedTasks = document.querySelectorAll('#completed-tasks .task-card').length;
    const totalTasks = pendingTasks + progressTasks + completedTasks;
    updateStatsDisplay(pendingTasks, progressTasks, completedTasks, totalTasks);
}

/**
 * Actualiza los elementos visuales de las estadísticas
 * Incluye badges de las columnas y contadores de la barra lateral
 */
function updateStatsDisplay(pending, progress, completed, total) {
    updateElement('pending-badge', pending);
    updateElement('progress-badge', progress);
    updateElement('completed-badge', completed);
    updateElement('pending-count', pending);
    updateElement('progress-count', progress);
    updateElement('completed-count', completed);
    updateElement('total-count', total);
    animateCounterUpdate(); // Animación sutil para feedback visual
}

/**
 * Actualiza el valor de un elemento por ID y anima si cambió
 */
function updateElement(id, value) {
    const element = document.getElementById(id);
    if (element) {
        const oldValue = element.textContent;
        element.textContent = value;
        // Agrega animación si el valor cambió
        if (oldValue !== value.toString()) {
            element.classList.add('counter-updated');
            setTimeout(() => {
                element.classList.remove('counter-updated');
            }, 600);
        }
    }
}

/**
 * Anima la barra lateral para indicar actualización de estadísticas
 */
function animateCounterUpdate() {
    // Anima la barra lateral para indicar actualización
    const sidebar = document.querySelector('.stats-panel');
    if (sidebar) {
        sidebar.classList.add('stats-updated');
        setTimeout(() => {
            sidebar.classList.remove('stats-updated');
        }, 300);
    }
}

/**
 * Agrega listeners para eventos de drag & drop y eliminación de tareas
 */
function setupDragAndDropEvents() {
    document.addEventListener('taskMoved', function() {
        setTimeout(updateStats, 100); // Pequeño delay para asegurar que el DOM esté actualizado
    });
    document.addEventListener('taskDeleted', function() {
        setTimeout(updateStats, 100);
    });
}

/**
 * Función para notificar cambio de tarea (mover o eliminar)
 */
function notifyTaskChange(action) {
    const event = new CustomEvent(action === 'delete' ? 'taskDeleted' : 'taskMoved');
    document.dispatchEvent(event);
}

/**
 * Mejora el aspecto visual de las áreas de drop del Kanban y de la papelera
 */
function enhanceDropZones() {
    const columns = document.querySelectorAll('.kanban-column');
    columns.forEach(column => {
        column.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('drop-zone-active');
        });
        column.addEventListener('dragleave', function(e) {
            if (!this.contains(e.relatedTarget)) {
                this.classList.remove('drop-zone-active');
            }
        });
        column.addEventListener('drop', function() {
            this.classList.remove('drop-zone-active');
        });
    });
    // Feedback visual para la papelera
    const trashContainer = document.getElementById('Litter');
    if (trashContainer) {
        trashContainer.addEventListener('dragover', function() {
            this.classList.add('drag-over');
        });
        trashContainer.addEventListener('dragleave', function() {
            this.classList.remove('drag-over');
        });
        trashContainer.addEventListener('drop', function() {
            this.classList.remove('drag-over');
        });
    }
}

// Inicializa mejoras visuales de las áreas de drop al cargar la página
document.addEventListener('DOMContentLoaded', enhanceDropZones);
