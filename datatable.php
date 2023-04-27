<?php
require_once("globals.php");

date_default_timezone_set("Asia/Kolkata");

if (empty($dbServer) == false || empty($dbUser) == false || empty($dbPassword) == false || empty($db) == false) {
	$dbDetails = [
		"host" => "$dbServer",
		"user" => "$dbUser",
		"pass" => "$dbPassword",
		"db" => "$db"
	];

	if (isset($_GET['table']) && empty($_GET['table']) == false) {
		switch ($_GET['table']) {
			case 1:
				$table = "api_logs";
				$primaryKey = "id";

				$columns = [
					["db" => "id", "dt" => "id"],
					["db" => "app_name", "dt" => "app_name"],
					["db" => "hub_portal_id", "dt" => "hub_portal_id"],
					["db" => "curl_type", "dt" => "curl_type"],
					["db" => "api_origin", "dt" => "api_origin"],
					["db" => "file_name", "dt" => "file_name"],
					["db" => "curl_method", "dt" => "curl_method"],
					["db" => "curl_http_code", "dt" => "curl_http_code"],
					[
						"db" => "timestamp",
						"dt" => "timestamp",
						"formatter" => function ($d, $row) {
							return date("d-M-Y h:i:s A T", strtotime($d));
						}
					]
				];
				break;
			case 2:
				$table = "webhooks";
				$primaryKey = "id";

				$columns = [
					["db" => "id", "dt" => "id"],
					["db" => "app_name", "dt" => "app_name"],
					["db" => "hub_portal_id", "dt" => "portal"],
					["db" => "source", "dt" => "source"],
					["db" => "type", "dt" => "type"],
					["db" => "file_name", "dt" => "file_name"],
					["db" => "status", "dt" => "status"],
					["db" => "timestamp", "dt" => "timestamp", "formatter" => function ($d, $row) {
						return date("d-M-Y h:i:s A T", strtotime($d));
					}],
					["db" => "last_modified", "dt" => "modified", "formatter" => function ($d, $row) {
						return date("d-M-Y h:i:s A T", strtotime($d));
					}]
				];
				break;
			case 3:
				$table = "uploads";
				$primaryKey = "id";

				$columns = [
					["db" => "id", "dt" => "id"],
					["db" => "file_name", "dt" => "file_name"],
					["db" => "file_type", "dt" => "file_type"],
					["db" => "file_size", "dt" => "file_size"],
					["db" => "uploaded", "dt" => "uploaded", "formatter" => function ($d, $row) {
						return date("d-M-Y h:i:s A T", strtotime($d));
					}],
					["db" => "last_modified", "dt" => "modified", "formatter" => function ($d, $row) {
						return date("d-M-Y h:i:s A T", strtotime($d));
					}]
				];
				break;
		}

		require_once("ssp.class.php");
		echo json_encode(SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns));
	} else {
		echo "Table type NOT Found! Cannot process request!";
		http_response_code(400);
		die;
	}
} else {
	echo "DB Credentials NOT Found! Cannot process request!";
	http_response_code(400);
	die;
}
