<?php
    include_once "db_connection.php";
    
    header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
    header('Access-Control-Allow-Credentials: true');
    /** @var $conn db_connection */
    $conn = OpenCon();

    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $phone = "123456";
    $random_id = rand(time(), 10000000);

    if (!empty($fname) && !empty($lname) && !empty($email) && !empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sql = $conn->prepare("SELECT username FROM caregiver WHERE username = ?");
            $sql->bind_param('s', $email);
            $sql->execute();
            // Store the result so we can check if the account exists in the database.
            $sql->store_result();
            if ($sql->num_rows > 0) {
                echo "email already exists";
            } else {
                if (isset($_FILES['image'])) {
                    $img_name = $_FILES['image']['name'];
                    $img_type = $_FILES['image']['type'];
                    $tmp_name = $_FILES['image']['tmp_name'];

                    $img_explode = explode('.', $img_name);
                    $img_ext = end($img_explode);

                    $extensions = ['png', 'jpeg', 'jpg'];
                    if (in_array($img_ext, $extensions) === true) {
                        $time = time();
                        $new_img_name = $time . $img_name;
                        if (move_uploaded_file($tmp_name, "images/" . $new_img_name)) {
                            $status = "active";
                            // $random_id = rand(time(), 10000000);
                            $sql2 = $conn->prepare("INSERT INTO `patient` (`fname`, `lname`, `email`, `password`, `phone`, `image`, `status`)
                                    VALUES (?,?,?,?,?,?,?)");
                            $sql2->bind_param("sssssss", $fname, $lname, $email, $password, $phone, $new_img_name, $status);
                            if ($sql2->execute()) {
                                $sql3 = $conn->prepare("SELECT * from patient WHERE email = ?");
                                $sql3->bind_param("s", $email);
                                $sql3->execute();
                                $result = $sql3->get_result();
                                $data = $result->fetch_all(MYSQLI_ASSOC);
                                // $sql3->store_result();
                                if ($result->num_rows > 0) {
                                    $_SESSION['id'] = $data[0]['idpatient'];
                                    // echo json_encode($data);
                                    echo "success";
                                }
                            } else {
                                echo "Error: " . $sql2 . "<br/>" . mysqli_error($conn);
                            }
                            CloseCon($conn);
                        }
                    } else {
                        echo "image file must be of type png, jpeg, or jpg";
                    }
                } else {
                    echo "please select an image file";
                }
            }
        } else {
            echo "$email is not a valid email";
        }
    } else {
        echo "all input fields are required";
    }

    // function genUniqueId($conn, $random_id, $fname, $lname, $email, $password, $phone){
    //     $sql4 = $conn->prepare("SELECT unique_id FROM user WHERE unique_id = ?");
    //     $sql4->bind_param('i', $random_id);
    //     $sql4->execute();
    //     // Store the result so we can check if the account exists in the database.
    //     $sql4->store_result();
    //     if ($sql4->num_rows > 0) {
    //         $random_id = rand(time(), 10000000);
    //         genUniqueId($conn, $random_id, $fname, $lname, $email, $password, $phone);
    //     }else{
    //         if (!empty($fname) && !empty($lname) && !empty($email) && !empty($password)) {
    //             $password = password_hash($password, PASSWORD_DEFAULT);
    //             if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //                 $sql = $conn->prepare("SELECT username FROM caregiver WHERE username = ?");
    //                 $sql->bind_param('s', $email);
    //                 $sql->execute();
    //                 // Store the result so we can check if the account exists in the database.
    //                 $sql->store_result();
    //                 if ($sql->num_rows > 0) {
    //                     echo "email already exists";
    //                 } else {
    //                     if (isset($_FILES['image'])) {
    //                         $img_name = $_FILES['image']['name'];
    //                         $img_type = $_FILES['image']['type'];
    //                         $tmp_name = $_FILES['image']['tmp_name'];
        
    //                         $img_explode = explode('.', $img_name);
    //                         $img_ext = end($img_explode);
        
    //                         $extensions = ['png', 'jpeg', 'jpg'];
    //                         if (in_array($img_ext, $extensions) === true) {
    //                             $time = time();
    //                             $new_img_name = $time . $img_name;
    //                             if (move_uploaded_file($tmp_name, "images/" . $new_img_name)) {
    //                                 $status = "active";
    //                                 // $random_id = rand(time(), 10000000);
    //                                 $sql2 = $conn->prepare("INSERT INTO `patient` (`fname`, `lname`, `email`, `password`, `phone`, `image`, `status`)
    //                                         VALUES (?,?,?,?,?,?,?)");
    //                                 $sql2->bind_param("sssssss", $fname, $lname, $email, $password, $phone, $new_img_name, $status);
    //                                 if ($sql2->execute()) {
    //                                     $sql3 = $conn->prepare("SELECT * from patient WHERE email = ?");
    //                                     $sql3->bind_param("s", $email);
    //                                     $sql3->execute();
    //                                     $result = $sql3->get_result();
    //                                     $data = $result->fetch_all(MYSQLI_ASSOC);
    //                                     // $sql3->store_result();
    //                                     if ($result->num_rows > 0) {
    //                                         $_SESSION['id'] = $data[0]['idpatient'];
    //                                         // echo json_encode($data);
    //                                         echo "success";
    //                                     }
    //                                 } else {
    //                                     echo "Error: " . $sql2 . "<br/>" . mysqli_error($conn);
    //                                 }
    //                                 CloseCon($conn);
    //                             }
    //                         } else {
    //                             echo "image file must be of type png, jpeg, or jpg";
    //                         }
    //                     } else {
    //                         echo "please select an image file";
    //                     }
    //                 }
    //             } else {
    //                 echo "$email is not a valid email";
    //             }
    //         } else {
    //             echo "all input fields are required";
    //         }
    //     } 
    // }
?>