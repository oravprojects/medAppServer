<?php
while($row = mysqli_fetch_assoc($sql)){
    $output .= '<a href="http://127.0.0.1:5500/chatArea.html?user_id='.$row['idpatient'].'">
                    <div class="content">
                        <img src="http://127.0.0.1/healthcareProvider/images/' .$row['image'] .'" alt="">
                        <div class="details">
                            <span>'. $row['fname'] . " " . $row['lname'] .'</span>
                            <p></p>
                        </div>
                    </div>
                    <div class="status-dot"><i class="fas fa-circle"></i></div>
                </a>';
}
