<?php 
    declare(strict_types=1);

    class TaskController extends ApplicationController
    {

        // Las funciones públicas son genéricas porque los sufijos Action() sirven
        // para asociarse a las rutas (por convención)
        public function indexAction(): void
        {
            /*
            Acción asociada a la ruta principal del recurso (/task o /task/index)
            Muestra un listado de todas las tareas.
            */
            $tasks = $this->model->getAll();
            $this->view->tasks = $tasks;
        }

        /* 
        public function createAction(): void
        {
            // Método para instanciar el TaskModel con los $data al createTask()

            Acción asociada a la creación de un nuevo elemento
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
        }
        */

        public function updateStatusAction(): void
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
            }
        }

        // --- READ ---
        // Este método es el encargado de manejar la ruta /task/read
        // Cuando el router detecta /task/read, ejecuta este readAction()
        // Aquí instanciamos el TaskModel para acceder a la persistencia (JSON)
        // Llamamos a getAllTasks() que devuelve todas las tareas guardadas
        // Asignamos el resultado a la vista usando $this->view->tasks
        // Finalmente renderizamos la vista task/read.phtml
        public function readAction(): void
        {
            $taskModel = new TaskModel(); // modelo que maneja tareas
            $tasks = $taskModel->getAllTasks(); // obtenemos todas las tareas
            $this->view->tasks = $tasks; // pasamos las tareas a la vista
        }

        // --- DELETE ---
        // Acción para manejar la eliminación de una tarea por ID
        // Cuando el router detecta /task/delete, ejecuta este deleteAction()
        // Si la petición es GET, muestra confirmación; si es POST, elimina y redirige o devuelve JSON (Drag & Drop)
        public function deleteAction(): void
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Soporta JSON y POST normal
                $data = json_decode(file_get_contents('php://input'), true);
                $id = $data['taskId'] ?? $this->_getParam('id');

                if ($id) {
                    $taskModel = new TaskModel();
                    $taskModel->deleteTask($id);

                    // Si la petición es AJAX (fetch), devuelve JSON
                    if ($data) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => true]);
                        exit;
                    }

                    // Si es formulario normal, redirige
                    header('Location: /fullstackphp-sprint3/S302/S3_02_DevTeam/web/task/read');
                    exit;
                } else {
                    if ($data) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'error' => 'ID inválido']);
                        exit;
                    } else {
                        echo "ID inválido"; die;
                    }
                }

            } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Si es GET, muestra la vista de confirmación

                // Obtiene el ID de la tarea a mostrar
                $id = $this->_getParam('id');

                // Instancia el modelo para buscar la tarea específica
                $taskModel = new TaskModel();
                $tasks = $taskModel->getAllTasks();

                // Busca la tarea por ID
                $task = null;
                foreach ($tasks as $t) {
                    if ($t['id'] == $id) {
                        $task = $t;
                        break;
                    }
                }

                // Pasa la tarea a la vista para confirmar
                $this->view->task = $task;

                // El render se maneja automáticamente en el core/layout
            } else {
                echo "Método no permitido."; die;
            }
        }

    }
?>
