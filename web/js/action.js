const quantPreg = 30;

fetch('../back/getPreguntes.php?quantPreg='+quantPreg)
.then(response => response.json())
.then(dataRecibida => {
  jugar(dataRecibida);
});

let data;
let iterador;
let totalTime;
let estatDeLaPartida;

function jugar(dataRecibida){
  iterador = 0;
  totalTime = 10;
  estatDeLaPartida = {
    rtasFetas: new Array()
  };
  updateClock();
  data = dataRecibida;
  mostrarPreguntes(iterador);
}

function mostrarPreg(indice){
  let htmlString = '';
  document.getElementById("pregunta").innerHTML = data.preguntes[indice].pregunta;
  document.getElementById("portada").setAttribute('src', data.preguntes[indice].imatge);
  document.getElementById("portada").setAttribute('alt', data.preguntes[indice].pregunta+".jpg");
  for(let j = 0; j < data.respostes[data.preguntes[indice].id].length; j++){
    htmlString += `<button class="btn" id=${j} idResp="${j}" name="boton">${data.respostes[data.preguntes[indice].id][j]}</button>`
  }
  document.getElementById("respostes").innerHTML = htmlString;
}

function mostrarPreguntes(index){
  if(index == 0){
    mostrarPreg(index);
  }else{
    setTimeout(() => {
      mostrarPreg(index);
    }, 200);
  }
}

//CONTADOR
function updateClock(){
  if(totalTime != -1){
    document.getElementById("contador").innerHTML = totalTime;
    totalTime--;
    setTimeout(updateClock, 1000);
  }else{
    let htmlString = "";
    
    fetch('../back/finalitza.php',{
      method: 'POST',
      body: JSON.stringify(estatDeLaPartida.rtasFetas)
    })
    .then(response => response.json())
    .then(score => {
      document.getElementById('jugar').classList.replace('show', 'hidden');
      document.getElementById('resultadoFinal').classList.replace('hidden', 'show');
      htmlString = `<h3 id="rtas">Respuestas correctas: ${score.numOk} / ${score.total}</h3>`;
      document.getElementById('score').innerHTML = htmlString;
    })
    .catch(error => console.log('Error: '+error));

    document.getElementById('playAgain').classList.remove("hidden"); //IMPORTANTEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE
    document.getElementById('enviarScore').classList.remove("hidden"); //IMPORTANTEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE
  }
}

document.getElementById('respostes').addEventListener('click', e => {
  if(e.target.classList.contains('btn')){
    pulsar(e.target.getAttribute('idResp'));
  }
});

// REACCIÓN AL PULSAR
function pulsar(j){ 
    // deshabilitar que pueda tocar otras opciones al pulsar el botón
    document.querySelectorAll(".btn").forEach(boton => {
      boton.classList.add("disabled");
    });

    let valorRta = document.getElementById(j).textContent;

    estatDeLaPartida.rtasFetas[iterador] = {
      nPreg: data.preguntes[iterador].id,
      idResp: valorRta
    };

    fetch('../back/validar.php',{
      method: 'POST',
      body: JSON.stringify(estatDeLaPartida.rtasFetas[iterador])
    })
    .then(response => response.json())
    .then(data => {
      if(data == true){
        document.getElementById(j).style.background = "#adfa97";
      }else{
        document.getElementById(j).style.background = "#fd6642";
      }
    })
    .catch(error => console.log("Error: " + error));
    iterador++;
    mostrarPreguntes(iterador);
}

document.getElementById('playAgain').addEventListener("click", reiniciar);

function reiniciar(){
  fetch('../back/sessiondestroy.php')
  .then(response => response.json)
  .then(data => {
    if(!data){
      alert('Ha ocurrido un error');
    }
  })
  .catch(error => console.log("Error: "+error));

  document.getElementById('jugar').classList.replace("hidden", "show");
  document.getElementById('resultadoFinal').classList.replace("show", "hidden");
  
  fetch('../back/getPreguntes.php?quantPreg='+quantPreg)
  .then(response => response.json())
  .then(dataRecibida => {
    jugar(dataRecibida);
  });
}