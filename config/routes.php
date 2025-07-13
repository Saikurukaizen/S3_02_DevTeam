<?php 

/**
 * Used to define the routes in the system.
 * 
 * A route should be defined with a key matching the URL and an
 * controller#action-to-call method. E.g.:
 * 
 * '/' => 'index#index',
 * '/calendar' => 'calendar#index'
 */
$routes = array(
	'/test' => 'test#index',
	'/user' => 'user#index',
	'/task' => 'task#index',
	'/task/create' => 'task#create',
	'/task/update' => 'task#update',
	//creo una ruta específica para el drag & drop de las tareas (puede ser opcional).
	'/task/updateStatus' => 'task#updateStatus'
);
