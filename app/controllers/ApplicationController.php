<?php
declare(strict_types=1);

class ApplicationController extends Controller 
{
    protected $model = null;
    public $view = null;

    public function init(): void
    {
        $this->initializeSession();
        $this->view = new View();
        $this->loadAssociatedModel();        
    }

    private function initializeSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function loadAssociatedModel(): void
    {
        $controllerName = strtolower(str_replace('Controller', '', get_class($this)));
        $modelClass = ucfirst($controllerName) . 'Model';
        if (class_exists($modelClass)) {
            $this->model = new $modelClass();
        }
    }

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
        //$_SESSION['flash'][$type] = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    }

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

    protected function hasFlash(string $type): bool
    {
        $this->initializeSession();
        return isset($_SESSION['flash'][$type]);
    }

    protected function getAllFlashes(): array
    {
        $this->initializeSession();
        return $_SESSION['flash'] ?? [];
    }

    protected function clearAllFlashes(): void
    {
        $this->initializeSession();
        unset($_SESSION['flash']);
    }

    /* protected function redirect(string $url, int $statusCode = 302): void
    {
        if (empty($url)) {
            throw new InvalidArgumentException("La URL de redirección no puede estar vacía");
        }
        if (headers_sent()) {
            throw new RuntimeException("No se puede redirigir, las cabeceras ya fueron enviadas");
        }
        http_response_code($statusCode);
        $safeUrl = filter_var($url, FILTER_SANITIZE_URL);
        header("Location: $safeUrl");
        exit();
    } */

    protected function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    protected function isJsonRequest(): bool
    {
        return isset($_SERVER['CONTENT_TYPE']) && 
               strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;
    }

    protected function jsonResponse($data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }
}
