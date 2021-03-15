<?php
// Test connection to database
include 'db_connection.php';
$conn = OpenCon();
echo "Connected Successfully";
CloseCon($conn);
?>