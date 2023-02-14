<?php
ini_set("display_errors", 1);

$dbServer = "92.205.18.107";
$dbUser = "campus_perth";
$dbPassword = "]C2}au7HLiwf";
$dbPort = 3306;
$db = "campus_perth";

$conn = mysqli_connect($dbServer, $dbUser, $dbPassword, $db, $dbPort);

if (!$conn) {
	die("Error: " . mysqli_connect_error());
}

$passwordStr = "trans@123";

$passwordInp = mysqli_real_escape_string($conn, $passwordStr);
$password = password_hash($passwordInp, PASSWORD_DEFAULT);

mysqli_query($conn, "INSERT INTO `users` (`first_name`, `last_name`, `username`, `email`, `password`) VALUES ('Rakesh', 'Jain', 'rakesh345', 'rakeshj@transfunnel.com', '$password')");