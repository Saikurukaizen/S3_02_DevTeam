<?php 

$routes = array(
 // ===== RUTAS PRINCIPALES =====
    '/' => 'task#index',                   // Pagina de inicio - Kanban Board
    '/home' => 'task#index',               // Alias para home
    
    // ===== RUTAS DE TAREAS (CRUD COMPLETO) =====
    // '/task' => 'task#index',            // Vista principal Kanban (eliminada por redundancia)
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
);
