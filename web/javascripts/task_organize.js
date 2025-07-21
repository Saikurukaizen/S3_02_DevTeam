'use strict';

// Script para mover las tareas a su columna por estado
// Busca todos los elementos con clase task y los coloca en el div correspondiente
// Los ids de columna deben coincidir con los valores de data-estado
window.addEventListener('DOMContentLoaded', () => {
    console.log('Organizando tareas en las columnas...');
    
    document.querySelectorAll('.task').forEach(card => {
        const estado = card.getAttribute('data-estado');
        console.log('Tarea con estado:', estado);
        
        const columna = document.getElementById(estado);
        if (columna) {
            console.log('Moviendo a columna:', estado);
            columna.appendChild(card);
        } else {
            console.warn('Columna no encontrada para estado:', estado);
        }
    });
    
    console.log('Organización completada!');
});