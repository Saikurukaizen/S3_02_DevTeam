<?php
declare(strict_types=1);

//Para persistencia en SQL, esta clase extiende del modelo principal
class TaskModel{

    private $file = 'config/fakeTasks.json';

    public function createTask(array $data): void
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

        $lastId = 0;
        if(!empty($tasks)){
            $ids = array_column($tasks, 'id');
            $lastId = max($ids);
        }
        $data['id'] = $lastId++; //Asigna un nuevo ID a la tarea.

        $tasks[] = $data; // Añade la nueva tarea al array
        //Codifica el array a JSON
        $json = json_encode($tasks, JSON_PRETTY_PRINT);
        //Guardar JSON en el archivo
        if(file_put_contents($this->file, $json) === false){
            throw new Exception("Hubo un fallo al guardar la tarea. ");
        }
    }

    public function updateTask(int $id, array $data): void{
        //Leer el archivo JSON
        $tasks = [];
        if(file_exists($this->file)){
            //Obtener el contenido JSON
            $json = json_decode(file_get_contents($this->file), true);
            //Decodifica el JSON y si es true, lo guarda en un array asociativo
            $tasks = $json ?? [];

            //Busca la tarea a actualizar
            $taskIndex = array_search($id, array_columns($tasks, 'id'));
            foreach($data as $key => $value){
                if(empty($value)){
                    throw new Exception("El campo $key no puede estar vacío.");
                } else {
                    $tasks[$taskIndex][$key] = $value; //Actualiza el campo
                }
            }
            //Guarda el archivo actualizado de nuevo en el JSON.
            $json = json_encode(file_exists($this->file) ? $tasks : [], JSON_PRETTY_PRINT);
            if(file_put_contents($this->file, $json) === false){
                throw new Exception("Hubo un fallo al actualizar la tarea.");
            } else {
                return;
            }            
        }
    }

    public function updateStatusTask(int $id, string $newStatus): void
    {

    }
}

?>