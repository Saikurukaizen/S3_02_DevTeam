'use strict';

window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.task').forEach(card => {
        const estado = card.getAttribute('data-estado');
        const columna = document.getElementById(estado);
        if (columna) {
            columna.appendChild(card);
        } else {
            console.warn('Columna no encontrada para estado:', estado);
        }
    });
});
