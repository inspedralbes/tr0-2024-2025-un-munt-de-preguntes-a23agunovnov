<?php
    require_once "config.php";

    $conn = new mysqli($host,$user,$pass,$dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>