<?php
    session_start();

    include "conexio.php";

    error_reporting(E_ALL);
    ini_set("display_errors",'On');
    
    $quantPreg = $_GET['quantPreg'];
    $sql = "SELECT * FROM preguntes ORDER BY RAND() LIMIT $quantPreg;";
    $preguntes = mysqli_query($conn, $sql);
    // $sql = "SELECT * FROM respostes";
    // $respostes = mysqli_query($conn, $sql);

    if(!isset($_SESSION['preguntes']) || !isset($_SESSION['respostes'])){
        $_SESSION['preguntes'] = guardarPregYResp($preguntes,$conn);
    }

    function guardarPregYResp($preguntes, $conn){
        $objPreg = new stdClass();
        $preguntes = convert($preguntes);
        $objPreg->preguntes = $preguntes;
        $respuestas = [];
        $respCorrectas = [];

        foreach($preguntes as $preg){
            $sql = "SELECT * FROM respostes WHERE idPreg = ".$preg['id'];
            $respostesQuery = mysqli_query($conn, $sql);
            $respostes = convert($respostesQuery);
            
            $respuestasSinCorrectas = [];
            foreach($respostes as $resposta){
                $respuestasSinCorrectas[] = $resposta['resposta'];
                if($resposta['correcte'] == 1){
                    $respCorrectas[$preg['id']] = $resposta['resposta'];
                }
            }
            $respuestas[$preg['id']] = $respuestasSinCorrectas;
        }
        $_SESSION['respostes'] = $respCorrectas;
        
        $objPreg->respostes = $respuestas;
        return $objPreg;
    }

    // function mezclarPreguntas($preguntes, $respostes){
    //     $data = [];
    //     $numerosRandom = numerosRandom(0,29);
    //     $pregArray = convert($preguntes);

    //     for($i = 0; $i < $quantPreg; $i++){
    //         $obj = new stdClass();
    //         $obj -> id = $pregArray[$numerosRandom[$i]]['id'];
    //         $obj -> pregunta = $pregArray[$numerosRandom[$i]]['pregunta'];
    //         $respuestas = [];
    //         $index = 0;
    //         foreach($respostes as $rta){
    //             if($rta['idPreg'] == $pregArray[$numerosRandom[$i]]['id']){
    //                 $respuestas[] = $rta['resposta'];
    //                 if($rta['correcte'] == 1){
    //                     $resposta_correcte[] = $index;
    //                 }
    //                 $index++;
    //             }
    //         }
    //         $obj -> respostes = $respuestas;
    //         //$obj -> resposta_correcte = $resposta_correcte;
    //         $obj -> imatge = $pregArray[$numerosRandom[$i]]['imatge'];
    //         $data[] = json_decode(json_encode($obj), true);
    //     }
    //     $_SESSION['respostes'] = $resposta_correcte;

    //     return $data;
    // }

    function convert($querySQL){
        $array = [];
        foreach($querySQL as $query){
            $array[] = $query;
        }
        return $array;
    }

    echo json_encode($_SESSION['preguntes']);
?>