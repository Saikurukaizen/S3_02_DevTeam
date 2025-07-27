<?php

/**
 * Router del Sistema
 * 
 * Maneja el enrutamiento de URLs a controladores y acciones.
 * Soporta rutas simples y parametrizadas.
 */
class Router
{
	/**
	 * Ejecuta el enrutamiento basado en las rutas definidas
	 * @param array $routes Array de rutas disponibles
	 */
	public function execute($routes)
	{
		try {
			$controller = null;
			$action = null;
			
			// Buscar ruta simple
			$routeFound = $this->_getSimpleRoute($routes, $controller, $action);
			
			if (!$routeFound) {
				// Buscar ruta con parametros
				$routeFound = $this->_getParameterRoute($routes, $controller, $action);
			}
			
			// Si no hay ruta, ejecutar controlador de error
			if (!$routeFound || $controller == null || $action == null) {
				throw new Exception('no route added for ' . $_SERVER['REQUEST_URI']);
			}
			else {
				// Ejecutar accion en el controlador
				$controller->execute($action);
			}
		}
		catch(Exception $exception) {
			// Ejecutar controlador de error
			$controller = new ErrorController();
			$controller->setException($exception);
			$controller->execute('error');
		}
	}
	
	/**
	 * Verifica si una ruta tiene parametros
	 * @param string $route Ruta a verificar
	 * @return boolean
	 */
	public function hasParameters($route)
	{
		return preg_match('/(\/:[a-z]+)/', $route);
	}
	
	/**
	 * Obtiene la URI actual limpia
	 * @return string URI procesada
	 */
	protected function _getUri()
	{
		$uri = $_SERVER['REQUEST_URI'];
		
		// Remover query string
		if (false !== $pos = strpos($uri, '?')) {
			$uri = substr($uri, 0, $pos);
		}
		
		// Remover web root si esta definido
		if (defined('WEB_ROOT') && !empty(WEB_ROOT)) {
			$uri = substr($uri, strlen(WEB_ROOT));
		}
		
		return $uri;
	}
	
	/**
	 * Busca ruta simple en el array de rutas
	 * @param array $routes Rutas disponibles
	 * @param object &$controller Controlador encontrado
	 * @param string &$action Accion encontrada
	 * @return boolean True si encuentra ruta
	 */
	protected function _getSimpleRoute($routes, &$controller, &$action)
	{
		$uri = $this->_getUri();
		
		// Buscar coincidencia exacta
		if (array_key_exists($uri, $routes)) {
			$routeFound = $routes[$uri];
		}
		// Buscar con barra final
		elseif (array_key_exists($uri . '/', $routes)) {
			$routeFound = $routes[$uri . '/'];
		}
		// Buscar coincidencia parcial
		else {
			$routeFound = null;
			foreach ($routes as $route => $target) {
				if (substr($uri, 0, strlen($route)) == $route) {
					$routeFound = $target;
					break;
				}
			}
		}
		
		if ($routeFound) {
			// Parsear formato controller#action
			if (strpos($routeFound, '#') !== false) {
				list($name, $action) = explode('#', $routeFound);
				$controllerName = ucfirst($name) . 'Controller';
				$controller = new $controllerName();
				$controller->init();
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Busca ruta parametrizada
	 * @param array $routes Rutas disponibles
	 * @param object &$controller Controlador encontrado
	 * @param string &$action Accion encontrada
	 * @return boolean True si encuentra ruta
	 */
	protected function _getParameterRoute($routes, &$controller, &$action)
	{
		// Implementacion basica para rutas con parametros
		// Se puede expandir segun necesidades
		return false;
	}
}
