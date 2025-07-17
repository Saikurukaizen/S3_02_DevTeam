<?php
declare(strict_types=1);

class PersistenceFacade{
    private $strategy;

    public function __construct(PersistenceInterface $strategy){
        $this->strategy = $strategy;
    }

    public function getAll($entity){
        return $this->strategy->getAll($entity);
    }

    public function getById($entity, $id){
        return $this->strategy->getById($entity, $id);
    }

    public function createTask($entity, $data){
        return $this->strategy->createTask($entity, $data);
    }

    public function updateTask($entity, int $id, array $data): void{
        $this->strategy->updateTask($entity, $id, $data);
    }

    public function updateStatusTask($entity, $id, $status){
        return $this->strategy->updateStatusTask($entity, $id, $status);
    }

    public function deleteTask($entity, $id){
        return $this->strategy->deleteTask($entity, $id);
    }
}