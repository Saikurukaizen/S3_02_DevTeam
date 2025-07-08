<?php 
declare(strict_types=1);

class TaskController extends ApplicationController{

    //Las funciones públicas son genéricas proque los sufijos Action() sirven
    //para asociarse a las rutas (por convención)
    public function indexAction(): void
    {
        /*Acción asociada a la ruta principal del recurso (/task o /task/index)
        Muestra un listado de todas las tareas.
        Aquí instancias el modelo, obtienes todas las tareas y las pasas a la vista.
        Muestra el listado por GET*/
        $taskModel = new TaskModel();
        $tasks = $taskModel->getAll();
        $this->view->tasks = $tasks;
    }

    /*Método para instanciar el TaskModel con los $data al createTask()
    public function createAction(): void
    {
        /*Acción asociada a la creación de un nuevo elemento
        Procesa el guardado de la nueva tarea si es POST
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = [
                'titulo' => $this->_getParam('titulo'),
                'descripcion' => $this->_getParam('descripcion'),
                'estado' => $this->_getParam('estado')
            ];
            $taskModel = new TaskModel();
            $taskModel->createTask($data);
        }
    }*/

    

    
}

?>