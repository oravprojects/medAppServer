<?php
    session_start();
    include_once "db_connection.php";

    header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
    header('Access-Control-Allow-Credentials: true');
    
    if(isset($_SESSION["id"])){
        var_dump($_POST);
        var_dump($_SESSION);
        $conn = OpenCon();
        $outgoing_id = $_SESSION["id"]; 
        $incoming_id = mysqli_escape_string($conn, $_POST["guest_id"]);
        $message = mysqli_escape_string($conn, $_POST["message"]);

        if(!empty($message)){
            $sql = mysqli_query($conn, "INSERT INTO chat_messages (incoming_msg_id, outgoing_msg_id, msg) 
            VALUES({$incoming_id}, {$outgoing_id}, '{$message}')") or die();
        }
        // closeCon($conn);
    }else{
        echo "logout";
        exit;
    }
?>