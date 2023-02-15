<?php
ini_set("display_errors", 1);

$fileName = pathinfo(__FILE__, PATHINFO_FILENAME);
require("conn.php");
require("functions.php");

if (isset($_REQUEST['code']) && empty($_REQUEST['code']) == false) {
	$code = $_REQUEST['code'];

	$method = "POST";
	$origin = "HubSpot";
	$type = "App Install";

	$redirectURL = "$subdomain/process.php";

	$payload = "grant_type=authorization_code&client_id=$clientId&client_secret=$clientSecret&redirect_uri=$redirectURL&code=$code";

	$endpoint = "https://api.hubapi.com/oauth/v1/token";
	$customHeaders = ["content-Type: application/x-www-form-urlencoded; charset=utf-8"];

	$result = cURL_request($endpoint, $customHeaders, $method, $payload);
	$response = $result['response'];
	$httpCode = $result['httpCode'];
	$responseArr = json_decode($response, true);

	if (isset($responseArr['status']) && $responseArr['status'] == "error") {
		log_request($portalId, $origin, $endpoint, $payload, $method, $response, $httpCode, $type, $fileName);
		req_response("<h3 class='fw-bold text-danger'>" . strtoupper($responseArr['status']) . ": " . $responseArr['message'] . "</h3>");
	} else {
		$refreshToken = $responseArr['refresh_token'];
		$accessToken = $responseArr['access_token'];

		$portalInfo = hsAccountInfo($accessToken, $fileName);
		$portalId = $portalInfo['portalId'];
		$timeZone = $portalInfo['timeZone'];

		$checkInstalls = mysqli_query($conn, "SELECT * FROM `app_installs` WHERE `hub_portal_id` = '$portalId'");
		if (mysqli_num_rows($checkInstalls) > 0) {
			$sql = "UPDATE `app_installs` SET `hub_timezone` = '$timeZone', `install_code` = '$code', `refresh_token` = '$refreshToken', `access_token` = '$accessToken', `last_installed` = current_timestamp(), `token_updated` = current_timestamp() WHERE `hub_portal_id` = '$portalId'";
		} else {
			$sql = "INSERT INTO `app_installs` (`hub_portal_id`, `hub_timezone`, `install_code`, `refresh_token`, `access_token`) VALUES ('$portalId', '$timeZone', '$code', '$refreshToken', '$accessToken')";
		}
		$execQuery = mysqli_query($conn, $sql);
		if ($execQuery) {
			log_request($portalId, $origin, $endpoint, $payload, $method, $response, $httpCode, $type, $fileName);
	
			req_response("<div class='col-lg-12 col-md-12 col-sm-12 text-center'> <div class='alert alert-success text-center p-2'> Application Installed! </div> <a href='https://app-eu1.hubspot.com/home?portalId=$portalId' title='Return to HubSpot' class='fluid-font btn btn-primary'> Return to HubSpot </a> </div>");
		}
	}
} else {
	req_response("<h3 class='m-0 fw-bold text-danger'>Invalid Request!</h3>");
}
