<?php
    session_start();
    $validar = json_decode(file_get_contents('php://input'),true);

    $idPreg = $validar['idPreg'];
    $idResp = $validar['idResp'];

    $isOk = false;
    if($_SESSION['respostes'][$idPreg] == $idResp){
        $isOk = true;
    }

    echo json_encode($isOk);
?>