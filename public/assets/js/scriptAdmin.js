

let crossAdmin = document.querySelector('.crossAdmin');
let hideAdmin = document.querySelectorAll('nav.navAdmin > ul.listNavAdmin > li');
let stickMiddle = document.querySelector('.stickMiddle');
let stickTop = document.querySelector('.stickTop');
let stickBottom = document.querySelector('.stickBottom');
let closeAdminBurger = document.querySelector('.closeAdminBurger');



function toggleAdminLink(event){
    closeAdminBurger.classList.toggle('statut-hide')
    stickTop.classList.toggle('statut-hide')
    stickBottom.classList.toggle('statut-hide')
    stickMiddle.classList.toggle('statut-hide')
    hideAdmin.forEach(element =>{
        element.classList.toggle('statut-hide')
        event.stopPropagation()
    });
    window.addEventListener('click', closeWindowAdmin)
}


crossAdmin.addEventListener('click', toggleAdminLink);


function closeWindowAdmin() {
    closeAdminBurger.classList.add('statut-hide')
    stickTop.classList.remove('statut-hide')
    stickBottom.classList.remove('statut-hide')
    stickMiddle.classList.remove('statut-hide')
    hideAdmin.forEach(element =>{
        element.className = "";
        element.classList.add('statut-hide')
    });
}

