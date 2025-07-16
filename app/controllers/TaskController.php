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
            $tasks = $this->model->getAllTasks();
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

        // --- DETALLE ---
        // Metodo para mostrar los detalles de una tarea especifica
        // Implementado/ajustado por mi: busca la tarea por id, pasa a la vista y renderiza manualmente sin layout
        public function detalleAction() {
            // agarra el id de la url (GET)
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            // instancia el modelo de tareas
            $taskModel = new TaskModel();
            // busca la tarea por id
            $task = $taskModel->getTaskById($id);
            // paso la tarea a la vista (asi la view puede usar $this->task)
            $this->view->task = $task;
            // desactivo el layout para mostrar solo el detalle
            $this->view->disableLayout();
            // renderizo la vista de detalle manualmente (sin layout)
            $this->view->render('task/detalle.phtml');
            exit;
        }

        // --- DELETE ---
        // Método unificado para eliminar una tarea por ID
        // Soporta POST AJAX (JSON), POST tradicional (formulario) y GET para confirmación
        public function deleteAction() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $id = $data['taskId'] ?? $this->_getParam('id');
                if ($id) {
                    $taskModel = new TaskModel();
                    $borrado = $taskModel->deleteTaskById($id);
                    if ($data) {
                        // AJAX: responde JSON
                        header('Content-Type: application/json');
                        echo json_encode([
                            'success' => $borrado,
                            'message' => $borrado ? 'Tarea eliminada correctamente.' : 'No se pudo eliminar la tarea.'
                        ]);
                        exit;
                    }
                    // POST tradicional: redirige
                    header('Location: /fullstackphp-sprint3/S302/S3_02_DevTeam/web/task/read');
                    exit;
                } else {
                    if ($data) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'ID inválido']);
                        exit;
                    } else {
                        echo "ID inválido"; die;
                    }
                }
            } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Si es GET, muestra la vista de confirmación
                $id = $this->_getParam('id');
                $taskModel = new TaskModel();
                $tasks = $taskModel->getAllTasks();
                $task = null;
                foreach ($tasks as $t) {
                    if ($t['id'] == $id) {
                        $task = $t;
                        break;
                    }
                }
                $this->view->task = $task;
            } else {
                echo "Método no permitido."; die;
            }
        }

    }
?>
