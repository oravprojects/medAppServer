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
    
    $stmt = $conn->prepare("SELECT report.idreport, report.type, report.patient, report.date, report.notes, caregiver.fname, caregiver.lname  FROM report join caregiver on caregiver = idcaregiver WHERE `date` >= ? and `date` < ?");
    $stmt->bind_param("ss", $queryDate, $tomorrowDate);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($data);
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    };
};

if ($_POST["table"] === "reports_add") {
    $timeOffset = $_POST["curr_date"];
    $client_time = gmdate("Y-m-d H:i:s", strtotime("+{$timeOffset} hours"));
    $user_id = $_POST["user_id"];
    $notes = $_POST["notes"];
    $type = $_POST["type"];
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
    $rep_id = $_POST["id"];
    $sql = $conn->prepare("DELETE FROM `report` WHERE `idreport` = ?");
    $sql->bind_param("i", $rep_id);
    if ($sql->execute()) {
        echo "Report deleted successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    };
};

if ($_POST["table"] === "reports_edit") {
    $rep_id = $_POST["id"];
    $notes = $_POST["notes"];
    $sql = $conn->prepare("UPDATE `report` SET `notes` = ? WHERE `idreport` = ?");
    $sql->bind_param("si", $notes, $rep_id);
    if ($sql->execute()) {
        echo "Report edited successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    };
};

CloseCon($conn);
