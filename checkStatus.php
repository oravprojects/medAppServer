<?php
    session_start();

    header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
    header('Access-Control-Allow-Credentials: true');


    if(isset($_SESSION['id'])){
        echo "already logged in";
    }
?>