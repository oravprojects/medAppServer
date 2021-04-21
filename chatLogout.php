<?php
    session_start();
    include_once "db_connection.php";

    header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
    header('Access-Control-Allow-Credentials: true');

    $conn = OpenCon();
    
    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
        $status = "offline";
        
        $stmt = $conn->prepare("UPDATE `patient` SET `status` = ? WHERE idpatient = ?"); 
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        $result = $stmt->affected_rows;
        if ($result > 0) {
            session_unset();
            session_destroy();
            echo "success";
        }else{
            echo "failure";
        }
    }else{
        header("location: http://127.0.0.1:5500/chatLogin.html");
    }
?>