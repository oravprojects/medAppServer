<?php
    session_start();
    include_once "db_connection.php";

    header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
    header('Access-Control-Allow-Credentials: true');
    
    if(isset($_SESSION["id"])){
        $outgoing_id = $_SESSION["id"]; 
        $incoming_id = $_POST["guest_id"];
        $img = $_POST["img"];
        $output = "";
        $conn = OpenCon();

        $sql = "SELECT * FROM chat_messages 
        LEFT JOIN patient on patient.idpatient = chat_messages.outgoing_msg_id
        WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
        OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY idchat_messages ASC";

        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
                if($row['outgoing_msg_id'] == $outgoing_id){
                    $output.= '<div class="chat outgoing">
                                    <div class="details">
                                        <p>'. $row['msg'] .'</p>
                                    </div>
                                </div>';
                }else{
                    $output.= '<div class="chat incoming">
                                    <img src="http://127.0.0.1/healthcareProvider/images/'. $row['image'] .'" alt="">
                                    <div class="details">
                                    <p>'. $row['msg'] .'</p>
                                    </div>
                                </div> ';   
                }
            }
            echo $output;
        } 
        closeCon($conn);
    }else{
        echo "logout";
        exit;
    }
?>