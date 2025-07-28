
'use strict';

document.addEventListener('DOMContentLoaded', function() {
    const isKanbanPage = !!document.querySelector('.kanban-board');
    if (isKanbanPage) {
        updateStats();
        setupDragAndDropEvents();
    } else {
        fetch(buildUrl('task/index'))
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const pending = doc.querySelectorAll('#pending-tasks .task-card').length;
                const progress = doc.querySelectorAll('#progress-tasks .task-card').length;
                const completed = doc.querySelectorAll('#completed-tasks .task-card').length;
                const total = pending + progress + completed;
                updateStatsDisplay(pending, progress, completed, total);
            })
            .catch(() => {
                updateStatsDisplay(0, 0, 0, 0);
            });
    }
    setupDragAndDropEvents();
});

function updateStats() {
    const pendingTasks = document.querySelectorAll('#pending-tasks .task-card').length;
    const progressTasks = document.querySelectorAll('#progress-tasks .task-card').length;
    const completedTasks = document.querySelectorAll('#completed-tasks .task-card').length;
    const totalTasks = pendingTasks + progressTasks + completedTasks;
    updateStatsDisplay(pendingTasks, progressTasks, completedTasks, totalTasks);
}

function updateStatsDisplay(pending, progress, completed, total) {
    updateElement('pending-badge', pending);
    updateElement('progress-badge', progress);
    updateElement('completed-badge', completed);
    updateElement('pending-count', pending);
    updateElement('progress-count', progress);
    updateElement('completed-count', completed);
    updateElement('total-count', total);
    animateCounterUpdate();
}

function updateElement(id, value) {
    const element = document.getElementById(id);
    if (element) {
        const oldValue = element.textContent;
        element.textContent = value;
        if (oldValue !== value.toString()) {
            element.classList.add('counter-updated');
            setTimeout(() => {
                element.classList.remove('counter-updated');
            }, 600);
        }
    }
}

function animateCounterUpdate() {
    const sidebar = document.querySelector('.stats-panel');
    if (sidebar) {
        sidebar.classList.add('stats-updated');
        setTimeout(() => {
            sidebar.classList.remove('stats-updated');
        }, 300);
    }
}

function setupDragAndDropEvents() {
    document.addEventListener('taskMoved', function() {
        setTimeout(updateStats, 100);
    });
    document.addEventListener('taskDeleted', function() {
        setTimeout(updateStats, 100);
    });
}

function notifyTaskChange(action) {
    const event = new CustomEvent(action === 'delete' ? 'taskDeleted' : 'taskMoved');
    document.dispatchEvent(event);
}

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

document.addEventListener('DOMContentLoaded', enhanceDropZones);
