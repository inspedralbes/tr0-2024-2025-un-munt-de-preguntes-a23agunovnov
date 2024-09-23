<?php
$preguntas = file_get_contents("./data.json");
$preguntas = json_decode($preguntas, true);

$data = json_decode(file_get_contents('php://input'), true);

$score = new stdClass();
$score -> numOk = 0;
$score -> total = 0;

if ($data === null) {
    die('Error: No se ha podido decodificar el JSON.');
}

foreach($data as $rta){
    if($preguntas['preguntes'][$rta['idPreg']-1]['resposta_correcta'] == $rta['idResp']){
        $score->numOk++;
    }
    $score->total++;
}

$response = $score;

echo json_encode($response);
?>