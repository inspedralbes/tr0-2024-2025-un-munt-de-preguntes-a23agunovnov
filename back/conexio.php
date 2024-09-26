<?php
    $host = "localhost";
    $user = "preguntas";
    $pass = "respostes";
    $dbname = "TR0";

    $conn = new mysqli($host,$user,$pass,$dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
   
    echo "Connected successfully<br>";
?>