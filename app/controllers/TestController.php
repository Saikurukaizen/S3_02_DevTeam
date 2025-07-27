<?php
declare(strict_types=1);

/**
 * Controlador de Pruebas del Sistema
 * 
 * Permite probar y depurar funcionalidades específicas sin afectar el sistema principal.
 * No utiliza modelo asociado por defecto.
 * 
 * @author Sistema de Tareas
 * @version 2.1
 */
class TestController extends ApplicationController
{
    /**
     * Inicializa el controlador de pruebas.
     * No llama a parent::init() para evitar cargar TestModel.
     * Inicializa manualmente los componentes necesarios.
     */
    public function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->view = new View();
        $this->model = null; // Desactiva explícitamente el modelo.
    }

    /**
     * Acción principal de pruebas.
     */
    public function indexAction(): void
    {
        // Aquí se pueden agregar pruebas generales del sistema.
    }

    /**
     * Acción para verificaciones del sistema.
     */
    public function checkAction(): void
    {
        // Aquí se pueden agregar verificaciones específicas.
    }

    /**
     * Acción para probar y depurar el formulario de tareas.
     * Simula una tarea y configura la vista para pruebas.
     */
    public function formAction(): void
    {
        // 1. Simula una tarea para pasar a la vista
        $this->view->task = [
            'id' => 'test-123',
            'titulo' => 'Tarea de Prueba',
            'descripcion' => 'Esta es una descripción de prueba para el formulario.',
            'asignado_a' => 'Desarrollador de Prueba',
            'estado' => 'en_progreso'
        ];

        // 2. Define las variables que el formulario espera
        $this->view->action = Environment::url('test/form');
        $this->view->buttonText = 'Probar Formulario';
        $this->view->isReadOnly = false; // Cambia a true para probar el modo lectura

        // 3. Renderiza una vista de prueba específica
        $this->view->render('test/form.phtml');
    }
}
