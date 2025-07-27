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

    private function isAjaxRequest(): bool
    {
        return isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }

    public function createAction(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if ($this->isAjaxRequest()) {
                $this->processAjaxCreateRequest();
            } else {
                $this->processFormCreateRequest();
            }
        }
        $this->view->action = Environment::url('task/create');
        $this->view->readonly = true;
        $this->view->buttonText = '';
        $this->view->showDelete = false;
        $this->view->cancelUrl = '';
        //$this->view->render('task/create.phtml');
    }

    private function processFormCreateRequest(): void
    {
        $data = [
            'titulo' => $this->_getParam('titulo'),
            'descripcion' => $this->_getParam('descripcion'),
            'estado' => $this->_getParam('estado')
        ];
        try {
            if (empty($data['titulo']) || empty($data['estado'])) {
                throw new Exception('Título y estado son obligatorios.');
            }
            $taskModel = new TaskModel();
            $taskModel->createTask($data);
            $this->setFlash('success', 'Tarea guardada correctamente.');
            header('Location: ' . Environment::url(''));
            exit;
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al crear la tarea: ' . $e->getMessage());
        }
    }

    private function processAjaxCreateRequest(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->handleAjaxCreate($data);
    }

    private function handleAjaxCreate(?array $data): void
    {
        header('Content-Type: application/json');
        try {
            if (!$data || empty($data['titulo']) || empty($data['estado'])) {
                throw new Exception('Título y estado son obligatorios.');
            }
            $taskModel = new TaskModel();
            $taskModel->createTask($data);
            echo json_encode([
                'success' => true,
                'message' => 'Tarea guardada correctamente.'
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error al crear la tarea: ' . $e->getMessage()
            ]);
            exit;
        }
    }
    
    public function readAction(): void
    {
        $id = $this->_getParam('id');
        $taskModel = new TaskModel();
        $task = $taskModel->getTaskById($id);
        $this->view->task = $task;
        $this->view->readonly = true;
        $this->view->buttonText = '';
        $this->view->showDelete = false;
        $this->view->cancelUrl = '';
        //$this->view->render('task/read.phtml');
    }

    public function updateAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $taskId = (int)$this->_getParam('id');
                if (!$taskId) {
                    throw new Exception('ID de tarea no válido.');
                }
                $taskModel = new TaskModel();
                $task = $taskModel->getTaskById($taskId);
                if (!$task) {
                    throw new Exception('Tarea no encontrada.');
                }
                $this->view->task = $task;
                $this->view->action = Environment::url('task/update');
                $this->view->readonly = false;
                $this->view->buttonText = 'Actualizar tarea';
                $this->view->showDelete = true;
                $this->view->cancelUrl = '';
                $this->view->render('task/update.phtml');
                return;
            } catch (Exception $e) {
                $this->setFlash('error', $e->getMessage());
                header('Location: ' . Environment::url('task/main'));
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->isAjaxRequest()) {
                $this->processAjaxUpdateRequest();
            } else {
                $this->processFormUpdateRequest();
            }
        }
    }

    private function processFormUpdateRequest(): void
    {
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
            header('Location: ' . Environment::url('task/read') . '?id=' . $data['id']);
            exit;
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al actualizar la tarea: ' . $e->getMessage());
        }
    }

    private function processAjaxUpdateRequest(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->handleAjaxUpdate($data);
    }

    private function handleAjaxUpdate(?array $data): void
    {
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

    public function deleteAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->isAjaxRequest()) {
                $this->processAjaxDeleteRequest();
            } else {
                $this->processFormDeleteRequest();
            }
        } else {
            header('Location: ' . Environment::url(''));
            exit;
        }
    }

    private function processFormDeleteRequest(): void
    {
        $id = $this->_getParam('id');
        if ($id) {
            $taskModel = new TaskModel();
            $borrado = $taskModel->deleteTaskById($id);
            $this->setFlash('success', 'Tarea eliminada correctamente.');
        } else {
            $this->setFlash('error', 'ID de tarea inválido.');
        }
        header('Location: ' . Environment::url(''));
        exit;
    }

    private function processAjaxDeleteRequest(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['taskId'] ?? null;
        header('Content-Type: application/json');
        if ($id) {
            $taskModel = new TaskModel();
            $borrado = $taskModel->deleteTaskById($id);
            echo json_encode([
                'success' => $borrado,
                'message' => $borrado ? 'Tarea eliminada correctamente.' : 'No se pudo eliminar la tarea.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'ID inválido'
            ]);
        }
        exit;
    }  
}
?>
