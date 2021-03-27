<?php
// create user in database
include 'db_connection.php';
header('Access-Control-Allow-Origin: *');

$conn = OpenCon();

$first_name = $_POST["patient_fname"];
$last_name = $_POST["patient_lname"];
$email = $_POST["patient_email"];
$phone = $_POST["patient_phone"];

$sql = $conn->prepare("INSERT INTO `patient` (`fname`, `lname`, `email`, `phone`)
VALUES (?,?,?,?)");
$sql->bind_param("ssss", $first_name, $last_name, $email, $phone);
if($sql->execute()){
  echo "New patient created successfully!";
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
};

CloseCon($conn);
?>