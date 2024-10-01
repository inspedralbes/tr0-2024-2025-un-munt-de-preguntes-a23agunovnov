<?php
    $host = "localhost";
    $user = "preguntas";
    $pass = "12345";
    $dbname = "TR0";

    $conn = new mysqli($host, $user, $pass, $dbname);

    if(!$conn){
        die("Error: ". mysqli_error($conn));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de administrador</title>
</head>
<body>
    <style>
        td input{
            max-width: 100px;
        }
        td{
            max-width: 100px;
        }
    </style>
    <h1 style="text-align:center">Panel de administrador</h1>
    <hr>
    <?php
        $sql = "SELECT * FROM preguntes";
        $preguntes = $conn->query($sql);
        $sql = "SELECT * FROM respostes";
        $respostes = $conn->query($sql);
    ?>
    <table style="width:100%" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pregunta</th>
                <th>Imágen</th>
                <th>Respostes</th>
                <th>Resposta correcte</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($preguntes as $preg){
                echo '<tr class="hover">';
                echo '<td>'.$preg['id'].'</td>';
                echo '<td><input type="text" value="'.$preg['pregunta'].'"></td>';
                echo '<td><input type="text" value="'.$preg['imatge'].'"></td>';
                echo '<td>';
                foreach($respostes as $rta){
                    if($rta['idPreg'] == $preg['id']){
                        if($rta['correcte'] == 1){
                            $rta_correcte = $rta['resposta'];
                        }
                        echo '<input type="text" value="'.$rta['resposta'].'">';
                    }
                }
                echo '</td>';
                echo '<td><div style="display:flex; justify-content:space-between"><div><input type="text" value="'.$rta_correcte.'"></div><div style="display:flex; align-items:center"><button idPreg="'.$preg['id'].'">Editar</button><img style="width: 15px" src="../web/imatges/ellipsis.png" alt="edit"></div></div></td>';
                echo '</tr>';
            }
        ?>
        </tbody>
    </table>
</body>
</html>