<?php
include_once("session.php");

$userId = $_SESSION['id'];
$user = $_SESSION['login_username'];

include_once("conn.php");

$http_cookie = (isset($_SERVER['HTTP_COOKIE']) && empty($_SERVER['HTTP_COOKIE']) == false) ? addslashes($_SERVER['HTTP_COOKIE']) : "";
$remote_address = (isset($_SERVER['REMOTE_ADDR']) && empty($_SERVER['REMOTE_ADDR']) == false) ? addslashes($_SERVER['REMOTE_ADDR']) : "";
$remote_port = (isset($_SERVER['REMOTE_PORT']) && empty($_SERVER['REMOTE_PORT']) == false) ? addslashes($_SERVER['REMOTE_PORT']) : "";
$ua_platform = (isset($_SERVER['HTTP_SEC_CH_UA_PLATFORM']) && empty($_SERVER['HTTP_SEC_CH_UA_PLATFORM']) == false) ? addslashes(trim(str_replace("\"", "", $_SERVER['HTTP_SEC_CH_UA_PLATFORM']))) : "";
$ua_version = (isset($_SERVER['HTTP_SEC_CH_UA']) && empty($_SERVER['HTTP_SEC_CH_UA']) == false) ? addslashes(implode(" | ", explode(", ", str_replace("\"", "", $_SERVER['HTTP_SEC_CH_UA'])))) : "";
$user_agent = (isset($_SERVER['HTTP_USER_AGENT']) && empty($_SERVER['HTTP_USER_AGENT']) == false) ? addslashes($_SERVER['HTTP_USER_AGENT']) : "";

mysqli_query($conn, "INSERT INTO `admin_access_logs` (`user_id`, `user`, `http_cookie`, `remote_address`, `remote_port`, `ua_platform`, `ua_version`, `user_agent`, `action`) VALUES ('$userId', '$user', '$http_cookie', '$remote_address', '$remote_port', '$ua_platform', '$ua_version', '$user_agent', 'logout')");

session_destroy();

echo "<script> window.location='login.php'; </script>";
