<?php
ini_set('session.cookie_secure', '0');

session_start();
header('Access-Control-Allow-Origin: *');


if(!isset($_SESSION['loggedin'])) {
    echo "redirect";
}else{
    echo "all is good";
}
print_r($_SESSION);
?>