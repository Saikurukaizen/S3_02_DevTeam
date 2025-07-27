<?php
declare(strict_types=1);

$routes = [
    // ===== RUTAS PRINCIPALES =====
    '/' => 'task#index',                   // Pagina de inicio - Kanban Board
    '/home' => 'task#index',               // Alias para home
    
    // ===== RUTAS DE TAREAS (CRUD COMPLETO) =====
    '/task/create' => 'task#create',       // Crear nueva tarea (GET: formulario, POST: procesar)
    '/task/read' => 'task#read',           // Leer/mostrar tarea especifica (requiere ?id=X)
    '/task/update' => 'task#update',       // Actualizar tarea (GET: formulario, POST: procesar)
    '/task/delete' => 'task#delete',       // Eliminar tarea (POST unicamente)
    
    // ===== RUTAS ADICIONALES DE TAREAS =====
    '/task/view' => 'task#read',           // Alias para ver tarea
    '/task/edit' => 'task#update',         // Alias para editar tarea
    '/task/remove' => 'task#delete',       // Alias para eliminar tarea
    
    // ===== RUTAS DE TESTING/DESARROLLO =====
    '/test' => 'test#index',               // Pagina de testing
    '/test/check' => 'test#check',         // Verificaciones del sistema
    '/test/form' => 'test#form',           // Ruta para probar el formulario
    
    // ===== RUTAS DE USUARIO (FUTURAS) =====
    // '/user' => 'user#index',            // Deshabilitado hasta implementar UserController
    // '/user/login' => 'user#login',      // Login de usuario
    // '/user/logout' => 'user#logout',    // Logout de usuario
    // '/user/profile' => 'user#profile',  // Perfil de usuario
    
    // ===== RUTAS DE API/AJAX (FUTURAS) =====
    // '/api/tasks' => 'api#tasks',         // API REST para tareas
    // '/api/tasks/create' => 'api#createTask',
    // '/api/tasks/update' => 'api#updateTask',
    // '/api/tasks/delete' => 'api#deleteTask',
    
    // ===== RUTAS DE ADMINISTRACION (FUTURAS) =====
    // '/admin' => 'admin#index',           // Panel de administracion
    // '/admin/settings' => 'admin#settings', // Configuraciones
    // '/admin/backup' => 'admin#backup',   // Backup de datos
];
