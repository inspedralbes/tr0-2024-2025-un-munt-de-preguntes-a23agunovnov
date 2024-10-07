<?php
    include "../conexio.php";

    $data = json_decode(file_get_contents('php://input'), true);
    $pregunta = mysqli_real_escape_string($conn, $data['pregunta']);
    $ok = new stdClass();

    updatePreg($data, $pregunta, $conn);
    $ok->ret = updateResp($data, $conn);

    function updatePreg($data, $pregunta, $conn){
        $sql = "UPDATE preguntes SET pregunta = '$pregunta', imatge = '".$data['imatge']."' WHERE id = ".$data['idPreg'];
        mysqli_query($conn, $sql);
    }
    
    function respExiste($data){
        $existe = false;
        for($i = 0; $i < count($data['respostes']); $i++){
            if($data['correcte'] == $data['respostes'][$i]){
                echo "Correcta: ".$data['correcte']." y Respuesta con la que coincide: ".$data['respostes'];
                $existe = true;
            }
        }
        return $existe;
    }

    function updateResp($data, $conn){
        if(respExiste($data)){
            for($i = 0; $i < count($data['respostes']); $i++){
                $sql = "SELECT * FROM respostes WHERE idPreg = ".$data['idPreg']." AND resposta = '".$data['original'][$i]."'"; //Obtener ID de las preguntas a modificar
                $idRta = mysqli_fetch_assoc(mysqli_query($conn, $sql));
                $idRta = $idRta['id'];
                
                $isOk = ($data['respostes'][$i] == $data['correcte']) ? 1 : 0;
                $sql = "UPDATE respostes SET resposta = '".$data['respostes'][$i]."', correcte = $isOk WHERE id = $idRta";
                $ok = (mysqli_query($conn, $sql)) ? true : false;
            }
        }else{
            $ok = false;
        }
        return $ok;
    }

    echo json_encode($ok);
?>