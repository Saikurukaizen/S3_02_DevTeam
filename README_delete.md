# DELETE - Sprint 3

## Descripción general

Esta feature implementa el flujo completo para eliminar tareas del sistema. El borrado se puede ejecutar de dos formas:
1. Vía tradicional con confirmación (GET + POST).
2. Vía Drag & Drop o desde la vista de detalle usando `fetch()` con petición AJAX en formato JSON.

El flujo asegura compatibilidad con ambos métodos y mantiene la persistencia de los datos en `fakeTasks.json`.

---

## Rutas

- `GET /task/delete?id=X` Muestra la vista de confirmación de borrado para la tarea con ID específico.
- `POST /task/delete` Ejecuta la eliminación de la tarea en el archivo JSON (puede ser por formulario o AJAX).

---

## Controlador

La lógica principal está en `TaskController.php`. El método `deleteAction()` detecta el tipo de petición:
- Si es `POST` con JSON (AJAX), responde en JSON.
- Si es `POST` tradicional (formulario), redirige tras eliminar.
- Si es `GET`, busca la tarea por ID y pasa los datos a la vista `delete.phtml` para confirmar.

Ejemplo de método unificado:

```php
public function deleteAction() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['taskId'] ?? $this->_getParam('id');
        if ($id) {
            $taskModel = new TaskModel();
            $borrado = $taskModel->deleteTaskById($id);
            if ($data) {
                // AJAX: responde JSON
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => $borrado,
                    'message' => $borrado ? 'Tarea eliminada correctamente.' : 'No se pudo eliminar la tarea.'
                ]);
                exit;
            }
            // POST tradicional: redirige
            header('Location: /fullstackphp-sprint3/S302/S3_02_DevTeam/web/task/read');
            exit;
        } else {
            if ($data) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'ID inválido']);
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

La vista `delete.phtml` muestra un mensaje de confirmación antes de borrar la tarea. Si la tarea existe, se muestran los detalles y un formulario con botón para confirmar la eliminación. Si no existe, se muestra un mensaje de error.

---

## Modelo

La lógica de eliminación se encuentra en `TaskModel.php`. El método `deleteTaskById($id)` busca la tarea por ID, la elimina del array y guarda el JSON actualizado. Devuelve true si borra, false si no encuentra la tarea.

---

## Decisiones y UX

- El botón de eliminar está disponible en la pantalla de detalle de la tarea (`/task/detalle?id=XX`).
- Al hacer clic en el botón, se muestra una confirmación para evitar eliminaciones accidentales.
- Si el usuario confirma, se envía una petición AJAX (POST, JSON) a `/task/delete`.
- El backend responde en JSON si la petición es AJAX, o redirige si es formulario.
- Si la respuesta es exitosa, el frontend redirige al tablero principal. Si hay error, muestra un mensaje.
- El archivo `fakeTasks.json` sirve como base de datos mock para pruebas.

---

## PS

- El flujo admite confirmación tradicional con formulario o borrado instantáneo vía Drag & Drop o AJAX.
- La respuesta del backend devuelve JSON si la petición viene desde `fetch()`.
- El `read.phtml` incluye los atributos `id` únicos para soportar el Drag & Drop y la vista de detalle para eliminar.
