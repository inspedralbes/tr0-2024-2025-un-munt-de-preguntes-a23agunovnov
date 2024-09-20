<?php
    session_start();
    $data = file_get_contents("data.json");
    $data = json_decode($data, true);

    function mezclarPreguntas($array){
        $numerosRandom = numerosRandom(0,9);
        foreach($numerosRandom as $num){
            $pregArray[] = $array[$num];
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

    if(!isset($_SESSION['preguntes'])){
        $_SESSION['preguntes'] = $pregArray;
        $_SESSION['index'] = 0;
        $_SESSION['score'] = new stdClass();
        $_SESSION['score'] -> correctes = 0;
        $_SESSION['score'] -> total = sizeof($_SESSION['preguntes']);
        /*$_SESSION['arrayRtas'] = array();
        $_SESSION['IDsRta'] = new stdClass();
        $_SESSION['IDsRta'] -> idPreg = -1;
        $_SESSION['IDsRta'] -> idRta = -1;*/
    }

    $index = $_SESSION['index'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas</title>
</head>
<body>
    <center>
    <h1 style="text-align: center">UMDP</h1>
    <?php
        if($index < 10){
            echo 'Preguntas correctas: '.$_SESSION['score']->correctes.'<br>';
            //Para probar correcto funcionamiento, descomentar la linea siguiente y corroborar con las respuestas correctas
            //echo "Pregunta ".$_SESSION['index']." | Respuesta correcta: ".$_SESSION['preguntes'][$index]['resposta_correcta'];
            echo '<h2>'.$_SESSION['preguntes'][$index]['pregunta'].'</h2>';
        }else{
            echo '<p>Has respondido <span style="font-weight: bold">'.$_SESSION['score']->correctes.'</span> preguntas correctas de <span style="font-weight: bold">'.$_SESSION['score']->total.'</span></p>';
            echo '<a href="index.php"><button>Volver a jugar</button></a>';
            session_destroy();
            exit(); // Detenemos la ejecución para no seguir mostrando más preguntas
        }
    
        echo '<form action="" method="post">';
    
        for($j = 0; $j < sizeof($_SESSION['preguntes'][$index]['respostes']); $j++){
            echo '<button type="submit" name="boton" value="'.$j.'">'.$_SESSION['preguntes'][$index]['respostes'][$j].'</button>';
        }
    
        echo '</form>';
    ?>
    </center>
</body>
</html>

<?php
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if(isset($_POST['boton'])){
            verificar($_SESSION['index'],$_POST['boton']);
            $_SESSION['index']++;
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        
    }
    
    function verificar($i, $j){
        if($_SESSION['preguntes'][$i]['resposta_correcta'] == $j){
            $_SESSION['score']->correctes+=1;
        }
        /*$_SESSION['IDsRta']->idPreg = $i;
        $_SESSION['IDsRta']->idRta = $j;
        $_SESION['arrayRtas'][0] = $_SESSION['IDsRta'];*/
    }
?>