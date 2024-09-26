<?php
    include 'conexio.php';

    $data = file_get_contents("./data.json");
    $data = json_decode($data, true);

    createTables($conn);

    function createTables($conn){
        $sql = "CREATE TABLE IF NOT EXISTS `preguntes`(
            `id` INT NOT NULL AUTO_INCREMENT ,
            `pregunta` VARCHAR(500) NOT NULL ,
            `imatge` VARCHAR(1000) NOT NULL ,
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
        $id = $row['id'];
        $pregunta = $row['pregunta'];
        $imatge = $row['imatge'];

        $sql = "INSERT INTO preguntes(id, pregunta, imatge)
                    VALUES ($id, '$pregunta', '$imatge')";

        if(mysqli_query($conn, $sql)){
            echo '<br>'.$row['id'].' - '.$row['pregunta'];
        }else{
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            exit();
        }
    }
?>