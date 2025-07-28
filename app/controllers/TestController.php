<?php
declare(strict_types=1);

class TestController extends ApplicationController
{

    public function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->view = new View();
        $this->model = null;
    }

    public function formAction(): void
    {
        $this->view->task = [
            'id' => 'test-123',
            'titulo' => 'Tarea de Prueba',
            'descripcion' => 'Esta es una descripción de prueba para el formulario.',
            'asignado_a' => 'Desarrollador de Prueba',
            'estado' => 'en_progreso'
        ];

        $this->view->action = Environment::url('test/form');
        $this->view->buttonText = 'Probar Formulario';
        $this->view->isReadOnly = false;
        $this->view->render('test/form.phtml');
    }
}
