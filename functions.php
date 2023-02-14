<?php
$successCodes = [200, 201, 202, 203, 204, 205];

/* ------------------------ cURL FUNCTIONS ------------------------ */
	/**
	 * Make a GET/DELETE cURL Request
	 *
	 * @param string $endpoint
	 * @param array $customHeaders
	 * @param string $method
	 * @return array $result
	 */
	function cURL_getRequest($endpoint, $customHeaders, $method) {
		$curlQuery = curl_init();
		curl_setopt($curlQuery, CURLOPT_URL, $endpoint);
		curl_setopt($curlQuery, CURLOPT_HTTP_VERSION, "CURL_HTTP_VERSION_1_1");
		curl_setopt($curlQuery, CURLOPT_HTTPHEADER, $customHeaders);
		curl_setopt($curlQuery, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curlQuery, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curlQuery);
		$httpCode = curl_getinfo($curlQuery, CURLINFO_RESPONSE_CODE);
		$result = [
			"httpCode" => $httpCode,
			"response" => $response
		];

		curl_close($curlQuery);

		return $result;
	}

	/**
	 * Make a POST/PATCH cURL request
	 *
	 * @param string $endpoint
	 * @param array $customHeaders
	 * @param string $method
	 * @param string $payload
	 * @return array $result
	 */
	function cURL_request($endpoint, $customHeaders, $method, $payload) {
		$curlQuery = curl_init();
		curl_setopt($curlQuery, CURLOPT_URL, $endpoint);
		curl_setopt($curlQuery, CURLOPT_HTTP_VERSION, "CURL_HTTP_VERSION_1_1");
		curl_setopt($curlQuery, CURLOPT_HTTPHEADER, $customHeaders);
		curl_setopt($curlQuery, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curlQuery, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($curlQuery, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curlQuery);
		$httpCode = curl_getinfo($curlQuery, CURLINFO_RESPONSE_CODE);
		$result = [
			"httpCode" => $httpCode,
			"response" => $response
		];

		curl_close($curlQuery);

		return $result;
	}
/* ------------------------ END ------------------------ */

/* ------------------------ DATABASE FUNCTIONS ------------------------ */
	/**
	 * Fetch Access Token from Database
	 *
	 * @param int $portalId
	 * @return string $access_token
	 */
	function retrieveAccessToken($portalId) {
		global $conn;
		$getToken = mysqli_query($conn, "SELECT `access_token` FROM `app_installs` WHERE `hub_portal_id` = '$portalId'");
		$tokenRow = mysqli_fetch_array($getToken);
		$access_token = $tokenRow['access_token'];
		return $access_token;
	}

	/**
	 * Update processing status of webhook
	 *
	 * @param int $id
	 * @return void
	 */
	function webhookStatusUpdate($id) {
		global $conn;
		mysqli_query($conn, "UPDATE `webhooks` SET `status` = '1' WHERE `id` = '$id'");
	}

	/**
	 * Log details when an API request is made & no payload is sent
	 *
	 * @param int $portal
	 * @param string $origin
	 * @param string $endpoint
	 * @param string $method
	 * @param string $response
	 * @param int $httpCode
	 * @param string $type
	 * @param string $fileName
	 * @return void
	 */
	function log_get_request($portal, $origin, $endpoint, $method, $response, $httpCode, $type, $fileName) {
		global $conn;
		mysqli_query($conn, "INSERT INTO `api_logs` (`hub_portal_id`, `api_origin`, `curl_url`, `curl_method`, `curl_response`, `curl_http_code`, `curl_type`, `file_name`) VALUES ('$portal', '$origin', '" . addslashes($endpoint) . "', '$method', '" . addslashes($response) . "', '$httpCode', '$type', '" . addslashes($fileName) . "')");
	}

	/**
	 * Log details when an API request is made & a payload is sent
	 *
	 * @param int $portal
	 * @param string $origin
	 * @param string $endpoint
	 * @param string $payload
	 * @param string $method
	 * @param string $response
	 * @param int $httpCode
	 * @param string $type
	 * @param string $fileName
	 * @return void
	 */
	function log_request($portal, $origin, $endpoint, $payload, $method, $response, $httpCode, $type, $fileName) {
		global $conn;
		mysqli_query($conn, "INSERT INTO `api_logs` (`hub_portal_id`, `api_origin`, `curl_url`, `curl_payload`, `curl_method`, `curl_response`, `curl_http_code`, `curl_type`, `file_name`) VALUES ('$portal', '$origin', '" . addslashes($endpoint) . "', '" . addslashes($payload) . "', '$method', '" . addslashes($response) . "', '$httpCode', '$type', '" . addslashes($fileName) . "')");
	}
/* ------------------------ END ------------------------ */

/* ------------------------ HUBSPOT CUSTOM ACTION FUNCTIONS ------------------------ */
	/**
	 * Get all custom workflow actions for the provided App ID
	 *
	 * @param int $appId
	 * @param string $devApiKey
	 * @param string $fileName
	 * @return array $result
	 */
	function getActions($appId, $devApiKey, $fileName) {
		$origin = "HubSpot";
		$method = "GET";
		$type = "Get Actions";

		$endpoint = "https://api.hubapi.com/automation/v4/actions/$appId?archived=false&hapikey=$devApiKey";
		$customHeaders = ["Content-Type: application/json"];

		$result = cURL_getRequest($endpoint, $customHeaders, $method);
		$response = $result['response'];
		$httpCode = $result['httpCode'];

		$newArr = explode("?", $endpoint);
		$publicEndpoint = $newArr[0];

		log_get_request(1, $origin, $publicEndpoint, $method, $response, $httpCode, $type, $fileName);

		return $result;
	}

	/**
	 * Create a custom Workflow action on HubSpot
	 *
	 * @param int $appId
	 * @param string $devApiKey
	 * @param array $payload
	 * @param string $fileName
	 * @return array
	 */
	function createAction($appId, $devApiKey, $payload, $fileName) {
		$origin = "HubSpot";
		$method = "POST";
		$type = "Create Action";

		$payload = json_encode($payload);

		$endpoint = "https://api.hubapi.com/automation/v4/actions/$appId?archived=false&hapikey=$devApiKey";
		$customHeaders = ["Content-Type: application/json"];

		$result = cURL_request($endpoint, $customHeaders, $method, $payload);
		$response = $result['response'];
		$httpCode = $result['httpCode'];

		$newArr = explode("?", $endpoint);
		$publicEndpoint = $newArr[0];

		log_request(1, $origin, $publicEndpoint, $payload, $method, $response, $httpCode, $type, $fileName);

		return $result;
	}

	/**
	 * Update custom workflow action by ID
	 *
	 * @param int $appId
	 * @param string $devApiKey
	 * @param array $payload
	 * @param int $actionId
	 * @param string $fileName
	 * @return array $result
	 */
	function updateAction($appId, $devApiKey, $payload, $actionId, $fileName) {
		$origin = "HubSpot";
		$method = "PATCH";
		$type = "Update Action";

		$payload = json_encode($payload);

		$endpoint = "https://api.hubapi.com/automation/v4/actions/$appId/$actionId?hapikey=$devApiKey";
		$customHeaders = ["Content-Type: application/json"];

		$result = cURL_request($endpoint, $customHeaders, $method, $payload);
		$response = $result['response'];
		$httpCode = $result['httpCode'];

		$newArr = explode("?", $endpoint);
		$publicEndpoint = $newArr[0];

		log_request(1, $origin, $publicEndpoint, $payload, $method, $response, $httpCode, $type, $fileName);

		return $result;
	}

	/**
	 * Delete a Workflow Action by ID for the provided App ID
	 *
	 * @param int $appId
	 * @param string $devApiKey
	 * @param int $actionId
	 * @param string $fileName
	 * @return array $result
	 */
	function deleteAction($appId, $devApiKey, $actionId, $fileName) {
		$origin = "HubSpot";
		$method = "DELETE";
		$type = "Delete Action";

		$endpoint = "https://api.hubapi.com/automation/v4/actions/$appId/$actionId?hapikey=$devApiKey";
		$customHeaders = ["Content-Type: application/json"];

		$result = cURL_getRequest($endpoint, $customHeaders, $method);
		$response = $result['response'];
		$httpCode = $result['httpCode'];

		$newArr = explode("?", $endpoint);
		$publicEndpoint = $newArr[0];

		log_get_request(1, $origin, $publicEndpoint, $method, $response, $httpCode, $type, $fileName);

		return $result;
	}
/* ------------------------ END ------------------------ */

/* ------------------------ OTHER FUNCTIONS ------------------------ */
	/**
	 * Display either the error generated by the cURL request or the success message
	 *
	 * @param string $text
	 * @return mixed
	 */
	function req_response($text) {
		echo '<!DOCTYPE html>
		<html lang="en">
			<head>
				<title> Divine Connection </title>
				<meta charset="UTF-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<link rel="shortcut icon" href="assets/hs-logo.png" type="image/x-icon">
				<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
				<link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
				<style>
					*{transition:.5s all ease;scroll-behavior:smooth}
					body{font-family: "Fira Sans", Arial, Helvetica, sans-serif;}
					.error-section{margin:.75rem}
					.error-content{display:flex;align-items:center;border-radius:.5rem}
					.fluid-font{font-size:clamp(.875rem,.875rem + .125vw,1rem)}
					pre{color:#022741!important;margin-top:1rem;font-size:clamp(.875rem,.875rem + .125vw,1rem)}
				</style>
			</head>
			<body>
				<section class="error-section">
					<div class="container-fluid p-0">
						<div class="error-content fs-3 fw-bold">
							' . $text . '
						</div>
					</div>
				</section>
			</body>
		</html>';
	}
/* ------------------------ END ------------------------ */