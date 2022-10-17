<?php
// ini_set("display_errors", 1);
$fileName = pathinfo(__FILE__, PATHINFO_FILENAME);

require("conn.php");
require("functions.php");

if (isset($_REQUEST['code']) && empty($_REQUEST['code']) == false) {
	$code = $_REQUEST['code'];

	$type = "App Install";
	$method = "POST";
	$origin = "HubSpot";

	$redirectURL = "$serverURL/$fileName.php";

	$payload = "grant_type=authorization_code&client_id=$clientId&client_secret=$clientSecret&redirect_uri=$redirectURL&code=$code";

	$endpoint = "https://api.hubapi.com/oauth/v1/token";
	$customHeaders = ["Content-Type: application/x-www-form-urlencoded;charset=utf-8"];

	$response = cURL_request($endpoint, $customHeaders, $method, $payload);
	$curlResponse = $response['curlResponse'];
	$httpCode = $response['httpCode'];
	$curlResult = json_decode($curlResponse, true);
	// echo '<pre>'; print_r($curlResult); echo '</pre>';

	if (empty($curlResult['status']) == false || isset($curlResult['refresh_token'])) {
		$refreshToken = $curlResult['refresh_token'];
		$accessToken = $curlResult['access_token'];

		$portalInfo = hsPortalInfo($accessToken, $fileName);
		$portalId = $portalInfo['portalId'];

		$checkInstall = mysqli_query($conn, "SELECT * FROM `app_installs` WHERE `hub_portal_id` = '$portalId'");
		if (mysqli_num_rows($checkInstall) > 0) {
			mysqli_query($conn, "UPDATE `app_installs` SET `install_code` = '$code', `refresh_token` = '$refreshToken', `access_token` = '$accessToken', `last_installed` = current_timestamp(), `token_updated` = current_timestamp() WHERE `hub_portal_id` = '$portalId'");
		} else {
			mysqli_query($conn, "INSERT INTO `app_installs` (`hub_portal_id`, `install_code`, `refresh_token`, `access_token`) VALUES ('$portalId', '$code', '$refreshToken', '$accessToken')");
		}

		log_request($portalId, $origin, $endpoint, $payload, $method, $curlResponse, $httpCode, $type, $fileName);
		// req_response("<div class='col-lg-12 col-md-12 col-sm-12 text-center'> <div class='alert alert-success text-center p-2'> Application Installed Successfully </div> <a href='https://app-eu1.hubspot.com/contacts/$portalId' title='Return to HubSpot' class='fluid-font btn btn-primary'> Return to HubSpot </a> </div>");

		// echo "<script> location.href = 'https://?portalId=$portalId' </script>";
	} else {
		log_request($portalId, $origin, $endpoint, $payload, $method, $curlResponse, $httpCode, $type, $fileName);
		req_response("<h3 class='fw-bold text-danger'>" . strtoupper($curlResult['status']) . ": " . $curlResult['message'] . "</h3>");
	}
} else {
	req_response("<h3 class='m-0 fw-bold text-danger'>Invalid Request!</h3>");
}
