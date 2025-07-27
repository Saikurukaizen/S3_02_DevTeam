<?php 
declare(strict_types=1);

/**
 * Controlador de Tareas
 * 
 * Maneja operaciones CRUD para tareas con soporte para formularios y AJAX.
 * Implementa validación robusta y manejo de errores.
 * Código limpio, seguro y organizado.
 * 
 * @author Sistema de Tareas
 * @version 2.1
 */
class TaskController extends ApplicationController
{
    // Estados válidos para las tareas
    private const VALID_STATES = ['pendiente', 'en_progreso', 'completada', 'cancelada'];

    /**
     * Vista principal del sistema - Kanban Board
     * Carga todas las tareas y las organiza por estado
     */
    public function indexAction(): void
    {
        try {
            $tasks = $this->model->getAllTasks();
            $this->view->tasks = $tasks;
            $this->view->isIndexPage = true; // Flag para la página principal
        } catch (Exception $e) {
            $this->setFlash('error', 'Error al cargar las tareas: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
            $this->view->tasks = [];
        }
    }

    /**
     * Crear nueva tarea
     * GET: Muestra formulario | POST: Procesa datos
     */
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

        // GET request - mostrar formulario
        $this->setupCreateView();
    }

    /**
     * Configura la vista para crear tarea
     */
    private function setupCreateView(): void
    {
        $this->view->action = Environment::url('task/create');
        $this->view->readonly = false;
        $this->view->buttonText = 'Crear Tarea';
        $this->view->showDelete = false;
        $this->view->cancelUrl = Environment::url('task');
    }

    /**
     * Procesa requisición de formulario para crear tarea
     */
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

    /**
     * Procesa requisición AJAX para crear tarea
     */
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

    /**
     * Ver detalles de tarea específica
     * Requiere parámetro 'id' en la URL
     */
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

    /**
     * Actualizar tarea - maneja GET y POST
     */
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

    /**
     * Muestra el formulario de actualización
     */
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

    /**
     * Procesa requisición de formulario para actualizar tarea
     */
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

    /**
     * Procesa requisición AJAX para actualizar tarea
     */
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

    /**
     * Eliminar tarea - solo acepta POST
     */
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

    /**
     * Procesa requisición de formulario para eliminar tarea
     */
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

    /**
     * Procesa requisición AJAX para eliminar tarea
     */
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

    /**
     * Valida y sanitiza los datos de la tarea
     * @param array $data Datos a validar
     * @param bool $isUpdate Si es una actualización (incluye validación de ID)
     * @return array Datos validados y sanitizados
     * @throws Exception Si los datos no son válidos
     */
    private function validateAndSanitizeTaskData(array $data, bool $isUpdate = false): array
    {
        // Sanitizar datos
        $sanitized = [
            'titulo' => trim($data['titulo'] ?? ''),
            'descripcion' => trim($data['descripcion'] ?? ''),
            'estado' => trim($data['estado'] ?? '')
        ];
        // Validaciones
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
        // Para updates, incluir y validar ID
        if ($isUpdate) {
            $sanitized['id'] = $this->validateTaskId($data['id'] ?? null);
        }
        return $sanitized;
    }

    /**
     * Valida que el ID de la tarea sea válido
     * Acepta números enteros y strings/UUIDs
     * @param mixed $id ID a validar
     * @return mixed ID validado
     * @throws Exception Si el ID es inválido
     */
    private function validateTaskId($id)
    {
        if ($id === null || $id === '') {
            throw new Exception('ID de tarea es requerido.');
        }
        // Si es numérico, validar como entero positivo
        if (is_numeric($id)) {
            $id = (int) $id;
            if ($id <= 0) {
                throw new Exception('ID de tarea debe ser un número positivo.');
            }
            return $id;
        }
        // Para strings/UUIDs, solo verificar que no esté vacío
        $id = trim((string) $id);
        if (empty($id)) {
            throw new Exception('ID de tarea no puede estar vacío.');
        }
        return $id;
    }

    /**
     * Obtiene y decodifica input JSON de la request
     * @return array Datos decodificados
     * @throws Exception Si JSON es inválido
     */
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
