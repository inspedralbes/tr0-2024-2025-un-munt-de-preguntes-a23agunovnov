fetch('http://localhost/tr0-2024-2025-un-munt-de-preguntes-a23agunovnov/back/data.json')
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
let estatDeLaPartida = {
  quantitatRespostes: 0,
  rtasFetas: new Array()
};

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
    htmlString = `<h2 class="tiempoAcabado">El tiempo se ha acabado</h2><h3 id="rtas">Cantidad de respuestas: ${estatDeLaPartida.quantitatRespostes}</h3>`;

    estatDeLaPartida.rtasFetas.forEach(rta => {
      htmlString += `<p>ID Pregunta: ${rta.idPreg} | ID Respuesta: ${rta.idResp}</p>`
    });

    subcontainer.innerHTML = htmlString;
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
        estatDeLaPartida.rtasFetas[iterador] = {
          idPreg: data.preguntes[i].id,
          idResp: j
        };
        btnElem.style.background = "#fdfd80";
    /*}else{
      btnElem.style.background = "#fc5454";
    }*/
    
      estatDeLaPartida.quantitatRespostes = iterador+1;

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