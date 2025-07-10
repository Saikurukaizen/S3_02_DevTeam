<?php
    declare(strict_types=1);

    //Para persistencia en SQL, esta clase extiende del modelo principal
    class TaskModel{
        // Archivo JSON que usaremos para guardar y leer las tareas
        //protected $file = "tasks.json";
        //private $file = 'config/fakeTasks.json';
        private $file = __DIR__ . '/../../config/fakeTests.json';

        /* public function crearTarea(array $data): void
        {
            Lógica para leer el JSON, crearTarea y guardar el archivo
        } */

        /* public function updateStatusTask(int $id, string $newStatus): void
        {

        } */
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
            return $tasks; // devuelve el array de tareas
        }

    }

?>