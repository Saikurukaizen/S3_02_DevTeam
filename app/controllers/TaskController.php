<?php 
declare(strict_types=1);

class TaskController extends ApplicationController
{
    private const VALID_STATES = ['pendiente', 'en_progreso', 'completada', 'cancelada'];

    public function indexAction(): void
    {
        try {
            $tasks = $this->model->getAllTasks();
            $this->view->tasks = $tasks;
            $this->view->isIndexPage = true;
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al cargar las tareas: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
            $this->view->tasks = [];
        }
    }

    public function createAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->isJsonRequest()) {
                $this->processAjaxCreateRequest();
            } else {
                $this->processFormCreateRequest();
            }
            return;
        }
        $this->setupCreateView();
    }

    private function setupCreateView(): void
    {
        $this->view->action = Environment::url('task/create');
        $this->view->readonly = false;
        $this->view->buttonText = 'Crear Tarea';
        $this->view->showDelete = false;
        $this->view->cancelUrl = Environment::url('task');
    }

    private function processFormCreateRequest(): void
    {
        try {
            $data = $this->validateAndSanitizeTaskData([
                'titulo' => $this->_getParam('titulo'),
                'descripcion' => $this->_getParam('descripcion'),
                'estado' => $this->_getParam('estado')
            ]);
            $this->model->createTask($data);
            $this->setFlash('success', 'Tarea creada correctamente.');
            $this->redirect(Environment::url('task'));
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al crear la tarea: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
            $this->setupCreateView();
        }
    }

    private function processAjaxCreateRequest(): void 
    {
        try {
            $data = $this->getJsonInput();
            $validatedData = $this->validateAndSanitizeTaskData($data);
            $this->model->createTask($validatedData);
            $this->jsonResponse([
                'success' => true,
                'message' => 'Tarea creada correctamente.',
                'data' => $validatedData
            ]);
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Error al crear la tarea: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
            ], 400);
        }
    }

    public function readAction(): void
    {
        try {
            $id = $this->validateTaskId($this->_getParam('id'));
            $task = $this->model->getTaskById($id);
            if (!$task) {
                throw new Exception('Tarea no encontrada.');
            }
            $this->view->task = $task;
        } catch (Exception $e) {
            $this->setFlash('error', htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
            $this->redirect(Environment::url('/'));
        }
    }

    public function updateAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->showUpdateForm();
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->isJsonRequest()) {
                $this->processAjaxUpdateRequest();
            } else {
                $this->processFormUpdateRequest();
            }
        }
    }

    private function showUpdateForm(): void
    {
        try {
            $taskId = $this->validateTaskId($this->_getParam('id'));
            $task = $this->model->getTaskById($taskId);
            if (!$task) {
                throw new Exception('Tarea no encontrada.');
            }
            $this->view->task = $task;
            $this->view->action = Environment::url('task/update');
            $this->view->readonly = false;
            $this->view->buttonText = 'Actualizar Tarea';
            $this->view->showDelete = true;
            $this->view->cancelUrl = Environment::url('task/read?id=' . $taskId);
        } catch (Exception $e) {
            $this->setFlash('error', htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
            $this->redirect(Environment::url('task'));
        }
    }

    private function processFormUpdateRequest(): void
    {
        try {
            $id = $this->validateTaskId($this->_getParam('id'));
            $data = $this->validateAndSanitizeTaskData([
                'id' => $id,
                'titulo' => $this->_getParam('titulo'),
                'descripcion' => $this->_getParam('descripcion'),
                'estado' => $this->_getParam('estado')
            ], true);
            $this->model->updateTask($id, $data);
            $this->setFlash('success', 'Tarea actualizada correctamente.');
            $this->redirect(Environment::url('task/read?id=' . $id));
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al actualizar la tarea: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
            $this->redirect(Environment::url('task/update?id=' . ($this->_getParam('id') ?? '')));
        }
    }

    private function processAjaxUpdateRequest(): void
    {
        try {
            $data = $this->getJsonInput();
            $id = $this->validateTaskId($data['id'] ?? null);
            $validatedData = $this->validateAndSanitizeTaskData($data, true);
            $this->model->updateTask($id, $validatedData);
            $this->jsonResponse([
                'success' => true,
                'message' => 'Tarea actualizada correctamente.',
                'data' => $validatedData
            ]);
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Error al actualizar la tarea: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
            ], 400);
        }
    }

    public function deleteAction(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(Environment::url('task'));
            return;
        }
        if ($this->isJsonRequest()) {
            $this->processAjaxDeleteRequest();
        } else {
            $this->processFormDeleteRequest();
        }
    }

    private function processFormDeleteRequest(): void
    {
        try {
            $id = $this->validateTaskId($this->_getParam('id'));
            $deleted = $this->model->deleteTaskById($id);
            if ($deleted) {
                $this->setFlash('success', 'Tarea eliminada correctamente.');
            } else {
                $this->setFlash('error', 'No se pudo eliminar la tarea.');
            }
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al eliminar la tarea: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
        }
        $this->redirect(Environment::url('task'));
    }

    private function processAjaxDeleteRequest(): void
    {
        try {
            $data = $this->getJsonInput();
            $id = $this->validateTaskId($data['taskId'] ?? null);
            $deleted = $this->model->deleteTaskById($id);
            $this->jsonResponse([
                'success' => $deleted,
                'message' => $deleted ? 'Tarea eliminada correctamente.' : 'No se pudo eliminar la tarea.'
            ]);
        } catch (Exception $e) {
            $this->jsonResponse([
                'success' => false,
                'message' => 'Error al eliminar la tarea: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
            ], 400);
        }
    }

    private function validateAndSanitizeTaskData(array $data, bool $isUpdate = false): array
    {
        $sanitized = [
            'titulo' => trim($data['titulo'] ?? ''),
            'descripcion' => trim($data['descripcion'] ?? ''),
            'estado' => trim($data['estado'] ?? '')
        ];
        if (empty($sanitized['titulo'])) {
            throw new Exception('El título es obligatorio.');
        }
        if (strlen($sanitized['titulo']) > 200) {
            throw new Exception('El título no puede exceder 200 caracteres.');
        }
        if (empty($sanitized['estado'])) {
            throw new Exception('El estado es obligatorio.');
        }
        if (!in_array($sanitized['estado'], self::VALID_STATES)) {
            throw new Exception('Estado inválido. Estados válidos: ' . implode(', ', self::VALID_STATES));
        }
        if (strlen($sanitized['descripcion']) > 1000) {
            throw new Exception('La descripción no puede exceder 1000 caracteres.');
        }
        if ($isUpdate) {
            $sanitized['id'] = $this->validateTaskId($data['id'] ?? null);
        }
        return $sanitized;
    }

    private function validateTaskId($id)
    {
        if ($id === null || $id === '') {
            throw new Exception('ID de tarea es requerido.');
        }

        if (is_numeric($id)) {
            $id = (int) $id;
            if ($id <= 0) {
                throw new Exception('ID de tarea debe ser un número positivo.');
            }
            return $id;
        }
        $id = trim((string) $id);
        if (empty($id)) {
            throw new Exception('ID de tarea no puede estar vacío.');
        }
        return $id;
    }

    private function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        if (empty($input)) {
            throw new Exception('No se recibieron datos JSON.');
        }
        $data = json_decode($input, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON inválido: ' . json_last_error_msg());
        }
        return $data ?? [];
    }
}
