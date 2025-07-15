## Read y Detalle - Sprint 3 S302

---

### Ruteo y flujo general

- La página principal del proyecto es `/web/`, donde se muestra el tablero con todas las tareas.
- Al hacer click en una tarea, se accede a `/task/detalle?id=XX` para ver los detalles de esa tarea.

---

### Board (read)

- El método `readAction` en el controlador busca todas las tareas y las pasa a la vista.
- En la vista `read.phtml`, las tareas se muestran como cards tipo Trello, organizadas por estado (pendiente, en_proceso, hecho).
- Cada card muestra solo el título y la descripción, y es clickeable para abrir el detalle de la tarea.

---

### Detalle de tarea

- El método `detalleAction` busca la tarea por id y la pasa a la vista de detalle.
- En la vista de detalle (`detalle.phtml`), se muestra el título, la descripción, el estado y los botones de update (U) y delete (D).  Los botones todavía no tienen funcionalidad, son solo para visualizar la interfaz.
- El CSS de la pantalla de detalle es temporal, solo para tener una idea visual y facilitar los próximos ajustes.
- Si la tarea no existe, se muestra un mensaje de error centrado.

---

### Decisiones y lógica (guía del flujo)

- Cuando entras en `/web/`, el router carga la acción `readAction` del `TaskController`.
- `readAction` pide todas las tareas al modelo y las pasa a la vista como `$this->view->tasks`.
- La vista `read.phtml` recorre ese array y arma los cards, cada uno con un link que apunta a `/task/detalle?id=ID`.
- Al hacer click en un card, el router llama a `detalleAction` en el controlador.
- `detalleAction` busca la tarea por id usando el modelo, y la pasa a la vista de detalle como `$this->view->task`.
- Antes de renderizar, desactiva el layout para que la pantalla de detalle salga sola, sin el tablero.
- La vista `detalle.phtml` agarra la tarea desde `$this->task`, muestra los datos y los botones (que por ahora son solo visuales).
- Si la tarea no existe, la vista muestra un mensaje de error y no sigue renderizando.