fetch('http://localhost/TR0/data.json')
.then(response => response.json())
.then(dataRecibida => {
  jugar(dataRecibida);
});


function jugar(dataRecibida){
  data = dataRecibida

  //mostrar primera pregunta + opciones
  document.getElementById("pregunta").innerHTML = data.preguntes[0].pregunta;
  
  let btn = "";
  for(let j = 0; j < data.preguntes[0].respostes.length; j++){
    btn += `<button class="btn" id="${j}" onclick="pulsar(0,${j})">${data.preguntes[0].respostes[j]}</button>`
  }
  document.getElementById("respuestas").innerHTML = btn;
}

let enviarScore = document.getElementById('enviarScore');
let subcontainer = document.getElementById('subcontainer');
let estatDeLaPartida = [];
let rtasFetas = new Array(30);
 /*{
  respostesCorrectes: 0,
  preguntesFetes: 0
};*/

//CONTADOR
window.onload = updateClock;
let totalTime = 5;
function updateClock(){
  if(totalTime != -1){
    document.getElementById("contador").innerHTML = totalTime;
    totalTime--;
    setTimeout(updateClock, 1000);
  }else{
    enviarScore.classList.remove("hidden");
    htmlString = `<h2 class="tiempoAcabado">El tiempo se ha acabado</h2><h3 id="rtas">Respuestas: </h3>`;
    
    rtasFetas.forEach(rta => {
      htmlString += `<p>ID Pregunta: ${rta.idPreg} | ID Respuesta: ${rta.idResp}</p>`
    });
    
    subcontainer.innerHTML = htmlString;

    //document.getElementById('rtas').innerHTML += estatDeLaPartida.respostesCorrectes;
  }
}

//FUNCIÓN DE VALIDACIÓN
let iterador = 0;

function pulsar(i,j){
  if(iterador <= 30){
    //DESHABILITAR TOCAR MAS OPCIONES
    document.querySelectorAll(".btn").forEach(boton => {
      boton.classList.add("disabled");
    });
    //COMPROBAR QUE LA RESPUESTA SEA CORRECTA + CAMBIAR STYLE
    btn = "";
    btnElem = document.getElementById(j);
    //if(j == data.preguntes[i].resposta_correcta){
        //estatDeLaPartida.respostesCorrectes++;
        rtasFetas[iterador] = {
          idPreg: data.preguntes[i].id,
          idResp: j
        };
        btnElem.style.background = "#fdfd80";
    /*}else{
      btnElem.style.background = "#fc5454";
    }*/
    
    //estatDeLaPartida.preguntesFetes = iterador;

    //MOSTRAR PREGUNTA + RESPUESTAS SIGUIENTE
    setTimeout(() => {
      document.getElementById("pregunta").innerHTML = data.preguntes[iterador].pregunta;
      for(let j = 0; j < data.preguntes[iterador].respostes.length; j++){
        btn += `<button class="btn" id="${j}" onclick="pulsar(${iterador},${j})">${data.preguntes[iterador].respostes[j]}</button>`
      }
      document.getElementById("respuestas").innerHTML = btn;
    }, 200);
  }
  iterador++;
}