<?php
while($row = mysqli_fetch_assoc($sql)){
    $sql2 = "SELECT * FROM chat_messages WHERE (incoming_msg_id = {$row['idpatient']}
    OR outgoing_msg_id = {$row['idpatient']}) AND (outgoing_msg_id = {$id}
    OR incoming_msg_id = {$id}) ORDER BY idchat_messages DESC LIMIT 1";
    $query2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($query2);
    if(mysqli_num_rows($query2) > 0){
        $result = $row2['msg'];
        ($id == $row2['outgoing_msg_id']) ? $you = "You: " : $you = "";
    }else{
        $result = "no messages";
        $you = "";
    }
    
    //trim message after 28 chars
    (strlen($result) > 28) ? $msg = substr($result, 0, 28) : $msg = $result;
    // check user status online or offline
    ($row['status'] == "active") ? $offline = "" : $offline = "offline";

    $output .= '<a href="http://127.0.0.1:5500/chatArea.html?user_id='.$row['idpatient'].'&img='.$row['image'].'">
                    <div class="content">
                        <img src="http://127.0.0.1/healthcareProvider/images/' .$row['image'] .'" alt="">
                        <div class="details">
                            <span>'. $row['fname'] . " " . $row['lname'] .'</span>
                            <p>'. $you . $msg .'</p>
                        </div>
                    </div>
                    <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                </a>';
}
