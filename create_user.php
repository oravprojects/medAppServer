<?php
// create user in database
include 'db_connection.php';
// header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Credentials: true');

/** @var $conn db_connection */
$conn = OpenCon();

$first_name = $_POST["fname"];
$last_name = $_POST["lname"];
$username = $_POST["email"];
$password = $_POST["password"];
$password = password_hash($password, PASSWORD_DEFAULT);
$phone = $_POST["phone"];

echo $first_name;
echo $last_name;
echo $username;
echo $password;
echo $phone;

// if (password_verify ($password, $hash)){
//   echo "password verified";
// };

$sql = $conn->prepare("INSERT INTO `caregiver` (`fname`, `lname`, `username`, `password`, `phone`)
VALUES (?,?,?,?,?)");
$sql->bind_param("sssss", $first_name, $last_name, $username, $password, $phone);
if($sql->execute()){
  echo "New user created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
};

CloseCon($conn);
?>