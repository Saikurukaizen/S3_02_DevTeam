'use strict';

//Configuración de URLs para JavaScript
// Configuración de URLs base para compatibilidad entre XAMPP y PHP Server
    
    
    // Función helper para construir URLs en JavaScript

    window.BASE_URL = '<?= BASE_URL ?>';

    function buildUrl(path) {
        path = path.replace(/^\//, '');
        return window.BASE_URL + '/' + path;
    }
