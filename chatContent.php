<?php
    session_start();
    include_once "db_connection.php";

    header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
    header('Access-Control-Allow-Credentials: true');
    
    if(isset($_SESSION["id"])){
        $outgoing_id = $_SESSION["id"]; 
        $incoming_id = $_POST["guest_id"];
        $message = $_POST["message"];
        $conn = OpenCon();

        if(!empty($message)){
            $sql = mysqli_query($conn, "INSERT INTO chat_messages (incoming_msg_id, outgoing_msg_id, msg) 
            VALUES({$incoming_id}, {$outgoing_id}, '{$message}')") or die();
        }
        closeCon($conn);
    }else{
        echo "logout";
        exit;
    }
?>