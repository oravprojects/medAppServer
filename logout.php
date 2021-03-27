<?php
session_start();
// echo "session id", session_id();
// echo "ini get ", ini_get('session.cookie_domain');

header('Access-Control-Allow-Origin: http://127.0.0.1:5500');
header('Access-Control-Allow-Credentials: true');

session_destroy();
echo "success";
?>