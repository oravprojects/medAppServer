<?php

function OpenCon(){
    include_once 'dbVar.php';

    // Connect to MySQL
    // $conn = new mysqli($dbServername, $dbUsername, $dbPassword);
    // if ($conn->connect_error) {
    //     die("Connection failed: " . $conn->connect_error);
    // }

    // // If database does not exist create one
    // if (!mysqli_select_db($conn, $dbName)){
    //     $sql = "CREATE DATABASE ".$dbName;
    //     if ($conn->query($sql) === TRUE) {
    //         echo "Database created successfully";
    //     }else {
    //         echo "Error creating database: " . $conn->error;
    //     }
    // } else {
    //     $conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName) 
    //     or die("Connect failed: %s\n". $conn -> error);
    //     return $conn;
    // }
    $conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName)
    or die("Connect failed: %s\n" . $conn->error);
    return $conn;
}

function CloseCon($conn){
    include 'dbVar.php';

    $conn->close();
}
// mysqli_close($conn);
?>