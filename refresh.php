<?php
$fileName = pathinfo(__FILE__, PATHINFO_FILENAME);
$conn = mysqli_connect("", "", "", "", 3306);

if (!$conn) {
	$error = "Database Error - " . mysqli_connect_error();
	$file = "logs/connect_err_" . time() . ".txt";
	$newFile = fopen($file, "w+") or die("Unable to open file!");
	fwrite($newFile, $error);
	fclose($newFile);
	die();
} else {
	$getInstalls = mysqli_query($conn, "SELECT `hub_portal_id`, `refresh_token` FROM `app_installs` WHERE `status` = 'Active' ORDER BY `last_installed`");

	if (mysqli_num_rows($getInstalls) > 0) {
		while ($rows = mysqli_fetch_array($getInstalls)) {
			$portalId = $rows['hub_portal_id'];
			$refreshToken = $rows['refresh_token'];
			$result = token_refresh($portalId, $refreshToken, $fileName);
		}
		if (!empty($result)) {
			if ($result[0] == 1) {
				echo "Access token updated for all installs";
			} else {
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
function token_refresh($portalId, $refreshToken, $fileName) {
	$method = "POST";
	$origin = "HubSpot";
	$type = "Token Refresh";

	$returnResult = 0;
	$clientId = "";
	$clientSecret = "";

	$data = "grant_type=refresh_token&client_id=$clientId&client_secret=$clientSecret&refresh_token=$refreshToken";

	$endpoint = "https://api.hubapi.com/oauth/v1/token";
	$customHeaders = ["content-Type: application/x-www-form-urlencoded;charset=utf-8"];

	$curlQuery = curl_init();
	curl_setopt($curlQuery, CURLOPT_URL, $endpoint);
	curl_setopt($curlQuery, CURLOPT_HTTPHEADER, $customHeaders);
	curl_setopt($curlQuery, CURLOPT_CUSTOMREQUEST, $method);
	curl_setopt($curlQuery, CURLOPT_POSTFIELDS, $data);
	curl_setopt($curlQuery, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curlQuery, CURLOPT_HTTP_VERSION, "CURL_HTTP_VERSION_1_1");
	$response = curl_exec($curlQuery);
	$httpCode = curl_getinfo($curlQuery, CURLINFO_RESPONSE_CODE);
	curl_close($curlQuery);

	global $conn;
	mysqli_query($conn, "INSERT INTO `api_logs` (`hub_portal_id`, `api_origin`, `curl_url`, `curl_payload`, `curl_method`, `curl_response`, `curl_http_code`, `curl_type`, `file_name`) VALUES ('$portalId', '$origin', '$endpoint', '" . addslashes($data) . "', '$method', '" . addslashes($response) . "', '$httpCode', '$type', '" . addslashes($fileName) . "')");

	$responseArr = json_decode($response, true);
	$newAccessToken = $responseArr['access_token'];

	if ($httpCode !== '429') {
		if (!empty($newAccessToken)) {
			mysqli_query($conn, "UPDATE `app_installs` SET `access_token` = '$newAccessToken' WHERE `hub_portal_id` = '$portalId' AND `refresh_token` = '$refreshToken'");
			$returnResult = 1;
		} else {
			$returnResult = 0;
		}
		$result = [$returnResult, $responseArr, $httpCode];
	}

	return $result;
}
