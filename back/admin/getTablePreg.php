<?php
// error_reporting(E_ALL);
// ini_set("display_errors", 'On');

require_once("../conexio.php");

if (!$conn) {
    die("Error: " . mysqli_error($conn));
}

$sql = "SELECT * FROM preguntes";
$preguntes = $conn->query($sql);
$sql = "SELECT * FROM respostes";
$respostes = $conn->query($sql);

$data = joinResult($preguntes, $respostes);

function joinResult($preguntes, $respostes)
{
    $data = [];
    $pregArray = convert($preguntes);

    foreach($pregArray as $preg) {
        $obj = new stdClass();
        $obj->id = $preg['id'];
        $obj->pregunta = $preg['pregunta'];
        $respuestas = [];
        foreach ($respostes as $rta){
            if ($rta['idPreg'] == $preg['id']) {
                if ($rta['correcte'] == 1) {
                    $resposta_correcte = $rta['resposta'];
                }
                $respuestas[] = $rta['resposta'];
            }
        }
        $obj->respostes = $respuestas;
        $obj->resposta_correcte = $resposta_correcte;
        $obj->imatge = $preg['imatge'];
        $data[] = json_decode(json_encode($obj), true);
    }
    return $data;
}

function convert($querySQL)
{
    $array = [];
    foreach ($querySQL as $query) {
        $array[] = $query;
    }
    return $array;
}

echo json_encode($data);

?>