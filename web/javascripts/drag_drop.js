'use strict';

function allowDrop(ev){
    //Permiso para drop
    ev.preventDefault();    
}

function drag(ev){
    //Guardar datos de la tarea que se va a mover
    ev.dataTransfer.setData("text", ev.currentTarget.id);
}

function dropDelete(ev, newStatus) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    var taskDiv = document.getElementById(data);
    if (!taskDiv) {
        console.error('No se encontró el elemento de la tarea con id:', data);
        return;
    }
    var realId = taskDiv.getAttribute('data-task-id');
    var stDiv = ev.target;

    // Eliminar visualmente la tarea
    taskDiv.style.display = "none";
    // Eliminar del DOM
    if (taskDiv.parentNode) {
        taskDiv.parentNode.removeChild(taskDiv);
    }
    // Llama a la función para eliminar la tarea de la base de datos
    deleteTask(realId);

    // Actualiza iconos de papelera si existen
    var closeLit = document.getElementById('closeLitter');
    var openLit = document.getElementById('openLitter');
    if (openLit) {
        openLit.style.color = "red";
        openLit.style.display = "block";
    }
    if (closeLit) {
        closeLit.style.display = "none";
    }
    var iconLitter = document.getElementById('Litter');
    if (iconLitter) {
        iconLitter.style.borderColor = "red";
    }
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

function dropUpdate(ev, newStatus) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");
    console.log('Intentando buscar elemento con id:', data);
    var taskDiv = document.getElementById(data);
    if (!taskDiv) {
        console.error('No se encontró el elemento de la tarea con id:', data);
        return;
    }
    var realId = taskDiv.getAttribute('data-task-id');
    var stDiv = ev.target;
    if (taskDiv && stDiv) {
        stDiv.appendChild(taskDiv);
        updateTask(realId, newStatus);
    }
}

function updateTask(id, newStatus){
    // Obtener el div de la tarea por id
    var taskDiv = document.getElementById(id);
    if (!taskDiv) {
        console.error('No se encontró el div de la tarea para actualizar:', id);
        return;
    }
    // Obtener los valores actuales del DOM
    var titulo = '';
    var descripcion = '';
    // Busca el <a> y los elementos dentro del div
    var a = taskDiv.querySelector('a');
    if (a) {
        var strong = a.querySelector('strong');
        var small = a.querySelector('small');
        if (strong) titulo = strong.textContent.trim();
        if (small) descripcion = small.textContent.trim();
    }
    fetch(buildUrl('task/update'), {
        method: 'POST',
        body: JSON.stringify({
            id: Number(id),
            titulo: titulo,
            descripcion: descripcion,
            estado: newStatus
        }),
        headers: { 'Content-Type': 'application/json'}
    })
    .then(response => response.json())
    .then(data => {
         console.log(data)
        if(data.success === true){
            //Actualización con éxito: ver si hacer un div alert
        } else {
            //Error al actualizar: ver si hacer un div alert
        }
    });
}

document.addEventListener('drop', function(ev){
    ev.preventDefault();
}, false);
document.addEventListener('dragover', function(ev){
    ev.preventDefault();
}, false);