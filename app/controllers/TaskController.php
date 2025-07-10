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

        public function updateStatusAction(): void
        {
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $data = json_decode(file_get_contents('php://input'), true);
            }
        }
        
        // --- READ ---
        // Este metodo es el encargado de manejar la ruta /task/read
        // Cuando el router detecta /task/read, ejecuta este readAction()
        // Aqui instanciamos el TaskModel para acceder a la persistencia (JSON)
        // Llamamos a getAllTasks() que devuelve todas las tareas guardadas
        // Asignamos el resultado a la vista usando $this->view->tasks
        // Finalmente renderizamos la vista task/read.phtml
        public function readAction() {
            $taskModel = new TaskModel(); // modelo que maneja tareas
            $tasks = $taskModel->getAllTasks(); // obtenemos todas las tareas
            $this->view->tasks = $tasks; // pasamos las tareas a la vista
            //$this->view->render("task/read.phtml"); // renderizamos la vista correspondiente
        }
        
    }

?>