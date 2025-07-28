<?php

class ApplicationController extends Controller 
{
    protected $model = null;

    public $view = null;

    public function init(): void
    {
        $this->view = new View();
        $controllerName = strtolower(str_replace('Controller', '', get_class($this)));
        $modelClass = ucfirst($controllerName) . 'Model';
        if (class_exists($modelClass)) {
            $this->model = new $modelClass();
        }
    }

    protected function loadModel(string $modelName): object
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
}
