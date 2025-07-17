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
    // Ruta principal que muestra el tablero de tareas (puedes cambiar a index si preferir)
    '/' => 'task#read',
    '/test' => 'test#index',
    '/user' => 'user#index',
    '/task' => 'task#index',
    // Rutas para leer, eliminar y ver detalle de tareas
    '/task/read' => 'task#read',
    '/task/delete' => 'task#delete',
    // Ruta para ver el detalle de una tarea por id
    '/task/detalle' => 'task#detalle'
);
