<?php
declare(strict_types=1);

class TaskModel{
    private $file;

    public function __construct()
    {
        $this->file = ROOT_PATH . '/config/fakeTasks.json';
    }

    public function createTask(array $data): void
    {
        foreach($data as $key => $value){
            if(empty($value)){
                throw new Exception("El campo $key no puede estar vacio.");
            }
        }
        $tasks = $this->getAllTasks();
        $lastId = 0;
        if(!empty($tasks)){
            $ids = array_column($tasks, 'id');
            $lastId = max($ids);
        }
        $data['id'] = $lastId + 1;
        $tasks[] = $data;
        $json = json_encode($tasks, JSON_PRETTY_PRINT);
        if(file_put_contents($this->file, $json) === false){
            throw new Exception("Hubo un fallo al guardar la tarea.");
        }
    }

    public function getTaskById($id): ?array
    {
        $tasks = $this->getAllTasks();
        foreach ($tasks as $task) {
            if (isset($task['id']) && $task['id'] == $id) {
                return $task;
            }
        }
        return null;
    }

    public function getAllTasks(): array
    {
        $tasks = [];
        if (file_exists($this->file)) {
            $content = file_get_contents($this->file);
            $tasks = json_decode($content, true);
        }
        return $tasks;
    }

    public function updateTask(int $id, array $data): void
    {
        if(empty($data['titulo'])){
            throw new Exception("El título no puede estar vacío.");
        }
        if(empty($data['estado'])){
            throw new Exception("El estado no puede estar vacío.");
        }

        if(!isset($data['id']) || $data['id'] !== $id){
            throw new Exception("El ID no coincide con el ID de la tarea a actualizar.");
        } 

        if(file_exists($this->file)){
            $tasks = json_decode(file_get_contents($this->file), true) ?? [];
            foreach($tasks as &$task){
                if($task['id'] == $id){
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

    public function deleteTaskById($id): bool
    {
        $tasks = $this->getAllTasks();
        $encontro = false;

        foreach ($tasks as $i => $task) {
            if (isset($task['id']) && $task['id'] == $id) {
                unset($tasks[$i]);
                $encontro = true;
                break;
            }
        }
        if ($encontro) {
            $tasks = array_values($tasks);
            file_put_contents($this->file, json_encode($tasks, JSON_PRETTY_PRINT));
            return true;
        } else {
            return false;
        }
    }
}

    


?>        

