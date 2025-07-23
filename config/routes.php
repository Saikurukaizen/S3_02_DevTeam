<?php 

$routes = array(
    '/' => 'task#main',
    '/task' => 'task#index',
    '/task/main' => 'task#main',
    '/task/create' => 'task#create',
    '/task/read' => 'task#read',
	'/task/update' => 'task#update',
    '/task/delete' => 'task#delete',
    '/test' => 'test#index',
    '/user' => 'user#index',
);
