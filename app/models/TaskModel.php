<?php
declare(strict_types=1);

//Para persistencia en SQL, esta clase extiende del modelo principal
class TaskModel{

    private $file = 'config/fakeTasks.json';

    public function crearTarea(array $data): void
    {
        foreach($data as $key => $value){
            if(empty($value)){
                throw new Exception("El campo $key no puede estar vacío.");
            }
        }
        //Leer el archivo JSON
        $tasks = [];
        if(file_exists($this->file)){
            //Obtiene el contenido del JSON
            $json = file_get_contents($this->file);
            //Decodifica el JSON y si es true, lo guarda en un array asociativo
            $tasks = json_decode($json, true) ?? [];
        }
    }

    /* public function updateStatusTask(int $id, string $newStatus): void
    {

    } */
}

?>