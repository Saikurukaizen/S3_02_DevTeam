
# DELETE - Sprint 3

## Descripción

Esta feature implementa el flujo completo para eliminar tareas del sistema.  
El borrado se puede ejecutar de dos formas:  
1. Vía tradicional con confirmación (GET + POST).  
2. Vía Drag & Drop usando `fetch()` con petición AJAX en formato JSON.

El flujo asegura compatibilidad con ambos métodos y mantiene la persistencia de los datos en `fakeTasks.json`.

---

## Rutas

- `GET /task/delete?id=X` Muestra la vista de confirmación de borrado para la tarea con ID específico.  
- `POST /task/delete` Ejecuta la eliminación de la tarea en el archivo JSON.

---

## Controlador

La lógica principal está en `TaskController.php`.  
El método `deleteAction()` maneja la detección de la petición:  
- Si es `POST`, ejecuta `deleteTask()` en el modelo y responde con redirección o JSON (si viene de Drag & Drop).
- Si es `GET`, busca la tarea por ID y pasa los datos a la vista `delete.phtml` para confirmar.


```php
public function deleteAction(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['taskId'] ?? $this->_getParam('id');

        if ($id) {
            $taskModel = new TaskModel();
            $taskModel->deleteTask($id);

            if ($data) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            }

            header('Location: /fullstackphp-sprint3/S302/S3_02_DevTeam/web/task/read');
            exit;
        } else {
            if ($data) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'ID inválido']);
                exit;
            } else {
                echo "ID inválido"; die;
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $id = $this->_getParam('id');
        $taskModel = new TaskModel();
        $tasks = $taskModel->getAllTasks();

        $task = null;
        foreach ($tasks as $t) {
            if ($t['id'] == $id) {
                $task = $t;
                break;
            }
        }
        $this->view->task = $task;
    } else {
        echo "Método no permitido."; die;
    }
}
```

---

## Vista

La vista `delete.phtml` muestra un mensaje de confirmación antes de borrar la tarea.  
Si la tarea existe, se muestran los detalles y un formulario con botón para confirmar la eliminación.  
Si no existe, se muestra un mensaje de error.

```php
<h2>¿Seguro que quieres eliminar esta tarea?</h2>

<?php if ($this->task): ?>
    <p><strong>ID:</strong> <?= htmlspecialchars($this->task['id']) ?></p>
    <p><strong>Título:</strong> <?= htmlspecialchars($this->task['titulo']) ?></p>
    <p><strong>Descripción:</strong> <?= htmlspecialchars($this->task['descripcion']) ?></p>

    <form method="POST" action="/fullstackphp-sprint3/S302/S3_02_DevTeam/web/task/delete">
        <input type="hidden" name="id" value="<?= htmlspecialchars($this->task['id']) ?>">
        <button type="submit">Sí, eliminar tarea</button>
    </form>
<?php else: ?>
    <p>No se encontró la tarea solicitada.</p>
<?php endif; ?>

<p>
    <a href="/fullstackphp-sprint3/S302/S3_02_DevTeam/web/task/read">Cancelar y volver</a>
</p>
```

---

## Modelo

La lógica de eliminación se encuentra en `TaskModel.php`.  
El método `deleteTask($id)` filtra el arreglo de tareas para remover la tarea con ID específico y guarda el JSON actualizado.

```php
public function deleteTask($id)
{
    if (file_exists($this->file)) {
        $content = file_get_contents($this->file);
        $tasks = json_decode($content, true);

        $tasks = array_filter($tasks, function ($task) use ($id) {
            return $task['id'] != $id;
        });

        $tasks = array_values($tasks);
        file_put_contents($this->file, json_encode($tasks, JSON_PRETTY_PRINT));
    }
}
```

---

## PS

- El flujo admite confirmación tradicional con formulario o borrado instantáneo vía Drag & Drop.
- La respuesta del backend devuelve JSON si la petición viene desde `fetch()`.
- El archivo `fakeTasks.json` sirve como base de datos mock para pruebas.
- El `read.phtml` incluye los enlaces `Eliminar` y atributos `id` únicos para soportar el Drag & Drop.
