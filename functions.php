<?php
$successCodes = [200, 201, 202, 203, 204, 205];

/* ------------------------ HUBSPOT CONTACT FUNCTIONS ------------------------ */
/**
 * Search HubSpot for Contact with provided filters
 *
 * @param int $portalId
 * @param string $accessToken
 * @param array $filters
 * @param string $fileName
 * @param int $try
 * @return array $result
 */
function hsContactSearch($portalId, $accessToken, $filters, $fileName, $try = 0) {
	global $successCodes;

	$type = "Contact Search";
	$origin = "HubSpot";
	$method = "POST";

	$endpoint = "https://api.hubapi.com/crm/v3/objects/contacts/search";

	$customHeaders = [
		"Content-Type: application/json",
		"Authorization: Bearer $accessToken",
		"cache-control: no-cache"
	];

	$payload = json_encode($filters);

	sleep(2);

	$response = cURL_request($endpoint, $customHeaders, $method, $payload);
	$curlResponse = $response['curlResponse'];
	$httpCode = $response['httpCode'];
	$curlResult = json_decode($curlResponse, true);

	log_request($portalId, $origin, $endpoint, $payload, $method, $curlResponse, $httpCode, $type, $fileName);

	if (in_array($httpCode, $successCodes) == false && $try < 4) {
		$try++;
		sleep(2);
		hsContactSearch($portalId, $accessToken, $filters, $fileName, $try);
	} else {
		if (isset($curlResult['total']) && $curlResult['total'] > 0) {
			$result = $curlResult['results'];
		} else {
			$result = "";
		}
	}

	return $result;
}

/**
 * Retrieve the provided properties of a Contact from HubSpot by ID
 *
 * @param int $portalId
 * @param string $accessToken
 * @param int $objectId
 * @param array $properties
 * @param string $fileName
 * @param int $try
 * @return void
 */
function hsContactDetails($portalId, $accessToken, $objectId, $properties, $fileName, $try = 0) {
	global $successCodes;

	$type = "Contact Details";
	$origin = "HubSpot";
	$method = "GET";

	$endpoint = "https://api.hubapi.com/crm/v3/objects/contacts/$objectId?archived=false&$properties";

	$customHeaders = [
		"Authorization: Bearer $accessToken",
		"Content-type: application/json"
	];

	$response = cURL_getRequest($endpoint, $customHeaders, $method);
	$curlResponse = $response['curlResponse'];
	$httpCode = $response['httpCode'];
	$curlResult = json_decode($curlResponse, true);

	log_get_request($portalId, $origin, $endpoint, $method, $curlResponse, $httpCode, $type, $fileName);

	if (in_array($httpCode, $successCodes) == false && $try < 4) {
		$try++;
		sleep(2);
		hsContactDetails($portalId, $accessToken, $objectId, $properties, $fileName, $try);
	} else {
		if (isset($curlResult['properties']) && empty($curlResult['properties']) == false) {
			$result = $curlResult['properties'];
		} else {
			$result = "";
		}
	}

	return $result;
}

/**
 * Create Contact on HubSpot
 *
 * @param int $portalId
 * @param string $accessToken
 * @param array $payload
 * @param string $fileName
 * @param int $try
 * @return void
 */
function hsContactCreate($portalId, $accessToken, $payload, $fileName, $try = 0) {
	global $successCodes;

	$type = "Contact Create";
	$origin = "HubSpot";
	$method = "POST";

	$endpoint = "https://api.hubapi.com/crm/v3/objects/contacts/";

	$customHeaders = [
		"Authorization: Bearer $accessToken",
		"Content-type: application/json"
	];

	$payload = json_encode($payload);

	$response = cURL_request($endpoint, $customHeaders, $method, $payload);
	$curlResponse = $response['curlResponse'];
	$httpCode = $response['httpCode'];

	log_request($portalId, $origin, $endpoint, $payload, $method, $curlResponse, $httpCode, $type, $fileName);

	if (in_array($httpCode, $successCodes) == false && $try < 4) {
		$try++;
		sleep(2);
		hsContactCreate($portalId, $accessToken, $payload, $fileName, $try);
	}
}

/**
 * Update Contact on HubSpot by ID
 *
 * @param int $portalId
 * @param string $accessToken
 * @param int $objectId
 * @param array $payload
 * @param string $fileName
 * @param int $try
 * @return void
 */
function hsContactUpdate($portalId, $accessToken, $objectId, $payload, $fileName, $try = 0) {
	global $successCodes;

	$type = "Contact Update";
	$origin = "HubSpot";
	$method = "PATCH";

	$endpoint = "https://api.hubapi.com/crm/v3/objects/contacts/$objectId";

	$customHeaders = [
		"Authorization: Bearer $accessToken",
		"Content-type: application/json"
	];

	$payload = json_encode($payload);

	$response = cURL_request($endpoint, $customHeaders, $method, $payload);
	$curlResponse = $response['curlResponse'];
	$httpCode = $response['httpCode'];

	log_request($portalId, $origin, $endpoint, $payload, $method, $curlResponse, $httpCode, $type, $fileName);

	if (in_array($httpCode, $successCodes) == false && $try < 4) {
		$try++;
		sleep(2);
		hsContactUpdate($portalId, $accessToken, $objectId, $payload, $fileName, $try);
	}
}

/**
 * Delete Contact from HubSpot by ID
 *
 * @param int $portalId
 * @param string $accessToken
 * @param int $objectId
 * @param string $fileName
 * @param int $try
 * @return void
 */
function hsContactDelete($portalId, $accessToken, $objectId, $fileName, $try = 0) {
	global $successCodes;

	$type = "Contact Delete";
	$origin = "HubSpot";
	$method = "DELETE";

	$endpoint = "https://api.hubapi.com/crm/v3/objects/contacts/$objectId";

	$customHeaders = [
		"Authorization: Bearer $accessToken",
		"Content-type: application/json"
	];

	$response = cURL_getRequest($endpoint, $customHeaders, $method);
	$curlResponse = $response['curlResponse'];
	$httpCode = $response['httpCode'];

	log_get_request($portalId, $origin, $endpoint, $method, $curlResponse, $httpCode, $type, $fileName);

	if (in_array($httpCode, $successCodes) == false && $try < 4) {
		$try++;
		sleep(2);
		hsContactDelete($portalId, $accessToken, $objectId, $fileName, $try);
	}
}
/* ------------------------ END ------------------------ */

/* ------------------------ HUBSPOT CUSTOM ACTION FUNCTIONS ------------------------ */
/**
 * Get all custom workflow actions for the provided App ID
 *
 * @param int $appId
 * @param string $devApiKey
 * @param string $fileName
 * @return array $response
 */
function getActions($appId, $devApiKey, $fileName) {
	$origin = "HubSpot";
	$method = "GET";
	$type = "Get Actions";

	$endpoint = "https://api.hubapi.com/automation/v4/actions/$appId?archived=false&hapikey=$devApiKey";
	$customHeaders = ["Content-Type: application/json"];

	$response = cURL_getRequest($endpoint, $customHeaders, $method);
	$curlResponse = $response['curlResponse'];
	$httpCode = $response['httpCode'];

	$newArr = explode("?", $endpoint);
	$publicEndpoint = $newArr[0];

	log_get_request(1, $origin, $publicEndpoint, $method, $curlResponse, $httpCode, $type, $fileName);

	return $response;
}

/**
 * Create a custom Workflow action on HubSpot
 *
 * @param int $appId
 * @param string $devApiKey
 * @param array $payload
 * @param string $fileName
 * @return array $response
 */
function createAction($appId, $devApiKey, $payload, $fileName) {
	$origin = "HubSpot";
	$method = "POST";
	$type = "Create Action";

	$payload = json_encode($payload);

	$endpoint = "https://api.hubapi.com/automation/v4/actions/$appId?archived=false&hapikey=$devApiKey";
	$customHeaders = ["Content-Type: application/json"];

	$response = cURL_request($endpoint, $customHeaders, $method, $payload);
	$curlResponse = $response['curlResponse'];
	$httpCode = $response['httpCode'];

	$newArr = explode("?", $endpoint);
	$publicEndpoint = $newArr[0];

	log_request(1, $origin, $publicEndpoint, $payload, $method, $curlResponse, $httpCode, $type, $fileName);

	return $response;
}

/**
 * Update custom workflow action by ID
 *
 * @param int $appId
 * @param string $devApiKey
 * @param array $payload
 * @param int $actionId
 * @param string $fileName
 * @return array $response
 */
function updateAction($appId, $devApiKey, $payload, $actionId, $fileName) {
	$origin = "HubSpot";
	$method = "PATCH";
	$type = "Update Action";

	$payload = json_encode($payload);

	$endpoint = "https://api.hubapi.com/automation/v4/actions/$appId/$actionId?hapikey=$devApiKey";
	$customHeaders = ["Content-Type: application/json"];

	$response = cURL_request($endpoint, $customHeaders, $method, $payload);
	$curlResponse = $response['curlResponse'];
	$httpCode = $response['httpCode'];

	$newArr = explode("?", $endpoint);
	$publicEndpoint = $newArr[0];

	log_request(1, $origin, $publicEndpoint, $payload, $method, $curlResponse, $httpCode, $type, $fileName);

	return $response;
}

/**
 * Delete a Workflow Action by ID for the provided App ID
 *
 * @param int $appId
 * @param string $devApiKey
 * @param int $actionId
 * @param string $fileName
 * @return array $response
 */
function deleteAction($appId, $devApiKey, $actionId, $fileName) {
	$origin = "HubSpot";
	$method = "DELETE";
	$type = "Delete Action";

	$endpoint = "https://api.hubapi.com/automation/v4/actions/$appId/$actionId?hapikey=$devApiKey";
	$customHeaders = ["Content-Type: application/json"];

	$response = cURL_getRequest($endpoint, $customHeaders, $method);
	$curlResponse = $response['curlResponse'];
	$httpCode = $response['httpCode'];

	$newArr = explode("?", $endpoint);
	$publicEndpoint = $newArr[0];

	log_get_request(1, $origin, $publicEndpoint, $method, $curlResponse, $httpCode, $type, $fileName);

	return $response;
}
/* ------------------------ END ------------------------ */

/* ------------------------ OTHER HUBSPOT FUNCTIONS ------------------------ */
/**
 * Get HubSpot Account Info by Access Token
 *
 * @param string $accessToken
 * @param string $fileName
 * @param int $try
 * @return array $result
 */
function hsAccountInfo($accessToken, $fileName, $try = 0) {
	global $successCodes;

	$type = "Account Info";
	$origin = "HubSpot";
	$method = "GET";

	$endpoint = "https://api.hubapi.com/account-info/v3/details";

	$customHeaders = [
		"Authorization: Bearer $accessToken",
		"Content-type: application/json"
	];

	$response = cURL_getRequest($endpoint, $customHeaders, $method);
	$curlResponse = $response['curlResponse'];
	$httpCode = $response['httpCode'];
	$curlResult = json_decode($curlResponse, true);
	$portalId = $curlResult['portalId'];

	log_get_request($portalId, $origin, $endpoint, $method, $curlResponse, $httpCode, $type, $fileName);

	if (in_array($httpCode, $successCodes) == false && $try < 4) {
		$try++;
		sleep(2);
		hsAccountInfo($accessToken, $fileName, $try);
	} else {
		if (isset($curlResult) && empty($curlResult) == false) {
			$result = $curlResult;
		} else {
			$result = "";
		}
	}

	return $result;
}
/* ------------------------ END ------------------------ */

/* ------------------------ OTHER FUNCTIONS ------------------------ */
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
	$curlResponse = curl_exec($curlQuery);
	$httpCode = curl_getinfo($curlQuery, CURLINFO_RESPONSE_CODE);
	$result = [
		"httpCode" => $httpCode,
		"curlResponse" => $curlResponse
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
	$curlResponse = curl_exec($curlQuery);
	$httpCode = curl_getinfo($curlQuery, CURLINFO_RESPONSE_CODE);
	$result = [
		"httpCode" => $httpCode,
		"curlResponse" => $curlResponse
	];

	curl_close($curlQuery);

	return $result;
}

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
			<title> App Install </title>
			<meta charset="UTF-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">
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
					<div class="error-content fs-4 fw-bold">
						' . $text . '
					</div>
				</div>
			</section>
		</body>
	</html>';
}

/**
 * Fetch Access Token from Database
 *
 * @param int $portalId
 * @return string $access_token
 */
function retrieve_token($portalId) {
	global $conn;
	$getToken = mysqli_query($conn, "SELECT `access_token` FROM `app_installs` WHERE `hub_portal_id` = '$portalId'");
	$tokenRow = mysqli_fetch_array($getToken);
	$access_token = $tokenRow['access_token'];
	return $access_token;
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
	mysqli_query($conn, "INSERT INTO `api_logs` (`hub_portal_id`, `api_origin`, `curl_url`, `curl_method`, `curl_response`, `curl_http_code`, `curl_type`, `file_name`) VALUES ('$portal', '$origin', '$endpoint', '$method', '" . addslashes($response) . "', '$httpCode', '" . addslashes($type) . "', '" . addslashes($fileName) . "')");
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
	mysqli_query($conn, "INSERT INTO `api_logs` (`hub_portal_id`, `api_origin`, `curl_url`, `curl_payload`, `curl_method`, `curl_response`, `curl_http_code`, `curl_type`, `file_name`) VALUES ('$portal', '$origin', '$endpoint', '" . addslashes($payload) . "', '$method', '" . addslashes($response) . "', '$httpCode', '" . addslashes($type) . "', '" . addslashes($fileName) . "')");
}
/* ------------------------ END ------------------------ */
