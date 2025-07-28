'use strict';

function confirmarEliminacion(taskId){
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.style.cssText = [
        'position: fixed',
        'top: 0',
        'left: 0',
        'width: 100%',
        'height: 100%',
        'background-color: rgba(0,0,0,0.5)',
        'display: flex',
        'align-items: center',
        'justify-content: center',
        'z-index: 10000'
    ].join(';');

    const modalContent = document.createElement('div');
    modalContent.style.cssText = [
        'background-color: white',
        'padding: 2rem',
        'border-radius: 8px',
        'box-shadow: 0 10px 30px rgba(0,0,0,0.3)',
        'max-width: 400px',
        'width: 90%',
        'text-align: center'
    ].join(';');

    modalContent.innerHTML = `
        <h3 style="margin-bottom: 1rem; color: #172b4d; font-size: 1.2rem;">Confirmar Eliminación</h3>
        <p style="margin-bottom: 2rem; color: #6c757d; line-height: 1.5;">¿Estás seguro de que quieres eliminar esta tarea? Esta acción no se puede deshacer.</p>
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button id="btn-confirm-delete" style="background-color: #dc3545; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.9rem; min-width: 100px; transition: all 0.2s ease;">Sí, Eliminar</button>
            <button id="btn-cancel-delete" style="background-color: #6c757d; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.9rem; min-width: 100px; transition: all 0.2s ease;">Cancelar</button>
        </div>
    `;
    modal.appendChild(modalContent);
    document.body.appendChild(modal);

    modalContent.querySelector('#btn-confirm-delete').onclick = function() {
        deleteTask(taskId);
        modal.remove();
    };
    modalContent.querySelector('#btn-cancel-delete').onclick = function() {
        modal.remove();
    };
}

function deleteTask(taskId)
{
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/task/delete';

    const idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = 'id';
    idInput.value = taskId;

    form.appendChild(idInput);
    document.body.appendChild(form);
    form.submit();
}
 