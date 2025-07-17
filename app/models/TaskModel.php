<?php
    declare(strict_types=1);

    // Para persistencia en SQL, esta clase extiende del modelo principal
    class TaskModel{
        // Archivo JSON que usaremos para guardar y leer las tareas
        private $file;

        // Constructor que define la ruta del archivo usando ROOT_PATH
        public function __construct()
        {
            // Usamos ROOT_PATH para mayor flexibilidad
            $this->file = ROOT_PATH . '/config/fakeTasks.json';
        }

        // --- GET ALL TASKS ---
        // Metodo que lee todas las tareas guardadas en el archivo JSON
        // Abre el archivo definido en $this->file
        // Si existe, lo lee y decodifica el contenido JSON a un array PHP
        // Si no existe o esta vacio, devuelve un array vacio
        public function getAllTasks() {
            $tasks = []; // array por defecto vacio
            if (file_exists($this->file)) { // verifica si el archivo existe
                $content = file_get_contents($this->file); // lee contenido del archivo
                $tasks = json_decode($content, true); // convierte JSON a array
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
            $tasks = $this->getAllTasks();

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

        // --- GET BY ID ---
        // Metodo para obtener una tarea por su id
        // Busca en el array de tareas y devuelve la tarea que coincide con el id
        public function getTaskById($id) {
            // Lee todas las tareas del archivo JSON
            $tasks = $this->getAllTasks();
            // Recorre el array de tareas
            foreach ($tasks as $task) {
                // Si el id coincide, retorna la tarea
                if (isset($task['id']) && $task['id'] == $id) {
                    return $task;
                }
            }
            // Si no encuentra la tarea, retorna null
            return null;
        }

        // --- DELETE BY ID ---
        // Metodo para eliminar una tarea por su id
        // Lee todas las tareas, busca la que coincide con el id, la borra y guarda el nuevo array en el JSON
        // Devuelve true si borro, false si no encontro el id
        public function deleteTaskById($id) {
            // Lee todas las tareas
            $tasks = $this->getAllTasks();
            $encontro = false; // bandera para saber si borro
            // Recorre el array y busca la tarea por id
            foreach ($tasks as $i => $task) {
                if (isset($task['id']) && $task['id'] == $id) {
                    // Si encuentra, la elimina del array
                    unset($tasks[$i]);
                    $encontro = true;
                    break;
                }
            }
            if ($encontro) {
                // Reindexa el array para que no queden huecos
                $tasks = array_values($tasks);
                // Guarda el array actualizado en el archivo JSON
                file_put_contents($this->file, json_encode($tasks, JSON_PRETTY_PRINT));
                return true; // borro bien
            } else {
                return false; // no encontro la tarea
            }
        }
    }

?>