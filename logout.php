<?php
include("session.php");

$userId = $_SESSION['id'];
$user = $_SESSION['login_user'];

include("conn.php");
mysqli_query($conn, "UPDATE `user_access_logs` SET `logout` = current_timestamp() WHERE `user_id` = '$userId' AND `user` = '$user' ORDER BY `login` DESC LIMIT 1");

session_destroy();

echo "<script> window.location='login.php'; </script>";