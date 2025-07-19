<?php 
    declare(strict_types=1);

class TaskController extends ApplicationController
{
    // Las funciones publicas son genericas porque los sufijos Action() sirven
    // para asociarse a las rutas (por convencion)
    public function indexAction(): void
    {
        /*
        Accion asociada a la ruta principal del recurso (/task o /task/index)
        Muestra un listado de todas las tareas.
        */
        $tasks = $this->model->getAllTasks();
        $this->view->tasks = $tasks;
    }

    public function createAction(): void
    {
        // Metodo para instanciar el TaskModel con los $data al createTask()
        // Accion asociada a la creacion de un nuevo elemento
        // Procesa el guardado de la nueva tarea si es POST
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            try {
                $data = [
                    'titulo' => $this->_getParam('titulo'),
                    'descripcion' => $this->_getParam('descripcion'),
                    'estado' => $this->_getParam('estado')
                ];
                
                // Validar que los campos requeridos estén presentes
                if (empty($data['titulo']) || empty($data['estado'])) {
                    throw new Exception('Título y estado son obligatorios.');
                }
                
                $taskModel = new TaskModel();
                $taskModel->createTask($data);
                $this->setFlash('success', 'Tarea guardada correctamente.');
                
                // Redirigir de vuelta al tablero principal
                header('Location: ' . url(''));
                exit;
            } catch (Exception $e) {
                $this->setFlash('error', 'Error al crear la tarea: ' . $e->getMessage());
                // No redirigir, mostrar el formulario con el error
            }
        }
        
        // Si es GET, mostrar SOLO el formulario de creación (sin layout do tablero)
        $this->view->disableLayout();
        $this->view->render('task/create.phtml');
        exit;
    }

     // --- READ ---
    // Este metodo es el encargado de manejar la ruta /task/read
    // Cuando el router detecta /task/read, ejecuta este readAction()
    // Aqui instanciamos el TaskModel para acceder a la persistencia (JSON)
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
    
    public function updateAction(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $taskId = (int)$this->_getParam('id');
            
            // Debug: verificar si el ID está siendo recibido
            error_log("DEBUG UPDATE: taskId = " . $taskId);
            error_log("DEBUG UPDATE: _GET = " . print_r($_GET, true));
            
            if(!$taskId){
                $this->setFlash('error', 'ID de tarea no válido.');
                header('Location: ' . url(''));
                exit;
            }
            $taskModel = new TaskModel();
            $task = $taskModel->getTaskById($taskId);
            
            // Debug: verificar si la tarea fue encontrada
            error_log("DEBUG UPDATE: task encontrada = " . ($task ? 'SÍ' : 'NO'));
            
            if(!$task){
                $this->setFlash('error', 'Tarea no encontrada.');
                header('Location: ' . url(''));
                exit;
            } else {
                $this->view->task = $task;
            }
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            try {
                $data = [
                    'id' => (int)$this->_getParam('id'),
                    'titulo' => $this->_getParam('titulo'),
                    'descripcion' => $this->_getParam('descripcion'),
                    'estado' => $this->_getParam('estado')
                ];
                
                // Validar que los campos requeridos estén presentes
                if (empty($data['titulo']) || empty($data['estado'])) {
                    throw new Exception('Título y estado son obligatorios.');
                }
                
                $taskModel = new TaskModel();
                $taskModel->updateTask($data['id'], $data);
                $this->setFlash('success', 'Tarea actualizada correctamente.');
                
                // Redirigir de vuelta a los detalles de la tarea
                header('Location: ' . url('task/detalle') . '?id=' . $data['id']);
                exit;
            } catch (Exception $e) {
                $this->setFlash('error', 'Error al actualizar la tarea: ' . $e->getMessage());
                // No redirigir, mostrar el formulario con el error
            }
        }
        
        // Si es GET, mostrar SOLO el formulario de actualización (sin layout do tablero)
        $this->view->disableLayout();
        $this->view->render('task/update.phtml');
        exit;
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
   
    // --- DELETE ---
    // Metodo para eliminar una tarea por ID
    // Solo acepta POST (AJAX o tradicional)
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
                
                // POST tradicional: redirige al tablero principal
                $this->setFlash('success', 'Tarea eliminada correctamente.');
                header('Location: ' . url(''));
                exit;
            } else {
                if ($data) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'ID inválido']);
                    exit;
                } else {
                    $this->setFlash('error', 'ID de tarea inválido.');
                    header('Location: ' . url(''));
                    exit;
                }
            }
        } else {
            // Si no es POST, redirige al tablero
            header('Location: ' . url(''));
            exit;
        }
    }
}
?>
