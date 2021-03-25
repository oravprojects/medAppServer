<?php
session_start();
// echo "session id ", session_id(), "<br>";
// echo "ini get ", ini_get('session.cookie_domain'), "<br>";

header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Credentials: true');

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

if(!isset($_SESSION['loggedin'])) {
    echo "failure";
}else{
    echo "success";
}
?>