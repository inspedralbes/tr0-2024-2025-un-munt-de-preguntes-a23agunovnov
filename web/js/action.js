const quantPreg = 10;

fetch('../back/getPreguntes.php?quantPreg='+quantPreg)
.then(response => response.json())
.then(dataRecibida => {
  jugar(dataRecibida);
});

let data;
let iterador = 0; // Itera por cada pregunta
window.onload = updateClock; //Al cargar la página web, comience la función del contador
let totalTime = 5; // Duración del contador
let estatDeLaPartida = {
  rtasFetas: new Array()
};

function jugar(dataRecibida){
  data = dataRecibida;
  mostrarPreguntes(iterador);
}

function mostrarPreguntes(indice){
  let htmlString = '';
  setTimeout(() => {
    document.getElementById("pregunta").innerHTML = data[indice].pregunta;
    for(let j = 0; j < data[indice].respostes.length; j++){
      htmlString += `<button class="btn" id=${j} idResp="${j}" name="boton">${data[indice].respostes[j]}</button>`
    }
    document.getElementById("respuestas").innerHTML = htmlString;
  }, 200);
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
      htmlString = `<h2 class="tiempoAcabado">El tiempo se ha acabado</h2><h3 id="rtas">Respuestas correctas: ${score.numOk} / ${score.total}</h3>`;
      document.getElementById('subcontainer').innerHTML = htmlString;
      console.log(score)
    })
    .catch(error => console.log('Error: '+error));

    document.getElementById('playAgain').classList.remove("hidden");
    document.getElementById('enviarScore').classList.remove("hidden");
  }
}

document.getElementById('respuestas').addEventListener('click', e => {
  if(e.target.classList.contains('btn')){
    pulsar(e.target.getAttribute('idResp'));
  }
});

// REACCIÓN AL PULSAR
function pulsar(j){
  if(iterador < data.length-1){
    // deshabilitar que pueda tocar otras opciones al pulsar el botón
    document.querySelectorAll(".btn").forEach(boton => {
      boton.classList.add("disabled");
    });

    estatDeLaPartida.rtasFetas[iterador] = {
      idPreg: iterador,
      idResp: j
    };

    fetch('../back/validar.php',{
      method: 'POST',
      body: JSON.stringify({
        idPreg: iterador,
        idResp: j
    })
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
  window.location.href="index.html";
}