<?php
    session_start();
    $validar = json_decode(file_get_contents('php://input'),true);

    $nPreg = $validar['nPreg']; // = 0
    $valorResp = $validar['idResp']; // = 0

    $isOk = false;
    if($_SESSION['respostes'][$nPreg] == $valorResp){
        $isOk = true;
    }

    echo json_encode($isOk);
?>