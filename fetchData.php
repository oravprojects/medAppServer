<?php
// create user in database
include 'db_connection.php';
header('Access-Control-Allow-Origin: *');

$conn = OpenCon();

// var_dump($_POST);
if ($_POST["table"] === "reports") {
    $queryDate = $_POST["curr_date"];
    $tomorrowDate = new DateTime($queryDate);
    $tomorrowDate->modify('+1 day');
    $tomorrowDate = $tomorrowDate->format('Y-m-d');
    // echo $_SERVER['REQUEST_TIME'];
    // echo date("Y-m-d H:i:s", $_SERVER['REQUEST_TIME']);
    // echo " query date: ", $queryDate, " tomorrow date: ", $tomorrowDate;
    $sql = "SELECT * FROM caregiver.report WHERE `date` >= $queryDate and `date` < $tomorrowDate";
    // echo $sql;

    $stmt = $conn->prepare("SELECT * FROM report WHERE `date` >= ? and `date` < ?");
    $stmt->bind_param("ss", $queryDate, $tomorrowDate);
    if ($stmt->execute()) {
        // echo "reports fetched successfully";
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        // var_dump($data);
        echo json_encode($data);
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    };
};

CloseCon($conn);
