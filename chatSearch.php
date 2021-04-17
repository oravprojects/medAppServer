<?php
    session_start();
    include_once "db_connection.php";

    header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
    header('Access-Control-Allow-Credentials: true');

    $conn = OpenCon();
    $searchTerm = $_POST['searchTerm'];
    $searchTerm = "%$searchTerm%";
    $id = $_SESSION['id'];
    $output = "";

    $stmt = $conn->prepare("SELECT * from patient WHERE fname LIKE ? OR lname LIKE ?");
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $output .= "found user";
    }else{
        $output .= "no such user found";
    } 
    echo $output;
    $stmt->close();
?>