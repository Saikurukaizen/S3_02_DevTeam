# Proyecto To-Do List — Modelo Entidad-Relación (MER)

## Objetivo del MER

- Definir qué entidades existen en la aplicación.
- Especificar qué atributos tiene cada entidad.
- Determinar qué relaciones existen entre las entidades.
- Garantizar la integridad referencial mediante claves primarias y foráneas.
- Dejar preparado el modelo para dividir tareas de desarrollo (CRUD).

---

## Estructura del MER

El diseño mínimo funcional para una aplicación To-Do List incluye 2 entidades principales:

1. User (Usuario)
2. Task (Tarea)

Decisión:  
El alcance mínimo asegura que el usuario pueda gestionar sus propias tareas. No se implementan categorías ni tablas intermedias en este nivel para mantener el enfoque alineado al enunciado.

---

## Entidad `User`

Atributos:  
- `id`: Número entero, clave primaria, autoincremental.
- `name`: Cadena de texto, máximo 100 caracteres, obligatorio.
- `email`: Cadena de texto, máximo 100 caracteres, obligatorio y único.
- `password`: Cadena de texto, máximo 255 caracteres, obligatorio.
- `created`: Fecha y hora de creación del registro, obligatorio.

Decisión:  
- Se establece `email` como único para evitar duplicados.  
- Todos los campos clave son obligatorios para garantizar consistencia.  
- El campo `created` permite trazar cuándo se crea cada usuario.

---

## Entidad `Task`

Atributos:  
- `id`: Número entero, clave primaria, autoincremental.
- `user_id`: Número entero, clave foránea que apunta a `User.id`, obligatorio.
- `title`: Cadena de texto, máximo 255 caracteres, obligatorio.
- `description`: Texto largo, opcional.
- `status`: Valor restringido a 'pending', 'in_progress' o 'done', obligatorio.
- `due_date`: Fecha límite, opcional.
- `created`: Fecha y hora de creación de la tarea, obligatorio.

Decisión:  
- `user_id` define la relación obligatoria entre tarea y usuario.  
- `status` limita los estados posibles, facilitando la validación y la lógica de negocio.  
- `description` y `due_date` dan flexibilidad, pero no son obligatorios para registrar la tarea.

---

## Relación entre entidades

- Un usuario puede tener varias tareas (relación 1:N).
- Cada tarea pertenece a un único usuario.
- La clave foránea `user_id` en `Task` tiene:
  - ON DELETE CASCADE: al eliminar un usuario, se eliminan sus tareas.
  - ON UPDATE CASCADE: si se actualiza el `id` del usuario, se actualiza en cascada en `Task`.

---

## Persistencia

- Base de datos real: el script `schema.sql` permite crear las tablas en un servidor MySQL.
- Persistencia simulada: si se usa archivo JSON, este MER define la estructura que se replicará como objetos JSON. En esta fase, la persistencia real se realizará mediante un archivo JSON que reflejará la estructura definida en el MER. Cada entidad (User, Task) se gestionará como colección de objetos dentro del archivo.