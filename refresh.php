<?php
// ini_set("display_errors", 1);
$fileName = pathinfo(__FILE__, PATHINFO_FILENAME);

echo "<style> *{font-family: 'Fira Sans', Calibri, Arial, sans-serif;} </style>";

$conn = mysqli_connect("host", "user", "password", "database");
if (!$conn) {
	$error = "Database Error - " . mysqli_connect_error();
	$file = "logs/connect_err_" . time() . ".txt";
	$newFile = fopen($file, "w+") or die("Unable to open file!");
	fwrite($newFile, $error);
	fclose($newFile);
	http_response_code(400);
	die();
} else {
	$getInstalls = mysqli_query($conn, "SELECT `hub_portal_id`, `refresh_token` FROM `app_installs` WHERE `status` = 'Active' ORDER BY `last_installed`");

	if (mysqli_num_rows($getInstalls) > 0) {
		while ($rows = mysqli_fetch_array($getInstalls)) {
			$portalId = $rows['hub_portal_id'];
			$refreshToken = $rows['refresh_token'];
			$result = tokenRefresh($portalId, $refreshToken, $fileName);
		}
		if (!empty($result)) {
			if ($result[0] == 1) {
				http_response_code(200);
				echo "Access token updated for all installs";
			} else {
				http_response_code(400);
				echo "HTTP Response (" . $result[2] . ")\n";
				print_r($result[1]);
			}
		}
	} else {
		echo "No active installs found!";
	}
}

/**
 * Refresh the Access Tokens for active installs of the App
 *
 * @param int $portalId
 * @param string $refreshToken
 * @param string $fileName
 * @return array $result
 */
function tokenRefresh($portalId, $refreshToken, $fileName) {
	$origin = "HubSpot";
	$method = "POST";
	$type = "Token Refresh";

	$returnResult = 0;

	$clientId = "";
	$clientSecret = "";

	$data = "grant_type=refresh_token&client_id=$clientId&client_secret=$clientSecret&refresh_token=$refreshToken";

	$endpoint = "https://api.hubapi.com/oauth/v1/token";
	$customHeaders = ["Content-Type: application/x-www-form-urlencoded;charset=utf-8"];

	$curlQuery = curl_init();
	curl_setopt($curlQuery, CURLOPT_URL, $endpoint);
	curl_setopt($curlQuery, CURLOPT_HTTPHEADER, $customHeaders);
	curl_setopt($curlQuery, CURLOPT_CUSTOMREQUEST, $method);
	curl_setopt($curlQuery, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curlQuery, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curlQuery, CURLOPT_HTTP_VERSION, "CURL_HTTP_VERSION_1_1");
	$curlResponse = curl_exec($curlQuery);
	$httpCode = curl_getinfo($curlQuery, CURLINFO_RESPONSE_CODE);
	curl_close($curlQuery);

	global $conn;
	mysqli_query($conn, "INSERT INTO `api_logs` (`hub_portal_id`, `api_origin`, `curl_url`, `curl_payload`, `curl_method`, `curl_response`, `curl_http_code`, `curl_type`, `file_name`) VALUES ('$portalId', '$origin', '$endpoint', '" . addslashes($data) . "', '$method', '" . addslashes($curlResponse) . "', '$httpCode', '" . addslashes($type) . "', '" . addslashes($fileName) . "')");

	$curlResult = json_decode($curlResponse, true);
	$newAccessToken = isset($curlResult['access_token']) ? $curlResult['access_token'] : "";

	if (empty($newAccessToken) == false) {
		mysqli_query($conn, "UPDATE `app_installs` SET `access_token` = '$newAccessToken' WHERE `hub_portal_id` = '$portalId' AND `refresh_token` = '$refreshToken'");
		$returnResult = 1;
	}

	$result = [$returnResult, $curlResult, $httpCode];
	return $result;
}
