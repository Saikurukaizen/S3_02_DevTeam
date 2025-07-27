<?php
declare(strict_types=1);

class ApplicationController extends Controller 
{
    protected $model = null;
    public $view = null;

    public function init(): void
    {
        try {
            $this->initializeSession();
            $this->view = new View();
            $this->loadAssociatedModel();
        } catch (Exception $e) {
            // Se recomienda loguear el error aquí si hay sistema de logs
            throw $e;
        }
    }

    private function initializeSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Carga automáticamente el modelo asociado al controlador
     */
    private function loadAssociatedModel(): void
    {
        $controllerName = strtolower(str_replace('Controller', '', get_class($this)));
        $modelClass = ucfirst($controllerName) . 'Model';
        if (class_exists($modelClass)) {
            $this->model = new $modelClass();
        }
    }

    /**
     * Carga un modelo específico por nombre
     * @param string $modelName Nombre del modelo (sin el sufijo 'Model')
     * @return object Instancia del modelo
     * @throws Exception Si el modelo no existe
     */
    protected function loadModel(string $modelName): object
    {
        $modelName = trim($modelName);
        if (empty($modelName)) {
            throw new InvalidArgumentException("El nombre del modelo no puede estar vacío");
        }
        $modelClass = ucfirst($modelName) . 'Model';
        if (!class_exists($modelClass)) {
            throw new Exception("Modelo no encontrado: $modelClass");
        }
        return new $modelClass();
    }

    /**
     * Define un mensaje flash para mostrar al usuario
     * @param string $type Tipo de mensaje (success, error, warning, info)
     * @param string $message Mensaje a mostrar
     * @throws InvalidArgumentException Si los parámetros son inválidos
     */
    protected function setFlash(string $type, string $message): void
    {
        $type = trim($type);
        $message = trim($message);
        if (empty($type)) {
            throw new InvalidArgumentException("El tipo de mensaje flash no puede estar vacío");
        }
        if (empty($message)) {
            throw new InvalidArgumentException("El mensaje flash no puede estar vacío");
        }
        $validTypes = ['success', 'error', 'warning', 'info'];
        if (!in_array($type, $validTypes)) {
            throw new InvalidArgumentException("Tipo de mensaje flash inválido: $type");
        }
        $this->initializeSession();
        // Sanitiza el mensaje para evitar XSS
        $_SESSION['flash'][$type] = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Recupera y elimina un mensaje flash por tipo
     * @param string $type Tipo de mensaje
     * @return string|null Mensaje o null si no existe
     */
    protected function getFlash(string $type): ?string
    {
        $this->initializeSession();
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
    }

    /**
     * Verifica si existe un mensaje flash de un tipo específico
     * @param string $type Tipo de mensaje
     * @return bool True si existe
     */
    protected function hasFlash(string $type): bool
    {
        $this->initializeSession();
        return isset($_SESSION['flash'][$type]);
    }

    /**
     * Recupera todos los mensajes flash sin eliminarlos
     * @return array Array asociativo con todos los mensajes flash
     */
    protected function getAllFlashes(): array
    {
        $this->initializeSession();
        return $_SESSION['flash'] ?? [];
    }

    /**
     * Elimina todos los mensajes flash
     */
    protected function clearAllFlashes(): void
    {
        $this->initializeSession();
        unset($_SESSION['flash']);
    }

    /**
     * Redirige a una URL específica
     * @param string $url URL de destino
     * @param int $statusCode Código de estado HTTP (por defecto: 302)
     */
    protected function redirect(string $url, int $statusCode = 302): void
    {
        if (empty($url)) {
            throw new InvalidArgumentException("La URL de redirección no puede estar vacía");
        }
        if (headers_sent()) {
            throw new RuntimeException("No se puede redirigir, las cabeceras ya fueron enviadas");
        }
        http_response_code($statusCode);
        // Sanitiza la URL para evitar inyección de cabeceras
        $safeUrl = filter_var($url, FILTER_SANITIZE_URL);
        header("Location: $safeUrl");
        exit();
    }

    /**
     * Verifica si la petición es AJAX
     * @return bool True si es AJAX
     */
    protected function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Verifica si la petición es de tipo JSON
     * @return bool True si el Content-Type es application/json
     */
    protected function isJsonRequest(): bool
    {
        return isset($_SERVER['CONTENT_TYPE']) && 
               strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }

    /**
     * Devuelve una respuesta JSON
     * @param mixed $data Datos a devolver en JSON
     * @param int $statusCode Código de estado HTTP
     */
    protected function jsonResponse($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        // Opciones de seguridad y compatibilidad en la respuesta JSON
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }
}
