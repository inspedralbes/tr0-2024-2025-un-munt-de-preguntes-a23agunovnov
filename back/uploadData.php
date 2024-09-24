<?php
    include 'conexio.php';

    $data = file_get_contents("./data.json");
    $data = json_decode($data, true);

    foreach($data['preguntes'] as $row){
        $id = $row['id'];
        $pregunta = $row['pregunta'];
        //$respostes = $row['respostes'];
        $resposta_correcta = $row['resposta_correcta'];
        $imatge = $row['imatge'];

        $insertsql = "INSERT INTO preguntes(id, pregunta, respostes, resposta_correcta, imatge)
                    VALUES ($id, '$pregunta', 'test', $resposta_correcta, '$imatge')";

        if(mysqli_query($conn, $insertsql)){
            echo "Upload is OK";
        }else{
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
?>