<?php
    declare(strict_types=1);

    //Para persistencia en SQL, esta clase extiende del modelo principal
    class TaskModel{
        // Archivo JSON que usaremos para guardar y leer las tareas
        private $file;

        // Constructor que define la ruta del archivo usando ROOT_PATH
        public function __construct()
        {
            // Usamos ROOT_PATH para mayor flexibilidad
            $this->file = ROOT_PATH . '/config/fakeTasks.json';
        }

        // Metodo para obtener todas las tareas (usado en la version dev)
        public function getAll(): array
        {
            // Lee todas las tareas del archivo JSON y devuelve un array
            if(file_exists($this->file)){
                $json = file_get_contents($this->file);
                $tasks = json_decode($json, true);
                return $tasks ?? [];
            }
            return [];
        }

        // Metodo alternativo para obtener todas las tareas (usado en feature/read)
        public function getAllTasks() {
            // Lee todas las tareas del archivo JSON y devuelve un array
            $tasks = [];
            if (file_exists($this->file)) {
                $content = file_get_contents($this->file);
                $tasks = json_decode($content, true);
            }
            return $tasks;
        }

        // Metodo para crear una nueva tarea y guardarla en el archivo JSON
        public function createTask(array $data): void
        {
            foreach($data as $key => $value){
                if(empty($value)){
                    throw new Exception("El campo $key no puede estar vacio.");
                }
            }
            // Leer el archivo JSON
            $tasks = [];
            if(file_exists($this->file)){
                $json = file_get_contents($this->file);
                $tasks = json_decode($json, true) ?? [];
            }

            $lastId = 0;
            if(!empty($tasks)){
                $ids = array_column($tasks, 'id');
                $lastId = max($ids);
            }
            $data['id'] = $lastId + 1; // Asigna un nuevo ID a la tarea

            $tasks[] = $data; // Agrega la nueva tarea al array
            $json = json_encode($tasks, JSON_PRETTY_PRINT);
            if(file_put_contents($this->file, $json) === false){
                throw new Exception("Hubo un fallo al guardar la tarea.");
            }
        }

        // Metodo para obtener una tarea por su id
        public function getTaskById($id) {
            $tasks = $this->getAllTasks();
            foreach ($tasks as $task) {
                if (isset($task['id']) && $task['id'] == $id) {
                    return $task;
                }
            }
            return null;
        }

    }

?>