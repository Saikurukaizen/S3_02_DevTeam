# Gestor de Tareas - Documentación Completa

## Resumen

Sistema de tareas en PHP con MVC, guarda en JSON y tiene interfaz web. Funciona tanto en XAMPP como en PHP Server. CRUD completo con interfaz unificada.

## Arquitectura

### MVC
- Modelos: `TaskModel.php` - maneja los datos en JSON
- Vistas: archivos PHTML con layouts
- Controladores: `TaskController.php` - toda la lógica

### Rutas
```php
// config/routes.php
'/' => 'task#read',                    // pantalla principal
'/task/create' => 'task#create',       // crear tarea
'/task/update' => 'task#update',       // editar tarea
'/task/detalle' => 'task#detalle',     // ver detalles
'/task/delete' => 'task#delete',       // borrar tarea
'/task/updateStatus' => 'task#updateStatus' // para el drag & drop
```

## Funcionalidades

### CRUD Completo

#### Crear Tarea
- Ruta: `/task/create`
- Método: `TaskController::createAction()`
- Vista: `app/views/scripts/task/create.phtml`
- Formulario POST con validación de campos obligatorios
- Redirección al tablero después de crear

#### Listar Tareas
- Ruta: `/` (principal)
- Método: `TaskController::readAction()`
- Vista: `app/views/scripts/task/read.phtml`
- Muestra tareas organizadas por estado en columnas tipo Trello
- Cada tarea es clickeable para ver detalles

#### Ver Detalles
- Ruta: `/task/detalle?id=X`
- Método: `TaskController::detalleAction()`
- Vista: `app/views/scripts/task/detalle.phtml`
- Campos de solo lectura (readonly/disabled)
- Botones: Editar, Excluir, Volver

#### Editar Tarea
- Ruta: `/task/update?id=X`
- Método: `TaskController::updateAction()`
- Vista: `app/views/scripts/task/update.phtml`
- Formulario pre-llenado con datos existentes
- Redirección a detalles después de actualizar

#### Borrar Tarea
- Ruta: `/task/delete`
- Método: `TaskController::deleteAction()`
- Modal de confirmación personalizado (sin "localhost diz")
- POST directo con redirección al tablero

### Interfaz Unificada

#### Diseño
- CSS: `web/stylesheets/layout.css`
- Fondo gradiente azul-púrpura
- Formularios centrados con mismo estilo
- Responsive design

#### Componentes
- Formularios con validación visual
- Botones diferenciados por acción:
  - Verde: crear/editar
  - Rojo: excluir
  - Gris: cancelar/volver
- Mensajes flash con auto-cierre
- Modal personalizado para confirmaciones

### Sistema Híbrido

#### Detección Automática
- Archivo: `config/environment.inc.php`
- Detecta automáticamente XAMPP vs PHP Server
- Ajusta URLs base según el entorno

#### Compatibilidad
- XAMPP: `localhost/fullstackphp-sprint3/S302/S3_02_DevTeam/web/`
- PHP Server: `localhost:8000/`
- JavaScript: URLs dinámicas con `buildUrl()` helper

### Persistencia

#### JSON
- Archivo: `config/tasks.json`
- Estructura: Array de objetos con id, título, descripción y estado
- CRUD completo con validación

#### Estados
- `pendiente` - por hacer
- `en_proceso` - en desarrollo  
- `hecho` - terminado

### Drag & Drop (Preparado)

#### Estructura HTML
- Tareas con `draggable="true"` y `data-task-id`
- Columnas con `ondrop` y `ondragover`
- JavaScript: `web/javascripts/drag_drop.js`

#### Funciones Disponibles
```javascript
allowDrop(ev)           // permite drop
drag(ev)               // empieza drag
dropUpdate(ev, status) // drop para cambiar estado
dropDelete(ev)         // drop para borrar
updateTask(id, status) // AJAX para actualizar
```

## Problemas Resueltos

### 1. Botón Update No Funcionaba
- **Problema**: Botón "Update" chamava função JS inexistente
- **Solução**: Mudado para navegação direta via URL
- **Arquivo**: `app/views/scripts/task/read.phtml`

### 2. Inconsistência de Idioma
- **Problema**: Sistema misturava português e espanhol
- **Solução**: Unificado todo para espanhol
- **Arquivos**: Todos os controllers, views e mensagens

### 3. Tela de Detalhes Inconsistente
- **Problema**: Tela de detalhes diferente das outras
- **Solução**: Reformulada para usar mesmo estilo do create/update
- **Arquivo**: `app/views/scripts/task/detalle.phtml`

### 4. Navegação Incorreta
- **Problema**: Botões cancelar e redirecionamentos incorretos
- **Solução**: Ajustados para voltar aos detalhes após editar
- **Arquivos**: Controllers e views de update/detalle

### 5. Confirmação de Exclusão
- **Problema**: "localhost diz" no modal de confirmação
- **Solução**: Modal personalizado sem dependência do navegador
- **Arquivo**: JavaScript customizado em detalle.phtml

### 6. Sistema Não Híbrido
- **Problema**: URLs fixas quebravam em diferentes ambientes
- **Solução**: Detecção automática de ambiente
- **Arquivo**: `config/environment.inc.php`

## Estructura de Archivos

```
S3_02_DevTeam/
├── app/
│   ├── controllers/
│   │   └── TaskController.php      # toda la lógica
│   ├── models/
│   │   └── TaskModel.php           # datos JSON
│   └── views/scripts/task/
│       ├── create.phtml            # crear
│       ├── read.phtml              # listar
│       ├── detalle.phtml           # ver detalles
│       ├── update.phtml            # editar
│       └── delete.phtml            # confirmar borrado
├── config/
│   ├── routes.php                  # rutas
│   ├── environment.inc.php         # detecta entorno
│   └── tasks.json                  # datos
├── web/
│   ├── stylesheets/
│   │   └── layout.css              # estilos
│   └── javascripts/
│       └── drag_drop.js            # drag & drop
└── lib/                            # framework (no tocamos)
```

## Flujo de Navegación

1. **Principal** (`/`) → lista tareas por estado
2. **Crear** (`/task/create`) → formulario nuevo
3. **Ver** (`/task/detalle?id=X`) → solo lectura
4. **Editar** (`/task/update?id=X`) → formulario con datos
5. **Borrar** → modal → borrado directo

## Características Técnicas

### Validación
- Título y estado obligatorios
- Validación frontend y backend
- Mensajes de error claros

### Seguridad
- Escape HTML en salidas
- Validación en controladores
- XSS básico prevenido