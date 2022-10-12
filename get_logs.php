<?php
require("globals.php");

if (empty($dbServer) == false || empty($dbUser) == false || empty($dbPassword) == false || empty($db) == false) {
	$dbDetails = [
		"host" => "$dbServer",
		"user" => "$dbUser",
		"pass" => "$dbPassword",
		"db" => "$db"
	];

	$table = "api_logs";
	$primaryKey = "id";

	$columns = [
		["db" => "id", "dt" => "id"],
		["db" => "curl_type", "dt" => "curl_type"],
		["db" => "file_name", "dt" => "file_name"],
		["db" => "hub_portal_id", "dt" => "hub_portal_id"],
		["db" => "api_origin", "dt" => "api_origin"],
		["db" => "curl_url", "dt" => "curl_url"],
		["db" => "curl_method", "dt" => "curl_method"],
		["db" => "curl_http_code", "dt" => "curl_http_code"],
		[
			"db" => "timestamp",
			"dt" => "timestamp",
			"formatter" => function ($d, $row) {
				return date("d-M-Y h:i:s A", strtotime($d));
			}
		]
	];

	require("ssp.class.php");
	echo json_encode(SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns));
} else {
	echo "DB Credentials NOT Found! Cannot process request!";
	http_response_code(400);
	die;
}