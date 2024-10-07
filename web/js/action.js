
let data;
let iterador;
let totalTime;
let estatDeLaPartida;
let dificultad;

//Comenzar a jugar
async function empezarJuego() {
  document.getElementById('reglas').classList.replace('showReglas', 'hidden')
  await fetch('../back/getPreguntes.php?quantPreg=' + 30)
    .then(response => response.json())
    .then(dataRecibida => {
      jugar(dataRecibida, dificultad)
    });
}

//Esperando que se aprete a "Jugar" en la primera ventana
document.getElementById('jugarbtn').addEventListener('click', mostrarReglas);

//Pasar a la pantalla de instrucciones
function mostrarReglas() {
  const nom = document.getElementById('nom').value;
  dificultad = document.getElementById('dificultad').value;
  localStorage.setItem("nom", nom);
  localStorage.setItem("dificultat", dificultad);
  document.getElementById('usuario').classList.replace('show', 'hidden');
  document.getElementById('reglas').classList.replace('hidden', 'showReglas')
}

//Esperando a escuchar el click de "Aceptar" para comenzar el juego
document.getElementById('empezarJuego').addEventListener('click', empezarJuego);

//Comenzar a jugar
function jugar(dataRecibida, dificultad) {
  document.querySelectorAll(".unabled").forEach(boton => {
    boton.classList.remove("unabled");
    boton.classList.remove("disabled");
    boton.classList.add("power");
  });
  document.getElementById('jugar').classList.replace('hidden', 'show');

  iterador = 0;
  if (dificultad == "facil") {
    totalTime = 60;
  } else if (dificultad == "intermedio") {
    totalTime = 40;
  } else {
    totalTime = 30;
  }
  estatDeLaPartida = {
    rtasFetas: new Array()
  };
  data = dataRecibida;
  updateClock();
  mostrarPreguntes(iterador);
}

//Retardo para que al hacer un click una respuesta y poder ver si está correcta o no
function mostrarPreguntes(index) {
  if (index == 0) {
    mostrarPreg(index);
  } else {
    setTimeout(() => {
      mostrarPreg(index);
    }, 200);
  }
}

//Mostrar pregunta con sus respuestas y su imagen
function mostrarPreg(indice) {
  let htmlString = '';
  document.getElementById("pregunta").innerHTML = data.preguntes[indice].pregunta;
  document.getElementById("portada").setAttribute('src', data.preguntes[indice].imatge);
  document.getElementById("portada").setAttribute('alt', data.preguntes[indice].pregunta + ".jpg");
  for (let j = 0; j < data.respostes[data.preguntes[indice].id].length; j++) {
    htmlString += `<button class="botonResp" style="cursor: pointer;" id=${j} idResp="${j}" name="boton">${data.respostes[data.preguntes[indice].id][j]}</button>`
  }
  document.getElementById("respostes").innerHTML = htmlString;
}

//CONTADOR
function updateClock() {
  if (totalTime != -1) {
    document.getElementById("contador").innerHTML = totalTime;
    totalTime--;
    setTimeout(updateClock, 1000);
  } else {
    let htmlString = "";

    fetch('../back/finalitza.php', {
      method: 'POST',
      body: JSON.stringify(estatDeLaPartida.rtasFetas)
    })
      .then(response => response.json())
      .then(score => {
        document.getElementById('jugar').classList.replace('show', 'hidden');
        document.getElementById('resultadoFinal').classList.replace('hidden', 'show');
        htmlString = `<h3 id="rtas">Respuestas correctas: ${score.numOk} / ${score.total}</h3><h3 style="margin-top:0;">Puntuación: ${score.puntos} pts</h3>`;
        document.getElementById('score').innerHTML = htmlString;
      })
      .catch(error => console.log('Error: ' + error));

    document.getElementById('playAgain').classList.remove("hidden"); //IMPORTANTEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE
    //document.getElementById('enviarScore').classList.remove("hidden"); //IMPORTANTEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE
  }
}

//Saber que respuesta tocó
document.getElementById('respostes').addEventListener('click', e => {
  if (e.target.classList.contains('botonResp')) {
    pulsar(e.target.getAttribute('idResp'));
  }
});

//Ayuda: añadir 5 segundos al contador
document.getElementById('back5').addEventListener("click", masTiempo);
function masTiempo() {
  document.getElementById('back5').classList.add('disabled');
  document.getElementById('back5').classList.replace('power', 'unabled');
  totalTime += 5;
}

//Ayuda: pasar a la siguiente pregunta sin penalización
document.getElementById('next').addEventListener("click", sigPreg);
function sigPreg() {
  document.getElementById('next').classList.add('disabled');
  document.getElementById('next').classList.replace('power', 'unabled');
  estatDeLaPartida.rtasFetas[iterador] = {
    nPreg: -1,
    idResp: -1
  };
  iterador++;
  mostrarPreguntes(iterador);
}

//Ayuda: quitar 2 respuestas incorrectas
document.getElementById('50').addEventListener("click", quitarResp);
function quitarResp() {
  document.getElementById('50').classList.add('disabled');
  document.getElementById('50').classList.replace('power', 'unabled');
  fetch('../back/powers/quitarResp.php', {
    method: 'POST',
    body: JSON.stringify(data.preguntes[iterador])
  })
    .then(response => response.json())
    .then(data => {
      document.getElementById(data[0]).classList.add('hidden');
      document.getElementById(data[1]).classList.add('hidden');
    })
}

//Reacción al pulsar una respuesta y saber si está correcta o no
function pulsar(j) {
  // deshabilitar que pueda tocar otras opciones al pulsar el botón
  document.querySelectorAll(".btn").forEach(boton => {
    boton.classList.add("disabled");
  });

  let valorRta = document.getElementById(j).textContent;

  estatDeLaPartida.rtasFetas[iterador] = {
    nPreg: data.preguntes[iterador].id,
    idResp: valorRta
  };

  fetch('../back/validar.php', {
    method: 'POST',
    body: JSON.stringify(estatDeLaPartida.rtasFetas[iterador])
  })
    .then(response => response.json())
    .then(data => {
      if (data['ret']) {
        document.getElementById(j).style.background = "#adfa97";
      } else {
        document.getElementById(j).style.background = "#fd6642";
      }
    })
    .catch(error => console.log("Error: " + error));
  iterador++;
  mostrarPreguntes(iterador);
}

//Reiniciar el juego al darle a jugar de nuevo
document.getElementById('playAgain').addEventListener("click", reiniciar);
function reiniciar() {
  document.getElementById('resultadoFinal').classList.replace("show", "hidden");
  empezarJuego();
}