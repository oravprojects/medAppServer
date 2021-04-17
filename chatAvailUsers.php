<?php
    session_start();
    include_once "db_connection.php";

    header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
    header('Access-Control-Allow-Credentials: true');

    $conn = OpenCon();
    $id = $_SESSION['id'];

    $sql = mysqli_query($conn, "SELECT * from patient where status = 'active' and idpatient != $id");
    $output = "";
    
    if(mysqli_num_rows($sql) === 1){
        $output .= "No users online";
    }elseif(mysqli_num_rows($sql) > 1){
        while($row = mysqli_fetch_assoc($sql)){
            $output .= '<a href="#">
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
    } 
    echo $output;
    $sql->close();
?>