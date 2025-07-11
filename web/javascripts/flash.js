'use strict';

function getTimeoutFlash(){
  document.addEventListener('DOMContentLoaded', function(){
    const flashMessage = document.getElementById('flash-message');
    if(flashMessage){
        setTimeout(() => {
            flashMessage.style.transition = 'opacity 0.5s ease-out';
            flashMessage.style.opacity = '0';

            setTimeout(() =>{
                flashMessage.remove();
            }, 1000);
        }, 3000);
    }
  });
}