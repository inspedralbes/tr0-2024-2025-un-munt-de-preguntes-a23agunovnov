<?php
// Recibir y procesar el JSON enviado desde el fetch
$preguntas = file_get_contents("./data.json");
$preguntas = json_decode($preguntas, true);

$data = json_decode(file_get_contents('php://input'), true);



$quantRespOK = "";
$score = new stdClass();
$score -> numOk = 0;
$score -> total = 0;
foreach($data as $rta){
    if($preguntas['preguntes'][$rta['idPreg']-1]['resposta_correcta'] == $rta['idResp']){
        //$quantRespOK.= 'Pregunta ID: '.$preguntas['preguntes'][$rta['idPreg']-1]['id'].'| Respuesta seleccionada: '.$rta['idResp'].'| Rta correcta: '.$preguntas['preguntes'][$rta['idPreg']-1]['resposta_correcta'].'             ';
        $score->numOk++;
    }
    $score->total++;
}

$response = $score;

echo json_encode($response);
?>