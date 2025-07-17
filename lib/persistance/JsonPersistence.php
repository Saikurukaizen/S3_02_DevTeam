<?php
declare(strict_types=1);

class JsonPersistence implements PersistenceInterface{
    

    public function getAll($entity) {}
    public function getById($entity, $id) {}
    public function createTask($entity, $data) {}
    public function updateTask($entity, int $id, array $data): void{
        //validación de los datos
        foreach($data as $key => $value){
            if(empty($value)){
                throw new Exception("Este campo $key no debe estar vacío!");
            }
        }
        if(!isset($data['id']) || $data['id'] !== $id){
            throw new Exception("El ID no coincide con el ID de la tarea a actualizar.");
        } 
        //Leer el archivo JSON
        if(file_exists($this->file)){
            $tasks = json_decode(file_get_contents($this->file), true) ?? [];

            //Bucle para las tareas. $task es una copia del array original. Para modificar el array original, se usa la referencia (&$task).
            foreach($tasks as &$task){
                if($task['id'] === $id){
                    $task = array_merge($task, $data);
                }
            }

            $json = json_encode($tasks, JSON_PRETTY_PRINT);
            if(file_put_contents($this->file, $json) === false){
                throw new Exception("Hubo un fallo al actualizar la tarea.");
            } else {
                return;
            }            
        }
    }
        
    
    public function updateStatusTask($entity, $id, $status) {}
    public function deleteTask($entity, $id) {}
}
}