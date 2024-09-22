<?php
    session_start();

    $data = file_get_contents("./data.json");
    $data = json_decode($data, true);

    function mezclarPreguntas($array){
         $numerosRandom = numerosRandom(0,29);
         for($i = 0; $i < 10; $i++){
             $pregArray[] = $array[$numerosRandom[$i]];
         }
         return $pregArray;
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

    // return $pregArray; //No funciona

    echo $preguntas = json_encode($pregArray);
?>