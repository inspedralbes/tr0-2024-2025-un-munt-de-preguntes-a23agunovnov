<?php
    session_start();

    include "conexio.php";

    error_reporting(E_ALL);
    ini_set("display_errors",'On');

    $quantPreg = $_GET['quantPreg'];
    $sql = "SELECT * FROM preguntes ORDER BY RAND() LIMIT $quantPreg;";
    $preguntes = mysqli_query($conn, $sql);

    // if(!isset($_SESSION['preguntes']) || !isset($_SESSION['respostes'])){
        $_SESSION['preguntes'] = guardarPregYResp($preguntes,$conn);
    // }

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

    function convert($querySQL){
        $array = [];
        foreach($querySQL as $query){
            $array[] = $query;
        }
        return $array;
    }

    echo json_encode($_SESSION['preguntes']);
?>