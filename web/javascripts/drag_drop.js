'use strict';

function allowDrop(ev){
    //Permiso para drop
    ev.preventDefault();    
}

function drag(ev){
    //Guardar datos de la tarea que se va a mover
    ev.dataTransfer.setData("text", ev.target.id);
}

function dropDelete(ev){
    //Obtener datos
    var data = ev.dataTransfer.getData("text");
    var taskDiv = document.getElementById(data);
    var realId = taskDiv.getAttribute('data-task-id');
    if(taskDiv){
        //Oculta la tarea
        taskDiv.style.display = "none";
        //Para eliminarlo del DOM, simula el borrado
        realId.remove();
        //Llama a la función para eliminar la tarea de la base de datos
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
    //Fetch para hacer una petición AJAX al backend para eliminar tarea de la db
    fetch(buildUrl('task/delete'), {
        method: 'POST',
        body: JSON.stringify({ taskId: data}),
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        //Manejamos la respuesta del backend
        if(data.success === true){
            //Borrado con éxito: ver si hace aquí un div alert
        } else{
            //Error al borrar: ver si hace aquí un div alert
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
    fetch(buildUrl('task/update'), {
        method: 'POST',
        body: JSON.stringify({ taskId: id, status: newStatus}),
        headers: { 'Content-Type': 'application/json'}
    })
    .then(response => response.json())
    .then(data => {
        if(data.success === true){
            //Actualización con éxito: ver si hacer un div alert
        } else {
            //Error al actualizar: ver si hacer un div alert
        }
    });
}