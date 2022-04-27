

let crossAdmin = document.querySelector('.crossAdmin');
let hideAdmin = document.querySelectorAll('nav.navAdmin > ul.listNavAdmin > li');
let stickMiddle = document.querySelector('.stickMiddle');
let stickTop = document.querySelector('.stickTop');
let stickBottom = document.querySelector('.stickBottom');
let closeAdminBurger = document.querySelector('.closeAdminBurger');

let crossUser = document.querySelector('.crossUser');
let hideUser = document.querySelector('.navUser');
let stickMiddleUser = document.querySelector('.stickMiddleUser');
let stickTopUser = document.querySelector('.stickTopUser');
let stickBottomUser = document.querySelector('.stickBottomUser');
let closeUserBurger = document.querySelector('.closeUserBurger');


function toggleAdminLink(event){
    closeAdminBurger.classList.toggle('statut-hide')
    stickTop.classList.toggle('statut-hide')
    stickBottom.classList.toggle('statut-hide')
    stickMiddle.classList.toggle('statut-hide')
    hideAdmin.forEach(element =>{
        element.classList.toggle('statut-hide')
        event.stopPropagation()
    });
    window.addEventListener('click', closeWindow)
}

function toggleUserLink(event){

    closeUserBurger.classList.toggle('statut-hide')
    stickTopUser.classList.toggle('statut-hide')
    stickBottomUser.classList.toggle('statut-hide')
    stickMiddleUser.classList.toggle('statut-hide')
    hideUser.classList.toggle('statut-hide')
        console.log(hideUser)

        event.stopPropagation()
    window.addEventListener('click', closeWindow)
}

crossAdmin.addEventListener('click', toggleAdminLink);
crossUser.addEventListener('click', toggleUserLink);

function closeWindow() {
    hideAdmin.forEach(element =>{
        element.className = "";
        element.classList.add('statut-hide')
    });
}