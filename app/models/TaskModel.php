<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/environment.inc.php';
require_once __DIR__ . '/../../lib/base/PersistenceFacade.php';
require_once __DIR__ . '/../../lib/base/JsonPersistence.php';
require_once __DIR__ . '/../../lib/base/SqlPersistence.php';
require_once __DIR__ . '/../../lib/base/MongoPersistence.php';

//Para persistencia en SQL, esta clase extiende del modelo principal
class TaskModel{

    private $persistance;
    private $file = __DIR__ . '/../../data/FakeTasks.json';

    public function __construct(){
        switch(PERSISTENCE_TYPE){
            case 'json':
                $this->persistance = new PersistenceFacade(new JsonPersistence());
                break;
            case 'sql':
                $this->persistance = new PersistenceFacade(new SqlPersistence());
                break;
            case 'mongo':
                $this->persistance = new PersistenceFacade(new MongoPersistence());
            break;
        }
    }

    public function createTask($entity, array $data): void {
        foreach($data as $key => $value){
            if(empty($value)){
                throw new Exception("El campo $key no puede estar vacío.");
            }
        }
        $this->persistance->createTask($entity, $data);
    }

    public function updateTask($entity, int $id, array $data): void {
        foreach($data as $key => $value){
            if(empty($value)){
                throw new Exception("Este campo $key no debe estar vacío!");
            }
        }
        if(!isset($data['id']) || $data['id'] !== $id){
            throw new Exception("El ID no coincide con el ID de la tarea a actualizar.");
        }
        $this->persistance->updateTask($entity, $id, $data);
    }

    public function updateStatusTask($entity, int $id, string $newStatus): void {
        if(empty($newStatus)){
            throw new Exception("El estado no puede estar vacío");
        }
        $this->persistance->updateStatusTask($entity, $id, $newStatus);
    }
}

?>        