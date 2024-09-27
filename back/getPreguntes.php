<?php
    session_start();

    include "conexio.php";

    error_reporting(E_ALL);
    ini_set("display_errors",'On');
    
    $sql = "SELECT * FROM preguntes";
    $preguntes = mysqli_query($conn, $sql);
    $sql = "SELECT * FROM respostes";
    $respostes = mysqli_query($conn, $sql);

    if(!isset($_SESSION['preguntes']) || !isset($_SESSION['respostes'])){
        $_SESSION['preguntes'] = mezclarPreguntas($preguntes, $respostes);
    }

    function mezclarPreguntas($preguntes, $respostes){
        $data = [];
        $quantPreg = $_GET['quantPreg']+1;
        $numerosRandom = numerosRandom(0,29);
        $pregArray = convert($preguntes);

        for($i = 0; $i < $quantPreg; $i++){
            $obj = new stdClass();
            $obj -> id = $pregArray[$numerosRandom[$i]]['id'];
            $obj -> pregunta = $pregArray[$numerosRandom[$i]]['pregunta'];
            $respuestas = [];
            $index = 0;
            foreach($respostes as $rta){
                if($rta['idPreg'] == $pregArray[$numerosRandom[$i]]['id']){
                    $respuestas[] = $rta['resposta'];
                    if($rta['correcte'] == 1){
                        $resposta_correcte[] = $index;
                    }
                    $index++;
                }
            }
            $obj -> respostes = $respuestas;
            //$obj -> resposta_correcte = $resposta_correcte;
            $obj -> imatge = $pregArray[$numerosRandom[$i]]['imatge'];
            $data[] = json_decode(json_encode($obj), true);
        }
        $_SESSION['respostes'] = $resposta_correcte;

        return $data;
    }

    function numerosRandom($min, $max){
         $arrayRandom = array();
         $iterador = $min;
         while($iterador <= $max){
             $randomID = random_int($min,$max);
             if(!in_array($randomID, $arrayRandom)){
                 $arrayRandom[$iterador] = $randomID;
                 $iterador++;
             }
         }
         return $arrayRandom;
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