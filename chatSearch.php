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

    $stmt = $conn->prepare("SELECT * from patient WHERE NOT idpatient = ? AND (fname LIKE ? OR lname LIKE ?)");
    $stmt->bind_param("iss", $id, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $newSearchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);
        $sql = mysqli_query($conn, "SELECT * from patient WHERE fname LIKE '%{$newSearchTerm}%' OR lname LIKE '%{$newSearchTerm}%'");
        include "chatData.php";
    }else{
        $output .= "no such user found";
    } 
    echo $output;
    $stmt->close();
?>