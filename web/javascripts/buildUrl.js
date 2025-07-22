'use strict';

//Configuración de URLs para JavaScript
// Configuración de URLs base para compatibilidad entre XAMPP y PHP Server
    
    
    // Función helper para construir URLs en JavaScript

    function buildUrl(path) {
        if(typeof window.BASE_URL !== 'string'){
            throw new Error('BASE_URL is not defined');
        }
        path = path.replace(/^\//, '');
        return window.BASE_URL + '/' + path;
    }
