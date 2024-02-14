<?php
// Start session
session_start();

// Clear all session variables
$_SESSION = array();

// Delete the session cookie
if(isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Delete the JWT token cookie if exists
if(isset($_COOKIE['jwt_token'])) {
  setcookie('jwt_token', '', time() - 3600, '/');
  setcookie('jwt_token_expire_time', '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Prevent caching of this page
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

// Redirect to the login page
header("Location: login.php");
exit;
?>
