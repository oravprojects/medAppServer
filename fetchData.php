<?php
// create user in database
include_once 'db_connection.php';

header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Credentials: true');

$conn = OpenCon();
session_start();
// echo "this is the array: ";
// foreach(getallheaders() as $name => $value){
//     echo "$name: $value\n";
// }

// $cookie = session_get_cookie_params();
// echo  "<br>";
// print_r($_COOKIE);
// echo  "<br>";
// echo session_save_path();
// echo  "<br>";
// var_dump($_SESSION);
// echo  "<br>";
// var_dump($cookie);


// echo "session id ", session_id(), "<br>";
// echo "ini get ", ini_get('session.cookie_domain'), "<br>";

// exit;

function encrypt($text){
    $plaintext = $text;
    $key = "secret";
    $cipher = "aes-128-ctr";
    if (in_array($cipher, openssl_get_cipher_methods())) {
        $ciphertext = openssl_encrypt($plaintext, $cipher, $key, 0, "BBBBBBBBBBBBBBBB");
        return $ciphertext;
    }
}

function decrypt($text){
    $coded_text = $text;
    $key = "secret";
    $cipher = "aes-128-ctr";
    if (in_array($cipher, openssl_get_cipher_methods())) {
        $original_plaintext = openssl_decrypt($coded_text, $cipher, $key, 0, "BBBBBBBBBBBBBBBB");
        return $original_plaintext;
    }
}

// var_dump($_POST);
if ($_POST["table"] === "reports") {
    $queryDate = $_POST["curr_date"];
    $tomorrowDate = new DateTime($queryDate);
    $tomorrowDate->modify('+1 day');
    $tomorrowDate = $tomorrowDate->format('Y-m-d');
    
    $stmt = $conn->prepare("SELECT report.idreport, report.type, report.patient, report.date, report.notes, caregiver.fname, caregiver.lname  
    FROM report join caregiver on caregiver = idcaregiver WHERE `date` >= ? and `date` < ?");
    $stmt->bind_param("ss", $queryDate, $tomorrowDate);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        for($i=0; $i<count($data); $i++){ 
            $newVal = encrypt($data[$i]['idreport']);
            $data[$i]['idreport'] = $newVal;
        };
        echo json_encode($data);
    } else {
        echo "Error: " . $stmt . "<br>" . mysqli_error($conn);
    };
};

if ($_POST["table"] === "reports_add") {
    $timeOffset = $_POST["curr_date"];
    $client_time = gmdate("Y-m-d H:i:s", strtotime("+{$timeOffset} hours"));
    // $user_id = $_POST["user_id"];
    $user_id = $_SESSION["id"];
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
    $rep_id = decrypt($_POST["id"]);
    $sql = $conn->prepare("DELETE FROM `report` WHERE `idreport` = ?");
    $sql->bind_param("i", $rep_id);
    if ($sql->execute()) {
        echo "Report deleted successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    };
};

if ($_POST["table"] === "reports_edit") {
    $rep_id = decrypt($_POST["id"]);
    $notes = $_POST["notes"];
    $sql = $conn->prepare("UPDATE `report` SET `notes` = ? WHERE `idreport` = ?");
    $sql->bind_param("si", $notes, $rep_id);
    if ($sql->execute()) {
        echo "Report edited successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    };
};

if ($_POST["table"] === "getReminders") {
    $user_id = $_SESSION["id"];
    $timeOffset = $_POST["curr_date"];
    $client_time = gmdate("Y-m-d H:i:s", strtotime("+{$timeOffset} hours")); 
    $tomorrowDate = new DateTime($client_time);
    $tomorrowDate->modify('+1 day');
    $tomorrowDate = $tomorrowDate->format('Y-m-d H:i:s');
    $viewed = 0;
    $orderField = "due";
    // var_dump($tomorrowDate);

    $stmt = $conn->prepare("SELECT reminder.idreminder, reminder.caregiver, reminder.entered, 
    reminder.due, reminder.text, caregiver.fname, caregiver.lname  
    FROM reminder join caregiver on caregiver = idcaregiver WHERE `caregiver` = ? and `viewed` = ? and `due` <= ? ORDER BY $orderField");
    $stmt->bind_param("iis", $user_id, $viewed, $tomorrowDate);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($data);
    } else {
        echo "Error: " . $stmt . "<br>" . mysqli_error($conn);
    };
};

if ($_POST["table"] === "setReminder") {
    $user_id = $_SESSION["id"];
    $timeOffset = $_POST["curr_date"];
    $client_time = gmdate("Y-m-d H:i:s", strtotime("+{$timeOffset} hours")); 
    // var_dump($tomorrowDate);
    $due = $_POST["due"];
    $text = $_POST["text"];
    $stmt = $conn->prepare("INSERT INTO `reminder` (`caregiver`, `entered`, `due`, `text`) 
    VALUES (?,?,?,?)");
    $stmt->bind_param("isss", $user_id, $client_time, $due, $text);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt . "<br>" . mysqli_error($conn);
    };
};

if ($_POST["table"] === "reminderViewed") {
    // var_dump($tomorrowDate);
    $viewed = true;
    $idreminder = $_POST["id"];
    $stmt = $conn->prepare("UPDATE reminder SET viewed = $viewed WHERE idreminder = $idreminder");
    // $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt . "<br>" . mysqli_error($conn);
    };
};

if ($_POST["table"] === "setApp") {
    $user_id = $_SESSION["id"];
    $timeOffset = $_POST["curr_date"];
    $client_time = gmdate("Y-m-d H:i:s", strtotime("+{$timeOffset} hours")); 
    // var_dump($tomorrowDate);
    $start = gmdate("Y-m-d H:i:s", ($_POST["start"]+$timeOffset*60*60));
    // echo "this is start: ", $start;
    $end = gmdate("Y-m-d H:i:s", ($_POST["end"]+$timeOffset*60*60));
    // echo " this is end:",  $end;
    $notes = $_POST["notes"];
    $patient = $_POST["pId"];
    $stmt = $conn->prepare("INSERT INTO `appointment` (`caregiver`, `entered`, `start`, `end`, `notes`, `patient`) 
    VALUES (?,?,?,?,?,?)");
    // var_dump($stmt);
    // return;
    $stmt->bind_param("issssi", $user_id, $client_time, $start, $end, $notes, $patient);
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt . "<br>" . mysqli_error($conn);
    };
};

if ($_POST["table"] === "getApp") {
    // var_dump($_POST);
    $user_id = $_SESSION["id"];
    $timeOffset = $_POST["curr_date"];
    $client_time = gmdate("Y-m-d H:i:s", strtotime("+{$timeOffset} hours"));
    $client_date = new DateTime($client_time);
    $client_date->setTime(0, 0, 0); 
    $client_date = $client_date->format('Y-m-d H:i:s');
    $tomorrowDate = new DateTime($client_time);
    $tomorrowDate->modify('+1 day');
    $tomorrowDate->setTime(0, 0, 0);
    $tomorrowDate = $tomorrowDate->format('Y-m-d H:i:s');
    // echo $client_date, " ", $tomorrowDate, " ", $user_id;

    $stmt = $conn->prepare("SELECT appointment.idappointment, appointment.caregiver, appointment.patient, appointment.start, appointment.end, appointment.notes, appointment.entered, patient.fname, patient.lname  
    FROM appointment join patient on patient = idpatient WHERE `start` >= ? and `start` < ? and caregiver = ?");
    $stmt->bind_param("ssi", $client_date, $tomorrowDate, $user_id);
    // var_dump($stmt);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        // var_dump($data);
        echo json_encode($data);
    } 
    else {
        echo "Error: " . $stmt . "<br>" . mysqli_error($conn);
    };
};


CloseCon($conn);
