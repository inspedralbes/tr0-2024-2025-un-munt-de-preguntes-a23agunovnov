<?php
include 'conexio.php';

$data = file_get_contents("https://opentdb.com/api.php?amount=30&category=11&difficulty=easy&type=multiple");
$data = json_decode($data, true);

$sql = "DROP TABLE IF EXISTS respostes";
$conn->query($sql);
$sql = "DROP TABLE IF EXISTS preguntes";
$conn->query($sql);

createTables($conn);

function createTables($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS `preguntes`(
            `id` INT NOT NULL AUTO_INCREMENT ,
            `pregunta` VARCHAR(255) NOT NULL ,
            PRIMARY KEY (`id`)
        )";
    if ($conn->query($sql)) {
        echo "Table 'preguntes' created sucessfully";
    } else {
        die("Error: " . $conn->error);
    }

    $sql = "CREATE TABLE IF NOT EXISTS `respostes`(
            `id` INT NOT NULL AUTO_INCREMENT ,
            `idPreg` INT NOT NULL ,
            `resposta` VARCHAR(50) NOT NULL ,
            `correcte` BOOLEAN NOT NULL ,
            PRIMARY KEY (`id`) ,
            FOREIGN KEY (`idPreg`) REFERENCES `preguntes`(`id`)
        )";

    if (mysqli_query($conn, $sql)) {
        echo "<br>Table 'respostes' created sucessfully";
    } else {
        die("Error: " . $conn->error);
    }
}

foreach($data['results'] as $row){
    echo $row['question'].'<br>';
}

//Insertar preguntas
foreach ($data['results'] as $row) {
    $pregunta = $row['question'];
    $pregunta = mysqli_real_escape_string($conn, $pregunta);

    $sql = "INSERT INTO preguntes(pregunta) VALUES ('$pregunta')";

    if (!mysqli_query($conn, $sql)) {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        exit();
    }
    $idPregs[] = mysqli_insert_id($conn);
}


//Insertar respuestas
foreach ($data['results'] as $key => $value) {
    $aux=[];
    $idPreg = $idPregs[$key];
    $idResp = 0;
    $aux = guardarResp($value);
    foreach ($aux as $rta) {
        $resposta = mysqli_real_escape_string($conn, $rta);
        $correcte = ($rta == $value['correct_answer']) ? 1 : 0;
        $sql = "INSERT INTO respostes(idPreg, resposta, correcte) VALUES ($idPreg, '$resposta', $correcte)";
        if (!mysqli_query($conn, $sql)) {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            exit();
        }
        $idResp+=1;
    }
}

function guardarResp($pregunta){
    $aux = [];
    foreach ($pregunta['incorrect_answers'] as $key => $value){
        $aux[$key] = $value;
    }
    $aux[3] = $pregunta['correct_answer'];
    shuffle($aux);
    return $aux;
}

$conn->close();
?>