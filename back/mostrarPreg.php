<?php
// include "conexio.php";

// error_reporting(E_ALL);
// ini_set("display_errors",'On');

// $sql = "SELECT * FROM preguntes";
// $preguntes = mysqli_query($conn, $sql);
// $sql = "SELECT * FROM respostes";
// $respostes = mysqli_query($conn, $sql);

// $data = array();

// foreach($preguntes as $preg){
//     $obj = new stdClass();
//     $obj -> id = $preg['id'];
//     $obj -> pregunta = $preg['pregunta'];
//     $respuestas = array();
//     $index = 0;
//     foreach($respostes as $rta){
//         if($rta['idPreg'] == $preg['id']){
//             $respuestas[] = $rta['resposta'];
//             if($rta['correcte'] == 1){
//                 $resposta_correcte = $index;
//             }
//             $index++;
//         }
//     }
//     $obj -> respostes = $respuestas;
//     $obj -> resposta_correcte = $resposta_correcte;
//     $obj -> imatge = $preg['imatge'];
//     $data[] = json_decode(json_encode($obj), true);
// }
?>