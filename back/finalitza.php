<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

$score = new stdClass();
$score -> numOk = 0;
$score -> total = 0;
$score -> puntos = 0;

if ($data === null) {
    die('Error: No se ha podido decodificar el JSON.');
}

$iterador = 0;
foreach($data as $rta){
    if($rta['nPreg'] != -1 && $rta['idResp'] != -1){
        if($_SESSION['respostes'][$rta['nPreg']] == $rta['idResp']){
            $score->numOk++;
            $score->puntos+=10;
        }else{
            $score->puntos-=5;
        }
        $score->total++;
        $iterador++;
    }
}

echo json_encode($score);
?>