<?php
    session_start();
    include_once "db_connection.php";

    header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
    header('Access-Control-Allow-Credentials: true');

    $conn = OpenCon();
    $id = $_SESSION['id'];

    $stmt = $conn->prepare("SELECT * from patient WHERE idpatient = ?"); 
        // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $fname = $data[0]["fname"];
            $lname = $data[0]["lname"];
            $status = $data[0]["status"];
            $image = $data[0]["image"];
            $res = array("login" => "success", "fname"=> $fname, "lname" => $lname, "status" => $status, "image" => $image);
            // var_dump($_SESSION);
            echo json_encode($res);
        }else{
            echo "something went wrong . . . $id";
        }
    $stmt->close();
?>