async function imprimirPreg(){
    await fetch("https://opentdb.com/api.php?amount=5&category=11&difficulty=easy&type=multiple")
    .then(response => response.json())
    .then(data => {
        mostrarPreg(data);
    })
}

imprimirPreg();




function mostrarPreg(preguntes){
    let indexPreg = 0;
    preguntes['results'].forEach(preg => {
        htmlString = `<h3 id="pregunta">${preg['question']}</h3><div style="display: flex; flex-direction: column;" id="${indexPreg}"></div>`
        document.getElementById('contenedor').innerHTML += htmlString;
        respuestas = guardarPregs(preg);
        respuestas.forEach(resp => {
            htmlString = `<input type="button" id="" value="${resp}">`
            document.getElementById(indexPreg).innerHTML += htmlString;
        })
        indexPreg++;
    });
}

function guardarPregs(pregunta){
    let aux = new Array;
    let iterador = 0;
    pregunta['incorrect_answers'].forEach(resp => {
        aux[iterador] = resp;
        iterador++;
    })
    aux[iterador] = pregunta['correct_answer'];
    aux = shuffle(aux);
    return aux;
}

function shuffle(array) {
    var currentIndex = array.length, temporaryValue, randomIndex;
  
    while (0 !== currentIndex) {
  
      // Seleccionar un elemento sin mezclar...
      randomIndex = Math.floor(Math.random() * currentIndex);
      currentIndex -= 1;
  
      // E intercambiarlo con el elemento actual
      temporaryValue = array[currentIndex];
      array[currentIndex] = array[randomIndex];
      array[randomIndex] = temporaryValue;
    }
  
    return array;
  }