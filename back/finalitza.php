<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

$score = new stdClass();
$score -> numOk = 0;
$score -> total = 0;

if ($data === null) {
    die('Error: No se ha podido decodificar el JSON.');
}

$iterador = 0;
foreach($data as $rta){
    if($_SESSION['respostes'][$iterador] == $rta['idResp']){
        $score->numOk++;
    }
    $score->total++;
    $iterador++;
}

$response = $score;

echo json_encode($response);
?>