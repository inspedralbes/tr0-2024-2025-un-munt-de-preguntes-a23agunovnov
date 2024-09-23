<?php
    $data = file_get_contents('./data.json');
    $data = json_decode($data, true);
    $validar = json_decode(file_get_contents('php://input'),true);
    
    $idPreg = $validar['idPreg']-1;
    $idResp = $validar['idResp'];

    $isOk = false;
    if($data['preguntes'][$idPreg]['resposta_correcta'] == $idResp){
        $isOk = true;
    }

    echo json_encode($isOk);
?>