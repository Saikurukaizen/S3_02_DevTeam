'use strict';

/*SCRIPT DRAG & DROP

Aquí juegan dos elementos:
1. El div/icon de deleteTask() que va a contene un id="Litter" y los atributos
    ondrop="drop(event)" dragover="allowDrop(event".
    También contiene dos iconos que representan cuando abres y cuando cierras la papelera, ambos con ids de
*/

function allowDrop(ev){
    ev.preventDefault();
}