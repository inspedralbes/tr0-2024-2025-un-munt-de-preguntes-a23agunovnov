// 'http://localhost/tr0-2024-2025-un-munt-de-preguntes-a23agunovnov/back/data.json'
fetch('../back/getPreguntes.php')
.then(response => response.json())
.then(dataRecibida => {
  jugar(dataRecibida);
});

let iterador = 0; // Itera por cada pregunta
window.onload = updateClock; //Al cargar la página web, comience la función del contador
let totalTime = 5; // Duración del contador

function jugar(dataRecibida){
  data = dataRecibida
  mostrarPreguntes(iterador);
}

//Declaramos los diferentes contenedores y el objeto que almacenará información de la partida
let enviarScore = document.getElementById('enviarScore');
let subcontainer = document.getElementById('subcontainer');
let estatDeLaPartida = {
  quantitatRespostes: 0,
  rtasFetas: new Array()
};

function mostrarPreguntes(indice){
  let htmlString = '';
  setTimeout(() => {
    document.getElementById("pregunta").innerHTML = data[indice].pregunta;
    for(let j = 0; j < data[indice].respostes.length; j++){
      htmlString += `<button class="btn" id="${j}" name="boton" onclick="pulsar(${indice},${j})">${data[indice].respostes[j]}</button>`
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
    fetch('../back/validar.php',{
      method: 'POST',
      body: JSON.stringify(estatDeLaPartida.rtasFetas)
    })
    .then(response => response.json())
    .then(score => {
      htmlString = `<h2 class="tiempoAcabado">El tiempo se ha acabado</h2><h3 id="rtas">Respuestas correctas: ${score.numOk} / ${score.total}</h3>`;
      subcontainer.innerHTML = htmlString;
      console.log(score)
    })
    .catch(error => console.log('Error: '+error));

    enviarScore.classList.remove("hidden");
  }
}

// Acción que realiza cada vez que se pulsa un botón
function pulsar(i,j){
  if(iterador < data.length -1){
    // deshabilitar que pueda tocar otras opciones al pulsar el botón
    document.querySelectorAll(".btn").forEach(boton => {
      boton.classList.add("disabled");
    });

    estatDeLaPartida.quantitatRespostes = iterador+1;
    estatDeLaPartida.rtasFetas[iterador] = {
      idPreg: data[i].id,
      idResp: j
    };

    document.getElementById(j).style.background = "#fdfd80";
    iterador++;
    mostrarPreguntes(iterador);
  }
}