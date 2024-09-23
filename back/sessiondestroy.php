<?php
    session_start();

    $destroyed = false;
    if(session_destroy()){
        $destroyed = true;
    }

    echo json_encode($destroyed);
?>