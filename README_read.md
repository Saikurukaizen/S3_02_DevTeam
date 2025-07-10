## Read - Sprint 3 S302

### Ruteo

Se añadió en `routes.php`:

  ```php
  '/task/read' => 'task#read',
  ```

Este mapeo accede a `/task/read` el Router ejecuta `TaskController::readAction()`

---

### Controlador

Método `readAction()` creado en `TaskController`:

  ```php
  public function readAction() {
      $taskModel = new TaskModel();
      $tasks = $taskModel->getAllTasks();
      $this->view->tasks = $tasks;
  }
  ```

El método instancia `TaskModel`.
Ejecuta `getAllTasks()` para obtener todas las tareas.
Asigna el resultado a `$this->view->tasks` para usar en la vista.
No llama `$this->view->render()` directamente, el render se maneja en el core/layout para evitar duplicados.

---

### ApplicationController

Se agregó la propiedad:

  ```php
  public $view = null;
  ```
Inicializada dentro de `init()`:

  ```php
  $this->view = new View();
  ```

  Así todos los controladores hijos (`TaskController`) heredan `$this->view` listo para renderizar.

---

### TaskModel

Configurado con persistencia en archivo JSON:

  ```php
  private $file = __DIR__ . '/../../config/fakeTests.json';
  ```

`__DIR__` garantiza ruta absoluta para evitar errores de localización.

Método `getAllTasks()`:

  ```php
  public function getAllTasks() {
      $tasks = [];
      if (file_exists($this->file)) {
          $content = file_get_contents($this->file);
          $tasks = json_decode($content, true);
      }
      return $tasks;
  }
  ```

Lee el archivo JSON, decodifica y devuelve un array.
Ruta ajustada para entorno local.

---

### Views

Archivo `read.phtml` ubicado en:
  ```
  app/views/scripts/task/read.phtml
  ```

Estructura:

    ```php
    <ul>
    <?php foreach ($this->tasks as $task): ?>
        <li>
            <strong>ID:</strong> <?= htmlspecialchars($task['id']) ?><br>
            <strong>Titulo:</strong> <?= htmlspecialchars($task['titulo']) ?><br>
            <strong>Descripcion:</strong> <?= htmlspecialchars($task['descripcion']) ?><br>
            <strong>Estado:</strong> <?= htmlspecialchars($task['estado']) ?>
            <hr>
        </li>
    <?php endforeach; ?>
    </ul>
    ```
---

### PS

`render()` no se invoca en `TaskController` para evitar contenido duplicado.
`fakeTests.json` se usa como mock de prueba; en producción se cambiará a `tasks.json`.
---
