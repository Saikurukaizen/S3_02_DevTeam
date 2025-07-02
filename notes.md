## Anotaciones del framework

Este documento contiene las anotaciones sobre el viaje del envío de datos y requests del MVC, desde el envío de método
del formulario hasta el almacenamiento en la persistencia de db. Es una serie de apuntes del cual me he basado en la observación y análisis, junto con soporte IA para seguir bien el hilo.

### index.php, el primer punto de entrada.

- Los formularios con la lógica de botones, inputs, etc deben estar en los archivos de vista específicos en una carpeta /task del cual ahora explicaré.

- Los filtros de entrada y salida de GET y POST lo realiza el *request.php*

- Una vez que un formulario .phtml, hace el envío a través del método POST, lo recibe el **index.php**. Actualiza el autoloader, incluyendo las rutas ,y las acciones son ejecutadas por el Controlador si las rutas del Router en **routes.php** y del autoloader son correctos.

- El autoloader sólo carga archivos .php de clases, si existen. Por lo que hay que crear manualmente:
    . Asignación de rutas en el array de routes.php
    . El archivo del controlador (TaskController.php)
    . Una carpeta Task dentro de **app/views/scripts/**
    . El archivo de vista ej create.phtml dentro de /task
    . Como tal, un TaskModel que conectará con la db
    . La URL task/create ejecuta TaskController::createAction().
    . Éste renderiza la vista

    ```
    app/views/scripts/task/create.phtml
    (si así lo indica el controlador)

    ```

- Los scripts y stylesheets se imprimirán en la plantilla general **layout.phtml**, tal y como describe el README.md

### router.php. El ejecutor

- El **router.php** analiza la URL y los parámetros (los datos de la petición), decide qué controlador y acción ejecutar usando el array $routes e instancia el controlador adecuado(este comportamiento lo adquiere el método getUri()). Esta variable es un array en enrutamiento de la aplicación, siendo key = ruta, y value = string, con el formato controlador#accion. Ésta es la función del método execute(). Realiza también filtros de excepción de errores, si no encuentra con coincidencia simple (_getSimpleRoute()) o con parámetros (getParameterRoute()).
Si no encuentra la ruta o hay un error, generará un ErrorController y ejecuta su accion de error

Esto significa que, si la URL es p.e /test, el Router debe de ejecutar el método index() del controlador TestController. Cuando encuentra la ruta, separa el string(p.e: test#index) en:
    . test -> nombre del controlador (TestController)
    . index -> nombre de la acción (método index)

_initializeController($name) se usa para crear una instancia del controlador adecuado.

_getUri es para limpiar la URI del URL ara buscar en el array $routes para decidir que controlador y acción ejecutar

REMINDER: El autoloader y el router sólo funcionan si la estructura y los nombres son correctos.

- En este punto ya ha instanciado nuevos controladores y ejecuta la creación de acciones.

CONCLUSIÓN: Los controladores se crean dinámicamente pero sólo si existe el archivo y la clase con el nombre correcto. El autoloader y el router permiten que, con sólo crear TaskController.php y definir la clase y sus métodos *Action() todo funcione automáticamente. Al menos, se resuelve de forma automática si se sigue la convención.

### ApplicationControllers.php Cuando tu padre te explota en casa.

Los controladores que se vayan a crear con dichas acciones (TaskController, TestController, ErrorController) van a extenderse del ApplicationController, que éste se extiende del controlador principal almacenado en /lib/base

#### DISCLAIMER

- La carpeta lib/base no se debe tocar, ya que es el corazón del framework. Todas las funcionalidades y mecanizaciones están ahí, y no debe ser modificado!

Voliendo a los controladores:

- ApplicationController extiende del controlador principal. Ahí hay que crear, un init() para inicialización general, y un loadModel() para instanciar modelos y que los controladores hijos puedan reutilizarla.

- Los controladores hijos, como TestController, o TaskController extiende del ApplicationController, que tendrá un indexAction() y un checkAction(). Se tiene que crear manualmente, para que el router ejecute los métodos *Action(), si existen en estos controladores.

- El controlador puede asignar mensajes o resultados a la vista usando $this->view->mensaje = "Tarea creada";

RESUMEN: En el controlador:
    . Se accede a los datos enviados por POST USANDO $this->getRequest()->getParam('nombre_campo').
    . Se instancia el modelo correspondiente (p.e:TaskModel).
    . Se llama al método del modelo para guardar los datos, p.e: $taskModel->save($data)
    . Se decide qué vista mostrar o si redirigir.

### Modelos para persistencias

- En la carpeta /models almacenamos nuestro modelo, siguiendo la convención ModelName.class.php. Se cargará automáticamente usando el Autoload en el archivo app/**router.php**.


- Se crearía, p.e un Task Model que tuviera una propiedad $file que sea la ruta a un tasks.json para enlazar la persistencia a un formato JSON.

- Sugerencia de comportamiento:
    . createTask()
    . getAllTask()
    . getTaskId()
    . updateTask()
    . deleteTask()
    . getNextId()

- NOTA: Cuando guardas datos en JSON (o una db sin A.I), necesitas asignar una id a una nueva tarea. Con este metodo getNextId() busca el método más alto que ya existe y suma 1 para asegurarte de cada nueva tarea tenga un nuevo id, y no haya duplicidades. Ej de JSON:

```code

[
    {
        "id": ,
        "titulo": "titulo de tarea",
        "descripcion" : "descripción de la tarea",
        "estado" : "pendiente, o en_ejecucion, o completado"
    }
]

```

- Hay que mencionar que, como son datos de un JSON, hay que usar métodos tipo file_get_contents(), file_put_contents(),json_encode() / json_decode() para la persistencia en JSON

**IMPORTANTE**

El model.php (el modelo principal) **NO** está diseñado para hacer la persistencia en JSON y, por lo tanto, TaskModel debería extender del Model principal para que funcione. Si la persistencia se hace en JSON, **no debería extenderse**

***explicando el view.php***

- _renderViewScript inicializa la vista e incluye el directorio
- Si el layout está habilitado, incluye el layout general que a su vez, puede llamar a $this->content() para mostrar el contenido de la vista. Si el layout está deshabilitado, imprime sólo el contenido de la vista.
- ob_start() captura la salida del archivo y la guarda en $_content.
- content() devuelve el contenido renderizado.
- Renderizado JSON: Desactiva la vista y el layout, y responde con el $data en formato JSON (para APIs)
- Layouts con sus getters/setters. Pueden desahibilitar las vistas y layout(disableLayout/disableView) para respuestas AJAX o JSON.
- Datos para la vista: __set($key, $value) y __get($key) permiten recuperar variables dinámicamente en la vista. p.e: $this->view->usuario = $usuario;
- appendScript($script) para añadir scripts JS y printScripts() para imprimir dichos scripts.
- Utilidades
- baseUrl() devuelve la raiz de la aplicación para generar enlaces correctamente

RESUMEN:
- Elcontrolador asigna datos a la vista y llama a $this->view->render($viewScript).
- Se renderiza la vista específica y se guarda el contenido.
- Se incluye el layout general, que puede mostrar el contenido de la vista y otros elementos comunes (header, menu, footer, etc)

Con todo lo explicado, hay dos formas de abordarlo:
    . Empezando desde las vistas y su recorrido
    . Empezar con la persistencia

En todo caso, hay que dejar definidas las rutas para empezar a picar código.

*-Marc Sanchez-*





