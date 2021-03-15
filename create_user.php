<?php
// create user in database
include 'db_connection.php';
header('Access-Control-Allow-Origin: *');

$conn = OpenCon();

$first_name = $conn -> real_escape_string($_POST["fname"]);
$last_name = $conn -> real_escape_string($_POST["lname"]);
$username = $conn -> real_escape_string($_POST["email"]);
$password = $_POST["password"];
$password = password_hash($password, PASSWORD_DEFAULT);
$phone = $conn -> real_escape_string($_POST["phone"]);

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