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

//----------trainings___________//

let buttonTraining = document.querySelectorAll('.buttonTraining')
let textTraining = document.querySelectorAll('.textTraining')
let buttonTrainingClose = document.querySelectorAll('.buttonTrainingClose')

console.log(textTraining)
function buttonCloseTraining(elemList){
    //première boucle agissant au click
    for(let i = 0 ; i < elemList.length ; i++){
        elemList[i].addEventListener('click', (event)=>{
            buttonTraining[i].classList.toggle('statut-hide');
            buttonTrainingClose[i].classList.toggle('statut-hide');
            //deuxieme boucle agissant les textes
            for(let v = 0 ; v < textTraining.length ; v++){
                if(textTraining[v]!=textTraining[i]){
                    textTraining[v].classList.add('statut-hide');
                    buttonTrainingClose[v].classList.add('statut-hide');
                    buttonTraining[v].classList.remove('statut-hide');
                }
            }

            textTraining[i].classList.toggle('statut-hide');
            //arret de la propagation de l'event venant du dessus du DOM
            event.stopPropagation();
        })
        window.addEventListener('click', closeTextTraining);
    }
}

function closeTextTraining() {
    for(let i = 0 ; i < textTraining.length ; i++){
        textTraining[i].classList.add('statut-hide');
        buttonTrainingClose[i].classList.add('statut-hide');
        buttonTraining[i].classList.remove('statut-hide');
    }
}

buttonCloseTraining(buttonTraining);
buttonCloseTraining(buttonTrainingClose)