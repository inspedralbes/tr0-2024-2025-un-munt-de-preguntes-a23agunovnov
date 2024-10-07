<?php
include "../conexio.php";

$data = json_decode(file_get_contents("php://input"), true);

$pregunta = mysqli_real_escape_string($conn, $data['pregunta']);
$imatge = $data['imatge'];
$respuestas = $data['respuestas'];
$correcte = $data['correcte'];
$ok = new stdClass;
$ok->ret = false;

$sql = "INSERT INTO preguntes(pregunta, imatge) VALUES ('$pregunta', '$imatge')";
if (!mysqli_query($conn, $sql)) {
    echo json_encode("ERROR: " . mysqli_error($conn));
}
$idPreg = mysqli_insert_id($conn);
foreach ($respuestas as $rta) {
    $isOk = ($rta == $correcte) ? 1 : 0;
    $sql = "INSERT INTO respostes(idPreg, resposta, correcte) VALUES ($idPreg, '$rta', $isOk)";
    if (!mysqli_query($conn, $sql)) {
        echo json_encode("ERROR: " . mysqli_error($conn));
    } else {
        $ok->ret = true;
    }
}

$conn->close();
echo json_encode($ok);
?>