<?php
session_start();
include_once "db_connection.php";

header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Credentials: true');

if(!isset($_SESSION["id"])){
    header("location: http://127.0.0.1:5500/chatLogin.html");
    exit;
}

$conn = OpenCon();

$id = $_SESSION['id'];

    $sql = mysqli_query($conn, "SELECT * from patient where idpatient = $id");
    $output = "";
    
    if(mysqli_num_rows($sql) > 0){
        $row = mysqli_fetch_assoc($sql);
    }elseif(mysqli_num_rows($sql) > 1){
        include "chatData.php";
    }
?>