<?php
declare(strict_types=1);

class TaskModel
{
    private string $file;

    private const VALID_STATES = ['pendiente', 'en_progreso', 'completada', 'cancelada'];

    private const DEFAULT_TASK_STRUCTURE = [
        'id' => 0,
        'titulo' => '',
        'descripcion' => '',
        'estado' => 'pendiente',
        'fecha_creacion' => '',
        'fecha_actualizacion' => ''
    ];

    public function __construct()
    {
        $this->file = ROOT_PATH . '/config/fakeTasks.json';
        $this->ensureFileExists();
    }

    public function createTask(array $data): array
    {
        $this->validateTaskData($data, false);
        $tasks = $this->getAllTasks();
        $newId = $this->generateNextId($tasks);
        $newTask = $this->prepareTaskData($data, $newId);
        $now = date('Y-m-d H:i:s');
        $newTask['fecha_creacion'] = $now;
        $newTask['fecha_actualizacion'] = $now;
        $tasks[] = $newTask;
        $this->saveTasksToFile($tasks);
        return $newTask;
    }

    public function getTaskById($id): ?array
    {
        $id = $this->validateId($id);
        $tasks = $this->getAllTasks();
        foreach ($tasks as $task) {
            if (isset($task['id']) && (int)$task['id'] === $id) {
                return $task;
            }
        }
        return null;
    }

    public function getAllTasks(): array
    {
        if (!file_exists($this->file)) {
            return [];
        }
        $content = file_get_contents($this->file);
        if ($content === false || empty(trim($content))) {
            return [];
        }
        $tasks = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }
        return is_array($tasks) ? $tasks : [];
    }

    public function updateTask(int $id, array $data): array
    {
        $id = $this->validateId($id);
        $this->validateTaskData($data, true);
        if (isset($data['id']) && (int)$data['id'] !== $id) {
            throw new Exception("El ID proporcionado ({$data['id']}) no coincide con el ID de la tarea a actualizar ($id).");
        }
        $tasks = $this->getAllTasks();
        $taskFound = false;
        $updatedTask = null;
        foreach ($tasks as &$task) {
            if (isset($task['id']) && (int)$task['id'] === $id) {
                $originalCreationDate = $task['fecha_creacion'] ?? date('Y-m-d H:i:s');
                $task = array_merge($task, $this->prepareTaskData($data, $id));
                $task['fecha_creacion'] = $originalCreationDate;
                $task['fecha_actualizacion'] = date('Y-m-d H:i:s');
                $updatedTask = $task;
                $taskFound = true;
                break;
            }
        }
        if (!$taskFound) {
            throw new Exception("No se encontró la tarea con ID: $id");
        }
        $this->saveTasksToFile($tasks);
        return $updatedTask;
    }

    public function deleteTaskById($id): bool
    {
        $id = $this->validateId($id);
        $tasks = $this->getAllTasks();
        $taskFound = false;
        foreach ($tasks as $index => $task) {
            if (isset($task['id']) && (int)$task['id'] === $id) {
                unset($tasks[$index]);
                $taskFound = true;
                break;
            }
        }
        if (!$taskFound) {
            return false;
        }
        $tasks = array_values($tasks);
        $this->saveTasksToFile($tasks);
        return true;
    }

    public function getTasksByState(string $estado): array
    {
        if (!in_array($estado, self::VALID_STATES)) {
            throw new InvalidArgumentException("Estado inválido: $estado");
        }
        $tasks = $this->getAllTasks();
        return array_filter($tasks, function($task) use ($estado) {
            return isset($task['estado']) && $task['estado'] === $estado;
        });
    }

    public function getTotalTasksCount(): int
    {
        return count($this->getAllTasks());
    }

    public function getTaskStatistics(): array
    {
        $tasks = $this->getAllTasks();
        $stats = array_fill_keys(self::VALID_STATES, 0);
        foreach ($tasks as $task) {
            $estado = $task['estado'] ?? 'pendiente';
            if (isset($stats[$estado])) {
                $stats[$estado]++;
            }
        }
        $stats['total'] = count($tasks);
        return $stats;
    }

    private function validateTaskData(array $data, bool $isUpdate): void
    {
        if (!isset($data['titulo']) || trim($data['titulo']) === '') {
            throw new Exception("El título es obligatorio.");
        }
        if (strlen(trim($data['titulo'])) > 200) {
            throw new Exception("El título no puede exceder 200 caracteres.");
        }
        if (!isset($data['estado']) || trim($data['estado']) === '') {
            throw new Exception("El estado es obligatorio.");
        }
        if (!in_array($data['estado'], self::VALID_STATES)) {
            throw new Exception("Estado inválido. Estados válidos: " . implode(', ', self::VALID_STATES));
        }
        if (isset($data['descripcion']) && strlen($data['descripcion']) > 1000) {
            throw new Exception("La descripción no puede exceder 1000 caracteres.");
        }
        if ($isUpdate && isset($data['id'])) {
            $this->validateId($data['id']);
        }
    }

    private function validateId($id)
    {
        if ($id === null || $id === '') {
            throw new InvalidArgumentException("ID es requerido.");
        }
        if (is_numeric($id)) {
            $id = (int) $id;
            if ($id <= 0) {
                throw new InvalidArgumentException("ID debe ser un número positivo.");
            }
            return $id;
        }
        $id = trim((string) $id);
        if (empty($id)) {
            throw new InvalidArgumentException("ID no puede estar vacío.");
        }
        return $id;
    }

    private function prepareTaskData(array $data, int $id): array
    {
        $prepared = self::DEFAULT_TASK_STRUCTURE;
        $prepared['id'] = $id;
        $prepared['titulo'] = trim($data['titulo'] ?? '');
        $prepared['descripcion'] = trim($data['descripcion'] ?? '');
        $prepared['estado'] = trim($data['estado'] ?? 'pendiente');
        return $prepared;
    }

    private function generateNextId(array $tasks): int
    {
        if (empty($tasks)) {
            return 1;
        }
        $ids = array_column($tasks, 'id');
        return max($ids) + 1;
    }

    private function saveTasksToFile(array $tasks): void
    {
        $json = json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new Exception("Error al codificar datos a JSON: " . json_last_error_msg());
        }
        $result = file_put_contents($this->file, $json, LOCK_EX);
        if ($result === false) {
            throw new Exception("Error al guardar el archivo de tareas.");
        }
    }

    private function ensureFileExists(): void
    {
        $directory = dirname($this->file);
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {
                throw new Exception("No se puede crear el directorio: $directory");
            }
        }
        if (!file_exists($this->file)) {
            if (file_put_contents($this->file, '[]') === false) {
                throw new Exception("No se puede crear el archivo de tareas: {$this->file}");
            }
        }
        if (!is_readable($this->file) || !is_writable($this->file)) {
            throw new Exception("El archivo de tareas no tiene permisos correctos: {$this->file}");
        }
    }
}

