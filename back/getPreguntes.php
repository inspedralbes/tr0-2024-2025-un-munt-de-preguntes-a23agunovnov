<?php
    session_start();
    
    $data = file_get_contents("./data.json");
    $data = json_decode($data, true);

    function mezclarPreguntas($array){
        $objPreg = array();
        $quantPreg = $_GET['quantPreg'];
        $numerosRandom = numerosRandom(0,29);

        for($i = 0; $i < 10; $i++){
            $preguntas = new stdClass();
            $preguntas->id = $array[$numerosRandom[$i]]['id'];
            $preguntas->pregunta = $array[$numerosRandom[$i]]['pregunta'];
            $preguntas->respostes = $array[$numerosRandom[$i]]['respostes'];
            $preguntas->imatge = $array[$numerosRandom[$i]]['imatge'];
            $objPreg[$i] = $preguntas;
        }
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

    $pregArray = mezclarPreguntas($data['preguntes']);

    echo json_encode($pregArray);
?>