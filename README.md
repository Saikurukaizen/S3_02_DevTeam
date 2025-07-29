# PHP Task Manager

## 👤Bootcamp Coleagues
Developed by:
  . Marc Sanchez
  . Leandro Da Silva

## 📄 Project Review
We're created this Trello's style Task Manager, using a PHP main structure called
*Php Initial Project* [Github Repository Link](https://github.com/IT-Academy-BCN/phpInitialDemo)

We made such functionalities as CRUD actions/methods, JavaScript's Drag & Drop scripts using AJAX, automatic
server detections, facade database persistence and so on.

## 🎯 Keywords
. CRUD
. MVC
. GET/POST requests
. Drag & Drop
. Developer Team
. Gitflow
. features & branches
. Terminal Command Lines

## 🛠️ Stack Tech
- PHP Server & XAMPP for server selections
- PHP 8.3 for Backend
- TailwindCSS v4.0 for Web Design
- JavaScript for extra functions
- Gitflow as Roadmap Methodology
- LucidChart for use's cases & design roadmap
- IDE: Visual Studio Code
- Git & GitHub

## 📋 Requisitos
- Local Server for PHP compilation. You can use XAMPP or initialize an PHP Server using the next command-line argument:

```bash

php -S localhost:8000

```

## 🛠️ How to Install
- Open your terminal command-line in a empyu project and clone the repository:

```bash

git clone https://github.com/Saikurukaizen/S3_02_DevTeam

```

- Start your server and open your internet browser. You can write in URL sidebar an:
. localhost/8000/web

Adding the /web the router will detect the project's renderized main view.


## ▶️ Execution
- Once the project has been installed, you can observer the structure

## 🔀 Branches

- 
Each feature branch was created to develop and test each part of the CRUD, database integration, styles, and bug fixes in isolation before merging them into the main *dev* branch.

```text

dev                 # Main Developer Branch
├── feature/create  
├── feature/read    
├── feature/update 
├── feature/delete  
├── feature/db      # database persistence
├── feature/styles  # Styles and UI / UX
└── feature/bugfix  # Fixing bugs in the final process

```

### Folder Structure

```text

phpInitialDemo-main/                
├── README.md                       
├── app/                                  # MVC Logic App
│   ├── controllers/               
│   │   ├── ApplicationController.php   
│   │   ├── ErrorController.php        
│   │   ├── TaskController.php            # CRUD Task Controller
│   │   ├── TestController.php    
│   │   └── UserController.php      
│   ├── models/                       
│   │   ├── TaskModel.php                 # (JSON)
│   │   └── UserModel.php              
│   └── views/                      
│       ├── layouts/                      # General Layouts
│       │   ├── form.phtml                # Shared Form
│       │   ├── layout.phtml              # Base View Structure
│       │   └── update.phtml          
│       └── scripts/                    
│           ├── error/
│           │   └── error.phtml        
│           ├── test/
│           │   ├── form.phtml            # Test Form
│           │   └── index.phtml           # Test List
│           └── task/
│               ├── _form.phtml           # Task Form
│               ├── create.phtml        
│               ├── delete.phtml      
│               ├── index.phtml           # Task List
│               ├── main.phtml            # Main view
│               ├── read.phtml       
│               ├── update.phtml      
│               └── detalle.phtml         # Task Details
├── config/                           
│   ├── db.inc.php                        # Database Config
│   ├── environment.inc.php               # Environtment Detector
│   ├── fakeTasksTest.json             
│   ├── routes.php                        # Route System
│   ├── settings.ini                      # General Setting
│   ├── tasks.json                  
│   └── users.json                
├── lib/                                  # Librerías y framework base
│   ├── README                            # Descripción de la librería
│   └── base/
│       ├── Controller.php                # Clase base de controladores
│       ├── Model.php                     # Clase base de modelos
│       ├── Request.php                   # Manejo de peticiones
│       ├── Router.php                    # Enrutador principal
│       └── View.php                      # Motor de vistas
├── web/                                  # Recursos públicos y entrada
│   ├── .htaccess                         # Reglas de Apache
│   ├── images/
│   │   └── README                        # Carpeta de imágenes
│   ├── index.php                         # Front controller (entrada app)
│   ├── javascripts/                      # Scripts JS
│   │   ├── buildUrl.js                   # Dynamic URL's helpers
│   │   ├── confirmar_delete.js           # Delete Confirmation Modal
│   │   ├── drag_drop.js                  # Task's Drag & Drop
│   │   ├── flash.js                      # Flash Messages
│   │   ├── stats.js                      # Task Stats
│   │   └── task_organize.js             
│   └── stylesheets/                      # Optional CSS Styles 
│       ├── detalhes.css                 
│       ├── detalles.css                  
│       └── layout.css                  

```

The idea is the availabilty to drag a task to a droppable status task list to update it directly, using an AJAX request. Every task list prints dinamically with all the Drop properties, as the same for draggable tasks, when you're rendering the view.

There's a Trash icon that it have the same for deleting tasks.

### About Design

- We thinked about a status task list cards for make a simple but effectively effect for User's experience. In addition, we make a lateral aside for the information. We use a basic gradient, and, approaching the aside, we use an visual effect called *Glassmorphism* to make some kind of 3D effect on it. We want to build a intuitive way to see all your information

#### Things that we could enhance

- Every task printed there's a kind of *See Details* Hyperlink. We could just make an onclick event for Read, instead using a text for entry.

- Drag & Drop is not completely integrated but two reasons. One is not update all the CSS properties when you drop a Task, and the Trash div is not completed. And is one of the main GUI elements to solve, like test JSON conecction as the same.


### About Architecture & Structure

#### app/controllers

Meanwhile the base Controller applies all the framework machinery, are an *ApplicationController* that extends of it, and that child Controller manage all the app inits, loaders, flashes and so on. At the same time, the *TaskController* uses all the GET/POST logic and inherit their parents properties.

There's three kind of methods:
. Action methods that manage GET/POST Requests and charges de views.
. Form Requests Process of every CRUD Action to validate and sanitize the model instances and his error exceptions
. AJAX Requests from JSON

- Validate And Sanitize methods are included here.

#### app/models

*TaskModel* handles all database interaction. Perhaps you notice that:

```code

private const VALID_STATES = ['pendiente', 'en_progreso', 'completada', 'cancelada'];

    private const DEFAULT_TASK_STRUCTURE = [
        'id' => 0,
        'titulo' => '',
        'descripcion' => '',
        'estado' => 'pendiente',
        'fecha_creacion' => '',
        'fecha_actualizacion' => ''
    ];

````

This constants determines which kind of status have an id Task, the JSON structure and how applies 
the data information.


All view files end with .phtml
Having an action in the TestController called index, the view file
app/views/test/index.phtml will be rendered as default.

#### Error handling
A general error handling has been added.

If a route doesn’t exist, then the error controller is hit.
If some other exception was thrown, the error controller is hit.
As default, the error controller just shows the exception occured, so remember
to style the error controller’s view file (app/views/error/error.phtml)

### Utilities
- [PHP Developers Guide](https://www.php.net/manual/en/index.php).
- .gitignore file configuration. [See Official Docs](https://docs.github.com/en/get-started/getting-started-with-git/ignoring-files).
- Git branches. [See Official Docs](https://git-scm.com/book/en/v2/Git-Branching-Branches-in-a-Nutshell).
