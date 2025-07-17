<?php

/**
 * Base controller for the application.
 * Add general things in this controller.
 */
class ApplicationController extends Controller 
{
    protected $model = null;

    // Declaramos la propiedad view para que todos los controladores hijos la tengan
    public $view = null;

    /**
     * Método de inicialización común para todos los controladores hijos
     */
    public function init()
    {
        // Inicializa la vista
        $this->view = new View();

        // Ejemplo: cargar el modelo según el nombre del controlador
        $controllerName = strtolower(str_replace('Controller', '', get_class($this)));
        $modelClass = ucfirst($controllerName) . 'Model';
        if (class_exists($modelClass)) {
            $this->model = new $modelClass();
        }
        // Puedes inicializar otras variables o helpers aquí
    }

    /**
     * Método para cargar cualquier modelo por nombre
     */
    protected function loadModel($modelName)
    {
        $modelClass = ucfirst($modelName) . 'Model';
        if (class_exists($modelClass)) {
            return new $modelClass();
        }
        throw new Exception("Modelo no encontrado: $modelClass");
    }

    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'][$type] = $message;
    }

    protected function getFlash(string $type): ?string
    {
        if(isset($_SESSION['flash'][$type])){
            $msg = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $msg;
        }
        return null;
    }

    // Puedes añadir aquí filtros before/after si lo necesitas
}
