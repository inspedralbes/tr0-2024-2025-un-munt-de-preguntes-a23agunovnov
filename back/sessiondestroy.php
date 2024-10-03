<?php
session_start();
$destroyed = new stdClass;
$destroyed->ret = false;
if (session_destroy()) {
    $destroyed->ret = true;
}

echo json_encode($destroyed);
?>