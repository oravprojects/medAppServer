<?php
    session_start();
    include_once "db_connection";

    header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
    header('Access-Control-Allow-Credentials: true');

    $conn = OpenCon();

    $email = $_POST["email"];
    $password = $_POST["password"];
    
    if(!empty($email) && !empty($password)){
        if ($stmt = $conn->prepare('SELECT idcaregiver, password, fname, lname, image, status, FROM caregiver WHERE username = ?')) {
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
            $stmt->bind_param('s', $_POST['username']);
            $stmt->execute();
            // Store the result so we can check if the account exists in the database.
            $stmt->store_result();
        
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $password, $fname, $lname, $status, $image);
                $stmt->fetch();
                // Account exists, now we verify the password.
                // Note: remember to use password_hash in your registration file to store the hashed passwords.
                if (password_verify($_POST['password'], $password)) {
                    // Verification success! User has logged-in!
                    // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
                    // session_regenerate_id();
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['username'] = $_POST['username'];
                    $_SESSION['id'] = $id;
                    $_SESSION['fname'] = $fname;
                    $_SESSION['lname'] = $lname;
                    $res = array("login" => "success", "fname"=> $fname, "lname" => $lname, "status" => $status, "image" => $image);
                    // var_dump($_SESSION);
                    echo json_encode($res);
                } else {
                    // Incorrect password
                    $res = array("login" => "failure");
                    echo json_encode($res);
                }
            } else {
                // Incorrect username
                echo 'Incorrect username and/or password!';
            }
        
            $stmt->close();
        }
    }else{
        echo "all input fields are required";
    }
?>