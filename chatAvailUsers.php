<?php
    session_start();
    include_once "db_connection.php";

    header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
    header('Access-Control-Allow-Credentials: true');

    $conn = OpenCon();
    if(!isset($_SESSION['id'])){
        echo "logout";
        exit;
    }
    $id = $_SESSION['id'];

    // $sql = mysqli_query($conn, "SELECT * from patient where status = 'active' and idpatient != $id");
    $sql = mysqli_query($conn, "SELECT * from patient where idpatient != $id");
    $output = "";
    
    if(mysqli_num_rows($sql) === 1){
        $output .= "No users online";
    }elseif(mysqli_num_rows($sql) > 1){
        include "chatData.php";
    } 
    echo $output;
    $sql->close();
?>