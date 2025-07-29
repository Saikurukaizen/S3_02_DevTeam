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

### Folder Structure

```text
phpInitialDemo-main/
├── README.md
├── app/
│   ├── controllers/
│   │   ├── ApplicationController.php
│   │   ├── ErrorController.php
│   │   ├── TaskController.php
│   │   ├── TestController.php
│   │   └── UserController.php
│   ├── models/
│   │   ├── TaskModel.php
│   │   └── UserModel.php
│   └── views/
│       ├── layouts/
│       │   ├── form.phtml
│       │   ├── layout.phtml
│       │   └── update.phtml
│       └── scripts/
│           ├── error/
│           │   └── error.phtml
│           ├── test/
│           │   ├── form.phtml
│           │   └── index.phtml
│           └── task/
│               ├── _form.phtml
│               ├── create.phtml
│               ├── delete.phtml
│               ├── index.phtml
│               ├── main.phtml
│               ├── read.phtml
│               ├── update.phtml
├── config/
│   ├── db.inc.php
│   ├── environment.inc.php
│   ├── fakeTasksTest.json
│   ├── routes.php
│   ├── settings.ini
│   ├── tasks.json
│   └── users.json
├── lib/
│   ├── README
│   └── base/
│       ├── Controller.php
│       ├── Model.php
│       ├── Request.php
│       ├── Router.php
│       └── View.php
├── web/
│   ├── .htaccess
│   ├── images/
│   │   └── README
│   ├── index.php
│   ├── javascripts/
│   │   ├── buildUrl.js
│   │   ├── confirmar_delete.js
│   │   ├── drag_drop.js
│   │   ├── flash.js
│   │   ├── stats.js
│   │   └── task_organize.js
│   └── stylesheets/              #Optional CSS Samples
│       ├── detalhes.css
│       ├── detalles.css
│       └── layout.css
```

The project has been designed using a MVC pattern. All the app files are located in the "app" folder.

All requests go through this file and it decides how the routing of the app
should be.
You can add additional hooks in this file to add certain routes.

All HTML output uses escaping functions to prevent code injection. The controllers validate incoming data and reject invalid input. The system prevents basic XSS attacks through data validation and sanitization.

### Automatic Server Detector - Hybrid System Style

Leandro:

I was having problems because there're app parts that just run it in XAMPP. Other parts in the command-line's PHP 
Server. So, I build a solution that it detects autmoatically in which server we are.

- The config/environment.inc.php file implements an automatic environment detection system. Its purpose is to identify the server type, port, base URL, and context (local or production) without requiring manual configurations or relying on the folder structure.

- The Environment class analyzes the global *$_SERVER* variables available in any PHP installation. It detects the server software (Apache, Nginx, IIS, PHP Built-in Server, among others) and determines whether the application is running in a local or remote environment. The system adjusts the base URL considering the protocol, host, port, and project path, ensuring that all routes and links work correctly in any environment.

- The *detect()* method runs automatically when the file is loaded, setting the class's static properties. These properties are used to build absolute URLs, identify the server type, and adapt the application's behavior according to the environment. The *Environment::url()* helper allows the generation of full paths for any resource or action, and the *buildUrl()* helper in JavaScript uses the global BASE_URL variable to maintain frontend compatibility.

- The system prevents common deployment issues, such as broken routes, port mismatches, and domain changes, ensuring that the application is portable and functional on any PHP server, Docker container, shared hosting, or local development environment.

#### Automatic Detection
- File: `config/environment.inc.php`
- Adjust base URLs depends of the environtment.

### Issues and solutions.

- Absolute URL folders directly instead a ServerPort. That had provoke serious headeaches. SO, we substitute it for NumberPort Servers.

- It became a lack of portability, so we worked into that with the details explained before.

### Database Persistence's Interface

Marc:
Thanks to a partner's reference, I was planning a more elegant way to change database persistence. I
created an Interface using a Facade pattern. The idea is that every Database Persistence could save different logic requests for every kind of persistence, and make an Dependency Injection using *Switch* cases, previously declared in a db.environment file. The reason it was for future cases, to develop a complete persistence, not just working with JSON.

You can see it at feature/db

### Persistence

#### JSON
- File: `config/tasks.json`
- Structure: Object's Array with auto-increment Id, títle, description, status, an created_at and an updated_at.
- Full Validation CRUD

### Using Scripts for a better UI / UX

Marc:
I was thinking about a more dynamic / better user experience. When we designed the main view of the task list, it
louds a Drag & Drop functionality. So, there're three important elements in the function:
  . A Draggable element
  . A droppable div / icon
  . Allow the element to Drop

The idea is the availabilty to drag a task to a droppable status task list to update it directly, using an AJAX request. Every task list prints dinamically with all the Drop properties, as the same for draggable tasks, when you're rendering the view.

There's a Trash icon that it have the same for deleting tasks.

### About Design

- We thinked about a status task list cards for make a simple but effectively effect for User's experience. In addition, we make a lateral aside for the information. We use a basic gradient, and, approaching the aside, we use an visual effect called *Glassmorphism* to make some kind of 3D effect on it. We want to build a intuitive way to see all your information


#### Things that we could enhance

- Every task printed there's a kind of *See Details* Hyperlink. We could just make an onclick event for Read, instead using a text for entry.

- Drag & Drop is not completely integrated but two reasons. One is not update all the CSS properties when you drop a Task, and the Trash div is not completed. And is one of the main GUI elements to solve.


### About Architecture & Structure

#### app/controllers

Meanwhile the base Controller applies all the framework machinery, are an *ApplicationController* that extends of it, and that child Controller manage all the app inits, loaders, flashes and so on. At the same time, the *TaskController* uses all the GET/POST logic and inherit their parents properties.

There's three kind of methods:
. Action methods that manage GET/POST Requests
. Form Requests Process of every CRUD Action to validate and sanitize the model instances and his error exceptions
. AJAX Requests from JSON

#### app/models

*TaskModel* handles all database interaction


All models should inherit from the Model class, which provides basic functionality.
The Model class handles basic functionality such as:

Setting up a database connection (using PDO)
fetchOne(ID)
save(array) → both update/create
delete(ID)
app/views
Your view files.
The structure is made so that having a controller named TestController, it looks
in the app/views/test/ folder for it’s view files.

All view files end with .phtml
Having an action in the TestController called index, the view file
app/views/test/index.phtml will be rendered as default.

#### config/routes.php
Your routes around the system needs to be defined here.
A route consists of the URL you want to call + the controller#action you want it
to hit.

An example is:
$routes = array(
‘/test’ => ‘test#index’ // this will hit the TestController’s indexAction method.
);

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
