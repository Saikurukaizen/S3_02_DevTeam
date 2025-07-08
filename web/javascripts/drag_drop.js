'use strict';

/*SCRIPT DRAG & DROP

Aquí juegan dos elementos:
    1. El div/icon de deleteTask() que va a contener un id="Litter" y los atributos
        ondrop="drop(event)" dragover="allowDrop(event)".
        También contiene dos iconos que representan cuando abres y cuando cierras la papelera, ambos con ids de
        "closeLitter" y "openLitter" respectivamente.
    2. Los div de las tasks, que tienen que tener ids únicos y diferentes para cada tarea, y todas las div tienen
        que tener los mismos atributos de evento: draggable="true" ondragstart="drag(event)"

Por lo tanto, contendrá tres funciones en el script
    
*/

function allowDrop(ev){
    //Permiso para deleteTask()
    ev.preventDefault();    
}

function drag(ev){
    //guardar datos de la tarea que se va a eliminar
    ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev){
    //obtener data
    var data = ev.dataTransfer.getData("text");
    var task = document.getElementById(data);
    if(task){
        //oculta la tarea
        task.style.display = "none";
        //Para eliminarlo del DOM, ergo, simula el borrado:
        task.remove()
        //Aqui añadiría el método deleteTask() para eliminar la tarea de la base de datos
        //deleteTask(data);
    }
    var closeLit = document.getElementById('closeLitter');
    var openLit = document.getElementById('openLitter');
    openLit.style.color = "red";
    openLit.style.display = "block";
    closeLit.style.display = "none";

    var iconLitter = document.getElementById('Litter');
    iconLitter.style.borderColor = "red";
}