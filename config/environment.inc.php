<?php
/**
 * Configuracion de entorno para compatibilidad entre XAMPP y servidor PHP built-in
 * Detecta automaticamente el entorno y configura las URLs base apropiadas
 */

// Detectar si estamos usando XAMPP o servidor PHP built-in
function detectEnvironment() {
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $serverPort = $_SERVER['SERVER_PORT'] ?? '';
    $httpHost = $_SERVER['HTTP_HOST'] ?? '';
    
    // Si el puerto es 8000, probablemente es php -S localhost:8000
    if ($serverPort === '8000' || strpos($httpHost, ':8000') !== false) {
        return 'php_server';
    }
    
    // Si la URL contiene /fullstackphp-sprint3/, es XAMPP
    if (strpos($scriptName, '/fullstackphp-sprint3/') !== false) {
        return 'xampp';
    }
    
    // Por defecto, asumir XAMPP
    return 'xampp';
}

// Configurar la URL base segun el entorno
function getBaseUrl() {
    $environment = detectEnvironment();
    
    switch ($environment) {
        case 'php_server':
            // Para php -S localhost:8000
            return '';
        case 'xampp':
        default:
            // Para XAMPP con la estructura actual
            return '/fullstackphp-sprint3/S302/S3_02_DevTeam/web';
    }
}

// Constantes globales para usar en toda la aplicacion
define('ENVIRONMENT', detectEnvironment());
define('BASE_URL', getBaseUrl());

// Funcion helper para construir URLs
function url($path = '') {
    $path = ltrim($path, '/');
    return BASE_URL . '/' . $path;
}

// Funcion helper para assets (CSS, JS, imagenes)
function asset($path = '') {
    $path = ltrim($path, '/');
    return BASE_URL . '/' . $path;
}
?>
