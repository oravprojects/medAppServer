<?php
ini_set('session.cookie_secure', '0');

session_start();
header('Access-Control-Allow-Origin: http://localhost:5500');
header('Access-Control-Allow-Credentials: true');

$cookie = session_get_cookie_params();

if(!isset($_SESSION['loggedin'])) {
    echo "failure";
}else{
    echo "success";
}
?>