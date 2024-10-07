<?php

include "../conexio.php";

$id = json_decode(file_get_contents('php://input'), true)['id'];

$sql = "SELECT resposta, correcte FROM respostes WHERE idPreg = $id";

$result = mysqli_query($conn, $sql);
$respuestas=[];
while($row = mysqli_fetch_array($result)){
    $respuestas[]=$row;
}

$respIncorrectes = [];
foreach($respuestas as $key => $value){
    if($value['correcte'] == 0){
        $respIncorrectes[] = $key;
    }
}

shuffle($respIncorrectes);
array_pop($respIncorrectes);


echo json_encode($respIncorrectes);