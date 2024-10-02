let preguntes = {};

fetch("../back/admin/getTablePreg.php")
.then(response => response.json())
.then(data => {
    mostrar(data),
    preguntes = toObject(data)
});

const panel = document.getElementById("panel");
let idPreg;

//MOSTRAR LAS PREGUNTAS DENTRO DE LA TABLA
function mostrar(data){
    htmlString = "";
    data.forEach(preg => {
        htmlString += '<tr class="hover">';
        htmlString += '<td>'+preg['id']+'</td><td>'+preg['pregunta']+'</td><td class="imatgeWidth"><a href="'+preg['imatge']+'" target="_blank">Imatge Link</a></td>';
        htmlString += '<td class="respWidth">';
        preg['respostes'].forEach(resp => {
            htmlString += resp+" ";
        });
        htmlString += '</td>';
        htmlString += '<td class="correcteWidth"><div class="flex-separado"><div>'+preg['resposta_correcte']+'</div><div style="display:flex; align-items:center"><button class="editarbtn" idPreg="'+preg['id']+'">Editar</button><button class="noStyleBTN borrarbtn" idPreg="'+preg['id']+'"><img style="width: 25px" src="img/trash.png" alt="borrar"></button></div></div></td></tr>';
        panel.innerHTML = htmlString;
    });
}

function toObject(data){
    let aux = {};
    data.forEach(preg => {
        aux[preg.id] = preg;
    })
    return aux;
}

//Cerrar ventana de editar
document.getElementById('cancelar1').addEventListener('click', function cerrarEditar(){
    document.getElementById('editar').classList.replace("mostrar", "ocultar");
});
//Cerrar ventana de borrar
document.getElementById('cancelar2').addEventListener('click', function cerrarBorrar(){
    document.getElementById('borrar').classList.replace("mostrar", "ocultar");
});
//Cerrar ventana de crear
document.getElementById('cancelar3').addEventListener('click', function cerrarBorrar(){
    document.getElementById('crear').classList.replace("mostrar", "ocultar");
});

//Escuchando clicks tanto en editar como en borrar, para llevar a una función u a otra
document.getElementById('panel').addEventListener('click', e => {
    idPreg = e.target.getAttribute('idPreg');
    if(e.target.classList.contains('editarbtn')){
        editarPreg(idPreg);
    }else if(e.target.classList.contains('borrarbtn') || e.target.closest('.borrarbtn')){
        borrarPreg(idPreg);
    }
});

document.getElementById('crearbtn').addEventListener('click', crearPreg);


let respOriginal = [];

let objPreg = new Object();
function editarPreg(idPreg){
    //let preguntesMap = {};
    htmlString = "";
    let indexResp = 0;
    document.getElementById('idPreg').innerHTML = '<h3>ID: '+(idPreg)+'</h3>'; //CUIDADO CON ESTE ID, LE SUMO UNO PARA QUE COINCIDA CON LA TABLA, AL GUARDAR DATOS, RESTARLE UNO
    document.getElementById('editar').classList.replace("ocultar", "mostrar");
    document.getElementById('pregunta').value = preguntes[idPreg]['pregunta'];
    document.getElementById('imatgeLink').value = preguntes[idPreg]['imatge'];
    preguntes[idPreg]['respostes'].forEach(resp => {
        htmlString += '<input type="text" id="'+indexResp+'" value="'+resp+'">'
        respOriginal[indexResp] = resp;
        indexResp++;
    });
    document.getElementById('respuestas').innerHTML = htmlString;
    document.getElementById('respCorrecte').value = preguntes[idPreg]['resposta_correcte'];
    document.getElementById('guardar').setAttribute('idPreg', idPreg);

    document.getElementById('guardar').addEventListener('click', function guardar(){
        objPreg.idPreg = document.getElementById('guardar').getAttribute('idPreg');
        objPreg.pregunta = document.getElementById('pregunta').value;
        objPreg.imatge = document.getElementById('imatgeLink').value;
        objPreg.respostes = [];
        objPreg.original = [];
        for (let i = 0; i < 4; i++) {
            objPreg.respostes[i] = document.getElementById(i).value;
            objPreg.original[i] = respOriginal[i];
        }
        //Hacer un objPreg.respostes.includes(objPreg.correcte) y que no deje enviarlo directamente, en lugar de hacerlo en el back
        objPreg.correcte = document.getElementById('respCorrecte').value;
        
        fetch('../back/admin/update.php', {
            method: 'POST',
            body: JSON.stringify(objPreg)
        })
        .then(response => response.json())
        .then(data => {
            aviso(data);
        });
    });
}

function borrarPreg(idPreg){
    document.getElementById('borrar').classList.replace("ocultar", "mostrar");
    //Y aca hace el fetch con el valor del ID para borrar esa pregunta con ese ID, sus 4 respuestas de la otra tabla (primero esto)
}

function crearPreg(){
    document.getElementById('crear').classList.replace("ocultar", "mostrar");
    document.getElementById('aceptarCrear').addEventListener('click', guardarPreg);
}

function guardarPreg(){
    let newPreg = new Object();
    newPreg.pregunta = document.getElementById('nuevaPreg').value;
    newPreg.imatge = document.getElementById('nuevaImatge').value;
    newPreg.respuestas = [];
    for (let i = 0; i < 4; i++) {
        newPreg.respuestas[i] = document.getElementById(i).value;
    }
    newPreg.correcte = document.getElementById('correcte').value;
    let ok = newPreg.respuestas.includes(newPreg.correcte);
    if(ok){
        fetch("../back/admin/create.php",{
            method: 'POST',
            body: JSON.stringify(newPreg)
        })
        .then(response => response.json())
        .then(data => {
            console.log(data)
        });
    }else{
        alert("La respuesta correcta no coincide");
    }
}

function aviso(ok){
    if(ok){
        document.getElementById('editar').classList.replace("mostrar", "ocultar");
        document.getElementById('aviso').classList.replace("ocultar", "mostrar");
        //mostrar(preguntes);
        setTimeout(function(){document.getElementById('aviso').classList.replace("mostrar", "ocultar")}, 1000)
    }else{
        console.log("ERROR");
        alert("Ha ocurrido un error");
    }
};