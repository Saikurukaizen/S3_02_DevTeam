drag(ev)               // empieza drag
dropUpdate(ev, status) // drop para cambiar estado
dropDelete(ev)         // drop para borrar
updateTask(id, status) // AJAX para actualizar

# Documentación Técnica - Gestor de Tareas Kanban PHP

El sistema implementa una aplicación web para gestión de tareas utilizando el patrón MVC en PHP. La persistencia de datos se realiza mediante archivos JSON. La interfaz es responsiva y compatible con entornos XAMPP y PHP Server.


## Arquitectura y Estructura

El sistema está dividido en tres capas principales:

- Controladores: Gestionan la lógica de negocio y las acciones del usuario. El controlador principal, `TaskController.php`, ubicado en `app/controllers/`, implementa los métodos para cada operación sobre las tareas. Este archivo recibe las solicitudes HTTP, valida los datos, interactúa con el modelo y selecciona la vista adecuada. Cada método corresponde a una acción específica: creación, lectura, actualización, eliminación y visualización de detalles. El controlador también gestiona la navegación y el flujo entre las vistas, asegurando que los datos sean procesados correctamente y que las respuestas sean coherentes con el estado del sistema.

- Modelos: Encapsulan el acceso y manipulación de los datos. El modelo de tareas, `TaskModel.php`, ubicado en `app/models/`, es responsable de la persistencia y recuperación de los datos de las tareas. Opera sobre el archivo `config/tasks.json`, realizando operaciones de lectura, escritura, actualización y eliminación de registros. El modelo implementa validaciones de integridad, garantiza la estructura de los datos y expone métodos para que el controlador pueda interactuar con el almacenamiento sin acceder directamente al archivo JSON.

- Vistas: Presentan la información al usuario. Se utilizan archivos `.phtml` en `app/views/scripts/task/` para renderizar los formularios, listados y detalles de las tareas. Los layouts en `app/views/layouts/` proporcionan la estructura visual común a todas las páginas. Las vistas reciben los datos procesados por el controlador y los muestran de forma segura, aplicando escape de caracteres y estilos definidos en las hojas de estilo.

Las rutas del sistema se definen en `config/routes.php`. Este archivo asocia cada URL del sistema a una acción específica de un controlador. Por ejemplo, la ruta `/task/create` está vinculada al método de creación de tareas en el controlador, mientras que `/task/update` corresponde a la edición. El archivo de rutas permite centralizar la configuración de navegación y facilita la extensión del sistema, ya que nuevas funcionalidades pueden ser agregadas simplemente definiendo nuevas rutas y métodos en los controladores.

## Flujo de Operaciones

Al acceder a la aplicación, el sistema inicializa el entorno y configura las rutas base según el entorno detectado (XAMPP o PHP Server). Se inicia la sesión del usuario y se registra el autocargador de clases para facilitar la carga dinámica de controladores y modelos.

El usuario puede realizar las siguientes operaciones:

- Listar tareas: La ruta principal carga el tablero Kanban, mostrando las tareas agrupadas por estado. Cada tarea es interactiva y permite acceder a sus detalles.
- Crear tarea: El formulario de creación valida los campos obligatorios y almacena la nueva tarea en el archivo JSON. Tras la creación, se redirige al tablero.
- Ver detalles: La vista de detalles muestra los datos de la tarea en modo solo lectura. Se presentan opciones para editar o eliminar la tarea.
- Editar tarea: El formulario de edición carga los datos existentes, permite modificarlos y actualiza el archivo JSON. Tras la edición, se redirige a la vista de detalles.
- Eliminar tarea: La acción de borrado utiliza un modal de confirmación personalizado y realiza la eliminación mediante POST, actualizando el archivo JSON y redirigiendo al tablero.

## Persistencia y Validación

Los datos de las tareas se almacenan en `config/tasks.json` como un array de objetos. Cada objeto contiene identificador, título, descripción, estado y responsable. Todas las operaciones de CRUD validan los datos tanto en el frontend como en el backend. Los campos obligatorios son el título y el estado.

La validación incluye escape de caracteres especiales en las salidas para prevenir vulnerabilidades XSS. Los controladores verifican la integridad de los datos antes de cualquier operación de escritura.

## Estados de las Tareas

El sistema define los siguientes estados para las tareas:

- pendiente: tarea por realizar
- en_progreso: tarea en desarrollo
- completada: tarea finalizada
- cancelada: tarea anulada

## Interfaz y Componentes

La interfaz utiliza CSS personalizado (`web/stylesheets/layout.css`) para mantener un diseño uniforme y responsivo. Los formularios están centrados y presentan validación visual. Los botones se diferencian por color según la acción: verde para crear/editar, rojo para eliminar, amarillo para editar en modo lectura y gris para cancelar o volver.

El sistema implementa mensajes flash para notificaciones y utiliza modales personalizados para confirmaciones de acciones críticas, evitando el uso de diálogos nativos del navegador.

## Compatibilidad y Entorno

El archivo `config/environment.inc.php` detecta automáticamente el entorno de ejecución y ajusta las URLs base para asegurar compatibilidad entre XAMPP y PHP Server. El helper JavaScript `buildUrl()` construye URLs absolutas dinámicamente según el entorno detectado.

## Seguridad

Todas las salidas HTML utilizan funciones de escape para evitar inyección de código. Los controladores validan los datos recibidos y rechazan entradas inválidas. El sistema previene ataques XSS básicos mediante validación y sanitización de los datos.

## Estructura de Archivos

El proyecto está organizado en los siguientes directorios:

app/controllers/: controladores principales
app/models/: modelos de datos
app/views/scripts/task/: vistas y formularios de tareas
app/views/layouts/: layouts generales
config/: archivos de configuración, rutas y datos
web/stylesheets/: hojas de estilo
web/javascripts/: scripts de interacción y utilidades
lib/: clases base y framework

## Sistema de Detección Automática de Entorno (environment.inc.php)

El archivo `config/environment.inc.php` implementa un sistema de detección automática del entorno de ejecución. Su objetivo es identificar el tipo de servidor, el puerto, la URL base y el contexto (local o producción) sin requerir configuraciones manuales ni depender de la estructura de carpetas.

La clase `Environment` analiza las variables globales `$_SERVER` disponibles en cualquier instalación PHP. Detecta el software del servidor (Apache, Nginx, IIS, PHP Built-in Server, entre otros) y determina si la aplicación se ejecuta en un entorno local o remoto. El sistema ajusta la URL base considerando el protocolo, el host, el puerto y el path del proyecto, permitiendo que todas las rutas y enlaces funcionen correctamente en cualquier ambiente.

El método `detect()` se ejecuta automáticamente al cargar el archivo, estableciendo las propiedades estáticas de la clase. Estas propiedades se utilizan para construir URLs absolutas, identificar el tipo de servidor y adaptar el comportamiento de la aplicación según el entorno. El helper `Environment::url()` permite generar rutas completas para cualquier recurso o acción, y el helper `buildUrl()` en JavaScript utiliza la variable global `BASE_URL` para mantener la compatibilidad en el frontend.

El sistema previene problemas comunes de despliegue, como rutas rotas, diferencias de puertos y cambios de dominio, asegurando que la aplicación sea portable y funcional en cualquier servidor PHP, contenedor Docker, hosting compartido o entorno de desarrollo local.