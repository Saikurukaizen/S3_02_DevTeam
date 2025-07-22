<?php 
    declare(strict_types=1);

class TaskController extends ApplicationController
{

    public function indexAction(): void
    {
        $tasks = $this->model->getAllTasks();
        $this->view->tasks = $tasks;
    }

    public function mainAction(): void
    {
        $taskModel = new TaskModel();
        $tasks = $taskModel->getAllTasks();
        $this->view->tasks = $tasks;
    }

    public function createAction(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            try {
                $data = [
                    'titulo' => $this->_getParam('titulo'),
                    'descripcion' => $this->_getParam('descripcion'),
                    'estado' => $this->_getParam('estado')
                ];
                
                if (empty($data['titulo']) || empty($data['estado'])) {
                    throw new Exception('Título y estado son obligatorios.');
                }
                
                $taskModel = new TaskModel();
                $taskModel->createTask($data);
                $this->setFlash('success', 'Tarea guardada correctamente.');
                
                header('Location: ' . url(''));
                exit;
            } catch (Exception $e) {
                $this->setFlash('error', 'Error al crear la tarea: ' . $e->getMessage());
            }
        }       
        $this->view->disableLayout();
        $this->view->action = url('task/create');
        $this->view->render('task/create.phtml');
        exit;
    }

    public function readAction() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $taskModel = new TaskModel();
        $task = $taskModel->getTaskById($id);
        $this->view->task = $task;
        $this->view->disableLayout();
        $this->view->render('task/read.phtml');
        exit;
    }
    
    public function updateAction(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            try {
                $taskId = (int)$this->_getParam('id');
                if(!$taskId){
                    throw new Exception('ID de tarea no válido.');
                }
                $taskModel = new TaskModel();
                $task = $taskModel->getTaskById($taskId);
                if(!$task){
                    throw new Exception('Tarea no encontrada.');
                }
                $this->view->task = $task;
                $this->view->disableLayout();
                $this->view->action = url('task/update');
                $this->view->render('task/update.phtml');
                exit;
            } catch (Exception $e) {
                $this->setFlash('error', $e->getMessage());
                header('Location: ' . url(''));
                exit;
            }
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if ($this->isAjaxRequest()) {
                $data = json_decode(file_get_contents('php://input'), true);
                $this->handleAjaxUpdate($data);
            } else {
                $data = [
                    'id' => (int)$this->_getParam('id'),
                    'titulo' => $this->_getParam('titulo'),
                    'descripcion' => $this->_getParam('descripcion'),
                    'estado' => $this->_getParam('estado')
                ];
                try {
                    if (empty($data['titulo']) || empty($data['estado'])) {
                        throw new Exception('Título y estado son obligatorios.');
                    }
                    $taskModel = new TaskModel();
                    $taskModel->updateTask($data['id'], $data);
                    $this->setFlash('success', 'Tarea actualizada correctamente.');
                    header('Location: ' . url('task/read') . '?id=' . $data['id']);
                    exit;
                } catch (Exception $e) {
                    $this->setFlash('error', 'Error al actualizar la tarea: ' . $e->getMessage());
                }
            }
        }
    }

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

    private function isAjaxRequest(): bool{
        return isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }

    private function handleAjaxUpdate(?array $data): void {
        header('Content-Type: application/json');
        try {
            if (!$data || empty($data['id']) || empty($data['titulo']) || empty($data['estado'])) {
                throw new Exception('ID, título y estado son obligatorios.');
            }
            $taskModel = new TaskModel();
            $taskModel->updateTask($data['id'], $data);
            echo json_encode([
                'success' => true,
                'message' => 'Tarea actualizada correctamente.'
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar la tarea: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    
}
?>
