'use strict';

function buildUrl(path) {
    if (typeof window.BASE_URL !== 'string' || window.BASE_URL.trim() === '') {
        throw new Error('BASE_URL no está definida correctamente');
    }
    path = String(path).replace(/^\//, '');
    return window.BASE_URL + '/' + path;
}
