<?php

/**
 * Base controller for the application.
 * Add general things in this controller.
 */
class ApplicationController extends Controller 
{
    protected $model = null;

    /**
     * Método de inicialización común para todos los controladores hijos
     */
    public function init()
    {
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

    // Puedes añadir aquí filtros before/after si lo necesitas
}
