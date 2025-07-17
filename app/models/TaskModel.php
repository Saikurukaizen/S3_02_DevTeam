<?php
declare(strict_types=1);

//Para persistencia en SQL, esta clase extiende del modelo principal
class TaskModel{

    private $file;

    public function __construct()
    {
        $this->file = ROOT_PATH . '/config/fakeTasks.json';
    }

    public function getAll(): array
    {
        
        if(file_exists($this->file)){
            
            $json = file_get_contents($this->file);
            $tasks = json_decode($json, true);
            return $tasks ?? [];           
        }
        return [];
    }

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
        $data['id'] = $lastId +1; //Asigna un nuevo ID a la tarea.

        $tasks[] = $data; // Añade la nueva tarea al array
        //Codifica el array a JSON
        $json = json_encode($tasks, JSON_PRETTY_PRINT);
        //Guardar JSON en el archivo
        if(file_put_contents($this->file, $json) === false){
            throw new Exception("Hubo un fallo al guardar la tarea. ");
        }
    }

    /* public function updateStatusTask(int $id, string $newStatus): void
    {

    } */
}

?>