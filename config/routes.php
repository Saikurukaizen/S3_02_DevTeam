<?php
declare(strict_types=1);

$routes = [
    '/' => 'task#index',
    '/home' => 'task#index',
    
    '/task/create' => 'task#create',
    '/task/read' => 'task#read',
    '/task/update' => 'task#update',
    '/task/delete' => 'task#delete',
    
    '/task/view' => 'task#read',
    '/task/edit' => 'task#update',
    '/task/remove' => 'task#delete',
    
    '/test' => 'test#index',
    '/test/check' => 'test#check',
    '/test/form' => 'test#form'    
];
