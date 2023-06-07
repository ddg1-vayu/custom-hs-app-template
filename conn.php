<?php
include_once("globals.php");

try {
	$conn = mysqli_connect(db_server, db_user, db_password, db, 3306);
} catch (mysqli_sql_exception $e) {
	echo "Error: " . $e->getMessage();
	die;
}