'use strict';

function allowDrop(ev){
    //Permiso para deleteTask()
    ev.preventDefault();    
}

function drag(ev){
    //guardar datos de la tarea que se va a eliminar
    ev.dataTransfer.setData("text", ev.target.id);
}

function dropDelete(ev){
    //obtener data
    var data = ev.dataTransfer.getData("text");
    var taskDiv = document.getElementById(data);
    var realId = taskDiv.getAttribute('data-task-id');
    if(taskDiv){
        //oculta la tarea
        taskDiv.style.display = "none";
        //Para eliminarlo del DOM, ergo, simula el borrado:
        realId.remove();
        //llama a la función para eliminar la tarea de la base de datos
        deleteTask(data);
    }
    var closeLit = document.getElementById('closeLitter');
    var openLit = document.getElementById('openLitter');
    openLit.style.color = "red";
    openLit.style.display = "block";
    closeLit.style.display = "none";

    var iconLitter = document.getElementById('Litter');
    iconLitter.style.borderColor = "red";
}

function deleteTask(data){
    //fetch para hacer una petición AJAX al backend para eliminar tarea de la db
    fetch('/task/delete', {
        method: 'POST',
        body: JSON.stringify({ taskId: data}),
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        //Manejamos la respuesta del backend
        if(data.success === true){
            //Borrado con exito: ver si hace aqui un div alert
        } else{
            //Error al borrar: ver si hace aqui un div alert
        }
    });
}

function dropUpdate(ev, newStatus){
    ev.preventDefault();

    var data = ev.dataTransfer.getData("text");
    var taskDiv = document.getElementById(data);
    var realId = taskDiv.getAttribute('data-task-id');
    var stDiv = ev.target;

    if(taskDiv && stDiv){
        stDiv.appendChild(taskDiv);
        updateTask(realId, newStatus); 
    }
}

function updateTask(id, newStatus){
    fetch('/task/update', {
        method: 'POST',
        body: JSON.stringify({ taskId: id, status: newStatus}),
        headers: { 'Content-Type': 'application/json'}
    })
    .then(response => response.json())
    .then(data => {
        if(data.success === true){
            //Actualizacion con éxito: ver si hacer un div alert
        } else {
            //Error al actualizar: ver si hacer un div alert
        }
    });
}