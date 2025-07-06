<?php 
declare(strict_types=1);

class TaskController extends ApplicationController{

    //Las funciones públicas son genéricas proque los sufijos Action() sirven
    //para asociarse a las rutas (por convención)
    public function indexAction(): void
    {

    }

   
    public function createAction(): void
    {
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->crearTarea();
        }
    }

    //Como tenemos funciones generales para el funcionamiento de las rutas, y las tareas
    //sólo tiene que conocerlas TaskController, hacemos funciones privadas para los métodos CRUD.

    private function crearTarea(): void
    {
        $taskModel = new TaskModel();
            //como nos refermios a un modelo de tareas, usamos una variable $data
            //para obtener los parámetros del JSON
            $data = [
                'titulo' => $this->_getParam('titulo'),
                'descripcion' => $this->_getParam('descripcion')
            ];
            $taskModel->crearTarea($data);
    }
}

?>