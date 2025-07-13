<?php 
declare(strict_types=1);

class TaskController extends ApplicationController{

    //Las funciones públicas son genéricas proque los sufijos Action() sirven
    //para asociarse a las rutas (por convención)
    public function indexAction(): void
    {
        /*Acción asociada a la ruta principal del recurso (/task o /task/index)
        Muestra un listado de todas las tareas.
       */
        $tasks = $this->model->getAll();
        $this->view->tasks = $tasks;
    }


    public function createAction(): void
    {
        /*Acción asociada a la creación de un nuevo elemento
        Procesa el guardado de la nueva tarea si es POST*/
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = [
                'titulo' => $this->_getParam('titulo'),
                'descripcion' => $this->_getParam('descripcion'),
                'estado' => $this->_getParam('estado')
            ];
            $taskModel = new TaskModel();

            try{
                $taskModel->createTask($data);
                $this->setFlash('success', 'Tarea guardada correctamente.');
            }
            catch(Exception $e){
                $this->setFlash('error', 'Error al guardar la tarea: ' . $e->getMessage());
            }
            header('Location: ./app/task/create');
            exit;
        }

        $this->view->form = 'layouts/form.phtml';
        require 'app/views/layouts/layout.phtml';
    }

    /* public function updateStatusAction(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = json_decode(file_get_contents('php://input'), true);
        }
    }
 */  
}

?>