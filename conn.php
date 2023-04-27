<?php
include_once("globals.php");
$conn = mysqli_connect($dbServer, $dbUser, $dbPassword, $db, 3306);

if (!$conn) {
	die("Error: " . mysqli_connect_error());
}
