let preguntes;

fetch("../back/admin/getTablePreg.php")
.then(response => response.json())
.then(data => {
    console.log(data);
    mostrar(data);
    preguntes = data;
});

panel = document.getElementById("panel");
htmlString = "";

function mostrar(data){
    data.forEach(preg => {
        htmlString += '<tr class="hover">';
        htmlString += '<td>'+preg['id']+'</td><td>'+preg['pregunta']+'</td><td class="imatgeWidth"><a href="'+preg['imatge']+'" target="_blank">Imatge Link</a></td>';
        htmlString += '<td class="respWidth">';
        preg['respostes'].forEach(resp => {
            htmlString += resp+" ";
        });
        htmlString += '</td>';
        htmlString += '<td class="correcteWidth"><div class="flex-separado"><div>'+preg['resposta_correcte']+'</div><div style="display:flex; align-items:center"><button class="editarbtn" idPreg="'+preg['id']+'">Editar</button><button class="noStyleBTN borrarbtn"><img style="width: 25px" src="img/trash.png" alt="borrar"></button></div></div></td></tr>';
        panel.innerHTML = htmlString;
    });
}

let idPreg;

document.getElementById('panel').addEventListener('click', e => {
    if(e.target.classList.contains('editarbtn')){
      idPreg = e.target.getAttribute('idPreg')-1;
      editar(idPreg);
    }else if(e.target.classList.contains('borrarbtn') || e.target.closest('.borrarbtn')){
        borrarPreg();
    }
});

function editar(idPreg){
    htmlString = "";
    let indexResp = 0;
    document.getElementById('idPreg').innerHTML = '<h3>ID: '+(idPreg+1)+'</h3>'; //CUIDADO CON ESTE ID, LE SUMO UNO PARA QUE COINCIDA CON LA TABLA, AL GUARDAR DATOS, RESTARLE UNO
    document.getElementById('editar').classList.replace("ocultar", "mostrar");
    document.getElementById('pregunta').value = preguntes[idPreg]['pregunta'];
    document.getElementById('imatgeLink').value = preguntes[idPreg]['imatge'];
    preguntes[idPreg]['respostes'].forEach(resp => {
        htmlString += '<input type="text" id="'+indexResp+'" value="'+resp+'">'
        indexResp++;
    });
    document.getElementById('respuestas').innerHTML = htmlString;
    document.getElementById('respCorrecta').value = preguntes[idPreg]['resposta_correcte'];
}

document.getElementById('cancelar1').addEventListener('click', function cerrarEditar(){
    document.getElementById('editar').classList.replace("mostrar", "ocultar");
});

document.getElementById('cancelar2').addEventListener('click', function cerrarBorrar(){
    document.getElementById('borrar').classList.replace("mostrar", "ocultar");
});

function borrarPreg(){
    document.getElementById('borrar').classList.replace("ocultar", "mostrar");
    //Y aca hace el fetch con el valor del ID para borrar esa pregunta con ese ID, sus 4 respuestas de la otra tabla (primero esto)
}

document.getElementById('guardar').addEventListener('click', function guardar(){
    alert(document.getElementById('pregunta').value);
});