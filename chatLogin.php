<?php
    session_start();
    include_once "db_connection.php";

    header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
    header('Access-Control-Allow-Credentials: true');

    $conn = OpenCon();

    $email = $_POST["email"];
    $password = $_POST["password"];

    if(!empty($email) && !empty($password)){
        if ($stmt = $conn->prepare("SELECT * from patient WHERE email = ?")) {
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
         
            if ($result->num_rows > 0) {
                $data = $result->fetch_all(MYSQLI_ASSOC);
                if (password_verify($password, $data[0]["password"])) {
                    // Verification success! User has logged-in!
                    // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
                    // session_regenerate_id();
                    $_SESSION['id'] = $data[0]["idpatient"];
                    $fname = $data[0]["fname"];
                    $lname = $data[0]["lname"];
                    $status = $data[0]["status"];
                    $image = $data[0]["image"];
                    
                    $id = $_SESSION['id'];
                    $status = "active";
        
                    $stmt = $conn->prepare("UPDATE `patient` SET `status` = ? WHERE idpatient = ?");
                    $stmt->bind_param("si", $status, $id);
                    $stmt->execute();
                    $result = $stmt->affected_rows;
                    if ($result > 0) {
                    $res = array("login" => "success", "fname"=> $fname, "lname" => $lname, "status" => $status, "image" => $image);
                    // var_dump($_SESSION);
                    echo json_encode($res);
                }else{
                    echo "something went wrong . . .";
                }
                } else {
                    // Incorrect password
                    // $res = array("login" => "failure");
                    // echo json_encode($res);
                    echo 'Incorrect username and/or password!';
                }
            } else {
                // Incorrect username
                // var_dump($result);
                echo 'Incorrect username and/or password!';
            }
        
            $stmt->close();
        }
    }else{
        echo "all input fields are required";
    }
?>