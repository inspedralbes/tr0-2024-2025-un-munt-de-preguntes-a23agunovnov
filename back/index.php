<?php
    // session_start();
    // $data = file_get_contents("data.json");
    // $data = json_decode($data, true);

    // function mezclarPreguntas($array){
    //     $numerosRandom = numerosRandom(0,29);
    //     for($i = 0; $i < 10; $i++){
    //         $pregArray[] = $array[$numerosRandom[$i]];
    //     }
    //     return $pregArray;
    // }

    // function numerosRandom($min, $max){
    //     $arrayRandom = array();
    //     $iterador = $min;
    //     while($iterador <= $max){
    //         $randomID = random_int($min,$max);
    //         if(!in_array($randomID, $arrayRandom)){
    //             $arrayRandom[$iterador] = $randomID;
    //             $iterador++;
    //         }
    //     }
    //     return $arrayRandom;
    // }

    // $pregArray = mezclarPreguntas($data['preguntes']);

    if(!isset($_SESSION['preguntes'])){
        $_SESSION['preguntes'] = $pregArray;
        $_SESSION['index'] = 0;
        $_SESSION['score'] = new stdClass();
        $_SESSION['score'] -> correctes = 0;
        $_SESSION['score'] -> total = sizeof($_SESSION['preguntes']);
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
            echo $index;
            echo $_SESSION['index'];
            echo '<h2>'.$_SESSION['preguntes'][$index]['pregunta'].'</h2>';
        }else{
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
            //header("Refresh: 1; url=".$_SERVER['PHP_SELF']);
        }
        $_SESSION['index']++;
    }
    
    function verificar($i, $j){
        if($_SESSION['preguntes'][$i]['resposta_correcta'] == $j){
            $_SESSION['score']->correctes+=1;
        }
        echo '<p>Respuestas correctas: '.$_SESSION['score']->correctes.'/'.$_SESSION['score']->total.'</p><br>';
    }
?>