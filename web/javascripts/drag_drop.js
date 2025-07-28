'use strict';

function allowDrop(ev){
    ev.preventDefault();    
}

function drag(ev){
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

    taskDiv.style.display = "none";
    if (taskDiv.parentNode) {
        taskDiv.parentNode.removeChild(taskDiv);
    }
    deleteTask(realId);

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

    fetch(buildUrl('task/delete'), {
        method: 'POST',
        body: JSON.stringify({ taskId: data}),
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success === true){
            if (typeof notifyTaskChange === 'function') {
                notifyTaskChange('delete');
            }
        } else{

        }
    });
}

function dropUpdate(ev, newStatus) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text");

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

    var taskDiv = document.getElementById(id);
    if (!taskDiv) {
        console.error('No se encontró el div de la tarea para actualizar:', id);
        return;
    }

    var titulo = '';
    var descripcion = '';

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
        if(data.success === true){
            if (typeof notifyTaskChange === 'function') {
                notifyTaskChange('move');
            }
        } else {

        }
    });
}

document.addEventListener('drop', function(ev){
    ev.preventDefault();
}, false);
document.addEventListener('dragover', function(ev){
    ev.preventDefault();
}, false);
