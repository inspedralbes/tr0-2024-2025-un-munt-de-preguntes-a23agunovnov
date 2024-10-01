<?php
    include 'conexio.php';

    $data = file_get_contents("./data.json");
    $data = json_decode($data, true);

    $sql = "DROP TABLE IF EXISTS respostes";
    $conn -> query($sql);
    $sql = "DROP TABLE IF EXISTS preguntes";
    $conn -> query($sql);

    createTables($conn);

    function createTables($conn){
        $sql = "CREATE TABLE IF NOT EXISTS `preguntes`(
            `id` INT NOT NULL AUTO_INCREMENT ,
            `pregunta` VARCHAR(255) NOT NULL ,
            `imatge` VARCHAR(500) NOT NULL ,
            PRIMARY KEY (`id`)
        )";
        if($conn ->query($sql)){
            echo "Table 'preguntes' created sucessfully";
        }else{
            die("Error: ".$conn -> error);
        }

        $sql = "CREATE TABLE IF NOT EXISTS `respostes`(
            `id` INT NOT NULL AUTO_INCREMENT ,
            `idPreg` INT NOT NULL ,
            `resposta` VARCHAR(50) NOT NULL ,
            `correcte` BOOLEAN NOT NULL ,
            PRIMARY KEY (`id`) ,
            FOREIGN KEY (`idPreg`) REFERENCES `preguntes`(`id`)
        )";
    
        if(mysqli_query($conn, $sql)){
            echo "<br>Table 'respostes' created sucessfully";
        }else{
            die("Error: ".$conn -> error);
        }
    }

    foreach($data['preguntes'] as $row){
        $pregunta = $row['pregunta'];
        $pregunta = mysqli_real_escape_string($conn, $pregunta);
        $imatge = $row['imatge'];

        $sql = "INSERT INTO preguntes(pregunta, imatge) VALUES ('$pregunta', '$imatge')";

        if(!mysqli_query($conn, $sql)){
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            exit();
        }
    }

    foreach($data['preguntes'] as $row){
        $idPreg = $row['id'];
        $idResp = -1;
        foreach($row['respostes'] as $rta){
            $idResp += 1;
            $resposta = $rta;
            $correcte = 0;
            if($idResp == $row['resposta_correcta']){
                $correcte = 1;
            }
            $sql = "INSERT INTO respostes(idPreg, resposta, correcte) VALUES ($idPreg, '$resposta', $correcte)";
            if(!mysqli_query($conn, $sql)){
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                exit();
            }
        }
    }
?>