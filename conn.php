<?php
require("globals.php");

if (empty($dbServer) == false || empty($dbUser) == false || empty($dbPassword) == false || empty($db) == false) {
	$conn = mysqli_connect($dbServer, $dbUser, $dbPassword, $db);

	if (!$conn) {
		die("Connection Error: " . mysqli_connect_error());
	}
} else {
	echo "DB Credentials NOT Found! Cannot process request!";
	http_response_code(400);
	die;
}