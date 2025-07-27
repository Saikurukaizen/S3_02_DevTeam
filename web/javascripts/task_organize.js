'use strict';

// Organiza las tareas en las columnas correctas del Kanban al cargar la página
window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.task').forEach(card => {
        // Obtiene el estado de la tarea
        const estado = card.getAttribute('data-estado');
        // Busca la columna correspondiente al estado
        const columna = document.getElementById(estado);
        if (columna) {
            // Mueve la tarjeta a la columna correcta
            columna.appendChild(card);
        } else {
            // Aviso si no se encuentra columna para el estado
            console.warn('Columna no encontrada para estado:', estado);
        }
    });
});
