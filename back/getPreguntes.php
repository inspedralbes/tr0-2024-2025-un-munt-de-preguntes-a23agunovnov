<?php
    session_start();

    $data = file_get_contents("./data.json");
    $data = json_decode($data, true);

    if(!isset($_SESSION['preguntes']) || !isset($_SESSION['respostes'])){
        $_SESSION['preguntes'] = mezclarPreguntas($data['preguntes']);
    }

    function mezclarPreguntas($array){
        $objPreg = array();
        $quantPreg = $_GET['quantPreg']+1;
        $numerosRandom = numerosRandom(0,29);
        $respostesOk = array();

        for($i = 0; $i < $quantPreg; $i++){
            $preguntas = new stdClass();
            $preguntas->id = $array[$numerosRandom[$i]]['id'];
            $preguntas->pregunta = $array[$numerosRandom[$i]]['pregunta'];
            $preguntas->respostes = $array[$numerosRandom[$i]]['respostes'];
            $respostesOk[$i] = $array[$numerosRandom[$i]]['resposta_correcta'];
            $preguntas->imatge = $array[$numerosRandom[$i]]['imatge'];
            $objPreg[$i] = $preguntas;
        }
        $_SESSION['respostes'] = $respostesOk;

        return $objPreg;
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

    echo json_encode($_SESSION['preguntes']);
?>