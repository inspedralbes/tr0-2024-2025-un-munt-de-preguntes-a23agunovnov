<?php
include "../conexio.php";

$data = json_decode(file_get_contents("php://input"), true);
$idPreg = $data;

$ok = new stdClass;
$ok->ret = false;

$sql = "DELETE FROM respostes WHERE idPreg = $idPreg";
if (mysqli_query($conn, $sql)) {
    $sql = "DELETE FROM preguntes WHERE id = $idPreg";
    if (mysqli_query($conn, $sql)) {
        $ok->ret = true;
    }
}

$conn->close();
echo json_encode($ok);
?>