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
. localhost/8000/app

Adding the /app the router will detect the project's renderized main view.


## ▶️ Execution
- Once the project has been installed, you can observer the structure

### Folder Structure

```
S3_02_DevTeam/
├── app/                            # Application usages
│   ├── controllers/
│   │   └── TaskController.php      # CRUD logic
│   ├── models/
│   │   └── TaskModel.php           # JSON persistence's requests
│   └── views/scripts/task/
│       ├── create.phtml            # Create
│       ├── read.phtml              # Read a List
│       ├── detalle.phtml           # Read an specific task
│       ├── update.phtml            # Edit
│       └── delete.phtml            # Confirm a deleting task
├── config/
│   ├── routes.php                  # route config
│   ├── environment.inc.php         # environtment detection
│   └── tasks.json                  # JSON files
├── web/
│   ├── stylesheets/
│   │   └── layout.css              # styles
│   └── javascripts/
│       |── drag_drop.js            # drag & drop
|       |__ flash.js                # for flash messages    
└── lib/                            # framework basis
```

### Usage

The project has been designed using a MVC pattern. All the app files are located in the "app" folder.

All requests go through this file and it decides how the routing of the app
should be.
You can add additional hooks in this file to add certain routes.

### Automatic Server Detector - Hybrid System Style

Leandro:

I was having problems because there're app parts that just run it in XAMPP. Other parts in the command-line's PHP 
Server. So, I build a solution that it detects autmoatically in which server we are.

Basically, if we're running in port 8000, the system assumes that's a PHP Server. If it sees that
Básicamente si estamos corriendo en el puerto 8000, el sistema asume que es PHP Server.
an URL with */fullstackphp-sprint3/*, assume the XAMPP Server.
In addition, getBaseUrl() method decides which base route have to decide. Using that way I don't need to change
the routes manually if I want to pass one server to another.

#### Automatic Detection
- File: `config/environment.inc.php`
- It detects automatically XAMPP & PHP Server
- Adjust base URLs depends of the environtment.

#### Compatibility
- XAMPP: `localhost/fullstackphp-sprint3/S302/S3_02_DevTeam/web/`
- PHP Server: `localhost:8000/`
- JavaScript: Dinamic URLs using `buildUrl()` helper method

### Database Persistence's Interface

Marc:
Thanks to a partner's reference, I was planning a more elegant way to change database persistence. I
created an Interface using a Facade pattern.

You can see it at feature/db

### Persistence

#### JSON
- File: `config/tasks.json`
- Structure: Object's Array with auto-increment Id, títle, description y status
- Full Validation CRUD

### Using Scripts for a better UI / UX

Marc:
I was thinking about a more dynamic / better user experience. When we designed the main view of the task list, it
louds a Drag & Drop functionality. So, there're three important elements in the function:
  . A Draggable element
  . A droppable div / icon
  . Allow the element to Drop



#### app/controllers
Your application’s controllers should be defined here.

All controller names should end with “Controller”. E.g. TestController.
All controllers should inherit the library’s “Controller” class.
However, you should generally just make an ApplicationController, which extends
the Controller. Then you can defined beforeFilters etc in that, which will get run
at every request.

#### app/models
Models handles database interaction etc.

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
