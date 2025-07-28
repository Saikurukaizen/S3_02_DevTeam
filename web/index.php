<?php

// Configuración de errores y entorno
error_reporting(E_ALL|E_STRICT); // Mostrar todos los errores y advertencias
ini_set('display_errors', 1); // Mostrar errores en pantalla
date_default_timezone_set('CET'); // Zona horaria estándar

// Configuración de entorno para compatibilidad XAMPP/PHP Server
include(realpath(dirname(__FILE__) . '/../config/environment.inc.php'));

// Define la raíz web para rutas amigables
define('WEB_ROOT', substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], '/index.php')));

// Define rutas absolutas del sistema
define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
define('CMS_PATH', ROOT_PATH . '/lib/base/');

// Inicia la sesión del usuario
session_start();

// Incluye las rutas del sistema (define tus propias rutas en este archivo)
include(ROOT_PATH . '/config/routes.php');

/**
 * Autocargador estándar del framework
 * Carga controladores y modelos automáticamente
 * @param string $className Nombre de la clase a cargar
 */
function autoloader($className) {
	// Autocarga de controladores
	if (strlen($className) > 10 && substr($className, -10) == 'Controller') {
		if (file_exists(ROOT_PATH . '/app/controllers/' . $className . '.php')) {
			require_once ROOT_PATH . '/app/controllers/' . $className . '.php';
		}
	}
	// Autocarga de clases base y modelos
	else {
		if (file_exists(CMS_PATH . $className . '.php')) {
			require_once CMS_PATH . $className . '.php';
		}
		else if (file_exists(ROOT_PATH . '/lib/' . $className . '.php')) {
			require_once ROOT_PATH . '/lib/' . $className . '.php';
		}
		else {
			require_once ROOT_PATH . '/app/models/' . $className . '.php';
		}
	}
}

// Activa el autocargador
spl_autoload_register('autoloader');

// Inicializa el enrutador principal y ejecuta la ruta solicitada
$router = new Router();
$router->execute($routes);
