'use strict';

// Configuración base de URLs para compatibilidad entre XAMPP y servidores PHP
// Esta función ayuda a construir URLs absolutas en JavaScript usando BASE_URL definida globalmente
function buildUrl(path) {
    // Verifica que BASE_URL esté definida correctamente
    if (typeof window.BASE_URL !== 'string' || window.BASE_URL.trim() === '') {
        throw new Error('BASE_URL no está definida correctamente');
    }
    // Elimina la barra inicial para evitar duplicidad
    path = String(path).replace(/^\//, '');
    // Retorna la URL absoluta
    return window.BASE_URL + '/' + path;
}
