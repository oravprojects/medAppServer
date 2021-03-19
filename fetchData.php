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

    // $stmt = $conn->prepare("SELECT * FROM report WHERE `date` >= ? and `date` < ?");
    $stmt = $conn->prepare("SELECT report.idreport, report.type, report.patient, report.date, report.notes, caregiver.fname, caregiver.lname  FROM report join caregiver on caregiver = idcaregiver WHERE `date` >= ? and `date` < ?");
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

if ($_POST["table"] === "reports_add") {
    $timeOffset = $_POST["curr_date"];
    $client_time = gmdate("Y-m-d H:i:s", strtotime("+{$timeOffset} hours"));
    $user_id = $conn -> real_escape_string($_POST["user_id"]);
    $notes = $conn -> real_escape_string($_POST["notes"]);
    $type = $conn -> real_escape_string($_POST["type"]);
    if($type === "daily_report"){
        $type = 1;
    }
    
    $sql = $conn->prepare("INSERT INTO `report` (`type`, `caregiver`, `date`, `notes`)
    VALUES (?,?,?,?)");
    $sql->bind_param("iiss", $type, $user_id, $client_time, $notes);
    if ($sql->execute()) {
        echo "Report added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    };
};

if ($_POST["table"] === "reports_del") {
    $rep_id = $conn -> real_escape_string($_POST["id"]);
    echo $rep_id;
    $sql = $conn->prepare("DELETE FROM `report` WHERE `idreport` = ?");
    $sql->bind_param("i", $rep_id);
    if ($sql->execute()) {
        echo "Report deleted successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    };
};

CloseCon($conn);
