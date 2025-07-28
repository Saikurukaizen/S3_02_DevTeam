<?php

error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);
date_default_timezone_set('CET');

include(realpath(dirname(__FILE__) . '/../config/environment.inc.php'));

define('WEB_ROOT', substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], '/index.php')));

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
define('CMS_PATH', ROOT_PATH . '/lib/base/');

session_start();

include(ROOT_PATH . '/config/routes.php');

function autoloader($className) {
	if (strlen($className) > 10 && substr($className, -10) == 'Controller') {
		if (file_exists(ROOT_PATH . '/app/controllers/' . $className . '.php')) {
			require_once ROOT_PATH . '/app/controllers/' . $className . '.php';
		}
	}
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

spl_autoload_register('autoloader');

$router = new Router();
$router->execute($routes);
