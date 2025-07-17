<?php
declare(strict_types=1);

interface PersistenceInterface{
    public function getAll($entity);
    public function getById($entity, $id);
    public function createTask($entity, $data);
    public function updateTask($entity, $id, $data);
    public function updateStatusTask($entity, $id, $status);
    public function deleteTask($entity, $id);
}
