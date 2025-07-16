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


  /*   public function createAction(): void
    {
    
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
            header('Location: /task/');
            exit;
        }
    } */

    public function updateAction(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $taskId = (int)$this->getParam('id');
            if(!$taskId){
                $this->setFlash('error', 'ID de tarea no válido.');
                header('Location: /task/');
                exit;
            }
            $taskModel = new TaskModel();
            $task = $taskModel->getTaskById($taskId);
            if(!$task){
                $this->setFlash('error', 'Tarea no encontrada.');
                header('Location: /task/');
                exit;
            } else {
                $this->view->task = $task;
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = json_decode(file_get_contents('php://input'), true);
            $taskModel = new TaskModel();

            try{
                $taskModel->updateTask($data['id'], $data);
                $this->setFlash('success', 'Tarea actualizada correctamente.');
            }
            catch(Exception $e){
                $this->setFlash('error', 'Error al actualizar la tarea: ' . $e->getMessage());
            }
            header('Location: /task/');
        }
    }
    //método para el drag & drop de las tareas. (Comprobar si puede ser opcional)
    public function updateStatusAction(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = json_decode(file_get_contents('php://input'), true);
            $taskModel = new TaskModel();

            try{
                $taskModel->updateStatusTask($data['id'], $data['estado']);
                $this->setFlash('success', 'Tarea actualizada correctamente.');
            }
            catch(Exception $e){
                $this->setFlash('error', 'Error al actualizar la tarea: ' . $e->getMessage());
            }
        }
    }  
}

?>