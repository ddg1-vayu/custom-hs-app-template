<?php
require("globals.php");

if (empty($dbServer) == false || empty($dbUser) == false || empty($dbPassword) == false || empty($db) == false) {
	$dbDetails = [
		"host" => "$dbServer",
		"user" => "$dbUser",
		"pass" => "$dbPassword",
		"db" => "$db"
	];

	$table = "webhooks";
	$primaryKey = "id";

	$columns = [
		["db" => "id", "dt" => "id"],
		["db" => "source", "dt" => "source"],
		["db" => "type", "dt" => "type"],
		["db" => "file", "dt" => "file"],
		["db" => "timestamp", "dt" => "timestamp", "formatter" => function ($d, $row) {
			return date("d-M-Y h:i:s A", strtotime($d));
		}]
	];

	require("ssp.class.php");
	echo json_encode(SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns));
} else {
	echo "DB Credentials NOT Found! Cannot process request!";
	http_response_code(400);
	die;
}