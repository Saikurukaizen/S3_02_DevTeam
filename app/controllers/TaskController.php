<?php 
declare(strict_types=1);

class TaskController extends ApplicationController{

    public function indexAction(): void
    {

        $tasks = $this->model->getAll();
        $this->view->tasks = $tasks;

    }


    public function createAction(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = [
                'titulo' => $this->_getParam('titulo'),
                'descripcion' => $this->_getParam('descripcion'),
                'estado' => $this->_getParam('estado')
            ];
            $taskModel = new TaskModel();

            try{
                $taskModel->createTask($data, null);
                $this->setFlash('success', 'Tarea guardada correctamente.');
            }
            catch(Exception $e){
                $this->setFlash('error', 'Error al guardar la tarea: ' . $e->getMessage());
            }
            header('Location: /task/');
            exit;
        }
    }
}

?>