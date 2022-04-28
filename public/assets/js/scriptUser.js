let crossUser = document.querySelector('.crossUser');
let hideUser = document.querySelectorAll('.hide-js');
let stickMiddleUser = document.querySelector('.stickMiddleUser');
let stickTopUser = document.querySelector('.stickTopUser');
let stickBottomUser = document.querySelector('.stickBottomUser');
let closeUserBurger = document.querySelector('.closeUserBurger');

function toggleUserLink(event){
    closeUserBurger.classList.toggle('statut-hide')
    stickTopUser.classList.toggle('statut-hide')
    stickBottomUser.classList.toggle('statut-hide')
    stickMiddleUser.classList.toggle('statut-hide')
    hideUser.forEach(element =>{
        element.classList.toggle('statut-hide')
        event.stopPropagation()
    })
    window.addEventListener('click', closeWindowUser)
}

crossUser.addEventListener('click', toggleUserLink);

function closeWindowUser() {
    closeUserBurger.classList.add('statut-hide')
    stickTopUser.classList.remove('statut-hide')
    stickBottomUser.classList.remove('statut-hide')
    stickMiddleUser.classList.remove('statut-hide')
    hideUser.forEach(element =>{
        element.classList.remove('statut-hide')
        element.classList.add('statut-hide')
    });
}