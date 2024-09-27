<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "TR0";

    $conn = new mysqli($host,$user,$pass,$dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>