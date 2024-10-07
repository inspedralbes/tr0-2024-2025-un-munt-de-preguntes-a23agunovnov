<?php
session_start();
$validar = json_decode(file_get_contents('php://input'), true);

$nPreg = $validar['nPreg'];
$valorResp = $validar['idResp'];
$isOk = new stdClass;

$isOk->ret = false;
if ($_SESSION['respostes'][$nPreg] == $valorResp) {
    $isOk->ret = true;
}

echo json_encode($isOk);
?>