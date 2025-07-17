<?php 
    declare(strict_types=1);

    class TaskController extends ApplicationController{

    // Este controlador maneja las acciones relacionadas con las tareas
    // Incluye metodos para listar, crear, leer, mostrar detalle y actualizar estado

    // Metodo para mostrar todas las tareas
    public function indexAction(): void
    {
        // Obtenemos todas las tareas usando el modelo
        $tasks = $this->model->getAllTasks(); // Si tu modelo solo tem getAll(), troca aqui
        $this->view->tasks = $tasks;
    }

    // Metodo para crear una nueva tarea
    public function createAction(): void
    {
        // Si la peticion es POST, procesamos los datos
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
    }

    // Metodo para actualizar el estado de una tarea (placeholder, puedes implementar la logica)
    public function updateStatusAction(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = json_decode(file_get_contents('php://input'), true);
            // Aqui puedes agregar la logica para actualizar el estado
        }
    }

    // Metodo para leer todas las tareas y pasarlas a la vista
    public function readAction() {
        $taskModel = new TaskModel();
        $tasks = $taskModel->getAllTasks();
        $this->view->tasks = $tasks;
    }

    // Metodo para mostrar el detalle de una tarea especifica
    public function detalleAction() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $taskModel = new TaskModel();
        $task = $taskModel->getTaskById($id);
        $this->view->task = $task;
        $this->view->disableLayout();
        $this->view->render('task/detalle.phtml');
        exit;
    }
        
    }

?>