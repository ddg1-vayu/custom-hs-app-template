<?php
$successCodes = [200, 201, 202, 203, 204, 205];

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

	/**
	 * Insert the data received from a HTTP Request in the DB
	 *
	 * @param int $portalId
	 * @param string $source
	 * @param string $payload
	 * @param string $type
	 * @param string $fileName
	 * @return void
	 */
	function webhookInsert($portalId, $source, $payload, $type, $fileName) {
		global $conn;
		mysqli_query($conn, "INSERT INTO `webhooks` (`hub_portal_id`, `source`, `payload`, `type`, `file_name`) VALUES ('$portalId', '$source', '$payload', '$type', '$fileName')");
	}

	/**
	 * Mark a webhook as Processed
	 *
	 * @param int $id
	 * @return void
	 */
	function webhookStatusUpdate($id) {
		global $conn;
		mysqli_query($conn, "UPDATE `webhooks` SET `status` = '1' WHERE `id` = '$id'");
	}
/* ------------------------ END ------------------------ */

/* ------------------------ HUBSPOT FUNCTIONS ------------------------ */
	/**
	 * Get HubSpot Account Info by Access Token
	 *
	 * @param string $appName
	 * @param string $accessToken
	 * @param string $fileName
	 * @param int $try
	 * @return array
	 */
	function hsAccountInfo($accessToken, $fileName, $try = 0) {
		global $successCodes;

		$method = "GET";
		$origin = "HubSpot";
		$type = "Account Info";

		$endpoint = "https://api.hubapi.com/account-info/v3/details";

		$customHeaders = ["Authorization: Bearer $accessToken", "Content-type: application/json"];

		$result = cURL_getRequest($endpoint, $customHeaders, $method);
		$response = $result['response'];
		$httpCode = $result['httpCode'];

		$responseArr = json_decode($response, true);
		$portalId = $responseArr['portalId'];

		log_get_request($portalId, $origin, $endpoint, $method, $response, $httpCode, $type, $fileName);

		if (in_array($httpCode, $successCodes) == false && $try < 4) {
			$try++;
			sleep(2);
			hsAccountInfo($accessToken, $fileName, $try);
		} else {
			$resultArr = (isset($responseArr) && empty($responseArr) == false) ? $responseArr : [];
		}

		return $resultArr;
	}

	/**
	 * Retreive all user of the provided HubSpot A/c
	 *
	 * @param string $appName
	 * @param int $portalId
	 * @param string $accessToken
	 * @param string $fileName
	 * @param array $list
	 * @param int $limit
	 * @param string $after
	 * @param int $try
	 * @return array
	 */
	function hsAccountOwners($portalId, $accessToken, $fileName, $list = [], $limit = 100, $after = "", $try = 0) {
		$method = "GET";
		$origin = "HubSpot";
		$type = "Account Owners";

		$after = ($after != "") ? "after=$after&" : "";

		$endpoint = "https://api.hubapi.com/crm/v3/owners/?archived=false&limit=$limit&$after";

		$customHeaders = ["Content-Type: application/json", "Authorization: Bearer $accessToken"];

		$result = cURL_getRequest($endpoint, $customHeaders, $method);
		$response = $result['response'];
		$httpCode = $result['httpCode'];

		log_get_request($portalId, $origin, $endpoint, $method, $response, $httpCode, $type, $fileName);

		$responseArr = json_decode($response, true);
		if (isset($responseArr['results']) && !empty($responseArr['results'])) {
			$list = array_merge($list, $responseArr['results']);

			if (isset($responseArr['paging']['next']['after']) && $responseArr['paging']['next']['after'] != '') {
				$new_after = $responseArr['paging']['next']['after'];
				return hsAccountOwners($portalId, $accessToken, $fileName, $list, $limit = 1, $new_after);
			}
		}

		return $list;
	}

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

		$type = "Search Contacts";
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

		$result = cURL_request($endpoint, $customHeaders, $method, $payload);
		$response = $result['response'];
		$httpCode = $result['httpCode'];
		$curlResult = json_decode($response, true);

		log_request($portalId, $origin, $endpoint, $payload, $method, $response, $httpCode, $type, $fileName);

		if (in_array($httpCode, $successCodes) == false && $try < 4) {
			$try++;
			sleep(2);
			hsContactSearch($portalId, $accessToken, $filters, $fileName, $try);
		} else {
			if (isset($curlResult['total']) && $curlResult['total'] > 0) {
				$responseArr = $curlResult['results'];
			} else {
				$responseArr = [];
			}
		}

		return $responseArr;
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
	function hsContactCreate($portalId, $accessToken, $data, $fileName, $try = 0) {
		global $successCodes;

		$type = "Create Contact";
		$origin = "HubSpot";
		$method = "POST";

		$hsObjectId = "";

		$endpoint = "https://api.hubapi.com/crm/v3/objects/contacts";

		$customHeaders = [
			"Authorization: Bearer $accessToken",
			"Content-type: application/json"
		];

		$payloadArr['properties'] = $data;
		$payload = json_encode($payloadArr);

		$result = cURL_request($endpoint, $customHeaders, $method, $payload);
		$response = $result['response'];
		$httpCode = $result['httpCode'];

		log_request($portalId, $origin, $endpoint, $payload, $method, $response, $httpCode, $type, $fileName);

		if (in_array($httpCode, $successCodes) == false && $httpCode != 409 && $try < 4) {
			$try++;
			sleep(2);
			hsContactCreate($portalId, $accessToken, $payload, $fileName, $try);
		} else {
			$responseArr = json_decode($response, true);
			$hsObjectId = $responseArr['properties']['hs_object_id'];
		}

		return $hsObjectId;
	}

	/**
	 * Update Contact on HubSpot by ID
	 *
	 * @param int $portalId
	 * @param string $accessToken
	 * @param int $objectId
	 * @param array $data
	 * @param string $fileName
	 * @param int $try
	 * @return int
	 */
	function hsContactUpdate($portalId, $accessToken, $objectId, $data, $fileName, $try = 0) {
		global $successCodes;

		$type = "Update Contact";
		$origin = "HubSpot";
		$method = "PATCH";

		$endpoint = "https://api.hubapi.com/crm/v3/objects/contacts/$objectId";

		$customHeaders = [
			"Authorization: Bearer $accessToken",
			"Content-type: application/json"
		];

		$payloadArr['properties'] = $data;
		$payload = json_encode($payloadArr);

		$result = cURL_request($endpoint, $customHeaders, $method, $payload);
		$response = $result['response'];
		$httpCode = $result['httpCode'];

		log_request($portalId, $origin, $endpoint, $payload, $method, $response, $httpCode, $type, $fileName);

		if (in_array($httpCode, $successCodes) == false && $try < 4) {
			$try++;
			sleep(2);
			hsContactUpdate($portalId, $accessToken, $objectId, $data, $fileName, $try);
		}

		return $httpCode;
	}

	/**
	 * Retrieve the provided properties of a Contact from HubSpot by Object ID
	 *
	 * @param int $portalId
	 * @param string $accessToken
	 * @param int $objectId
	 * @param string $properties
	 * @param string $fileName
	 * @param int $try
	 * @return array
	 */
	function hsContactDetails($portalId, $accessToken, $objectId, $properties = "", $fileName, $try = 0) {
		global $successCodes;

		$method = "GET";
		$origin = "HubSpot";
		$type = "Contact Details";

		$result = [];

		$endpoint = "https://api.hubapi.com/crm/v3/objects/contacts/$objectId?$properties&archived=false";

		$customHeaders = [
			"Authorization: Bearer $accessToken",
			"Content-type: application/json"
		];

		$result = cURL_getRequest($endpoint, $customHeaders, $method);
		$response = $result['response'];
		$httpCode = $result['httpCode'];

		log_get_request($portalId, $origin, $endpoint, $method, $response, $httpCode, $type, $fileName);

		if (in_array($httpCode, $successCodes) == false && $try < 4) {
			$try++;
			sleep(2);
			hsContactDetails($portalId, $accessToken, $objectId, $properties, $fileName, $try);
		} else {
			$responseArr = json_decode($response, true);
			if (isset($responseArr['properties']) && empty($responseArr['properties']) == false) {
				$result = $responseArr['properties'];
			}
		}
	
		return $result;
	}

	/**
	 * Create a Timeline Event on HubSpot
	 *
	 * @param string $appName
	 * @param int $portalId
	 * @param string $accessToken
	 * @param string $eventType
	 * @param string $eventData
	 * @param string $fileName
	 * @return int
	 */
	function hsTimelineEvent($portalId, $accessToken, $eventType, $eventData, $fileName) {
		$origin = "HubSpot";
		$method = "POST";

		$endpoint = "https://api.hubapi.com/crm/v3/timeline/events";

		$customHeaders = ["Authorization: Bearer $accessToken", "Content-type: application/json"];

		$payload = json_encode($eventData);
		$result = cURL_request($endpoint, $customHeaders, $method, $payload);
		$response = $result['response'];
		$httpCode = $result['httpCode'];

		log_request($portalId, $origin, $endpoint, $payload, $method, $response, $httpCode, $eventType, $fileName);

		return $httpCode;
	}

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
	 * Make a GET/DELETE cURL Request
	 *
	 * @param string $endpoint
	 * @param array $customHeaders
	 * @param string $method
	 * @return array $result
	 */
	function cURL_getRequest($endpoint, $customHeaders, $method) {
		$curlQuery = curl_init();
		curl_setopt($curlQuery, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curlQuery, CURLOPT_ENCODING, "");
		curl_setopt($curlQuery, CURLOPT_HTTPHEADER, $customHeaders);
		curl_setopt($curlQuery, CURLOPT_HTTP_VERSION, "CURL_HTTP_VERSION_1_1");
		curl_setopt($curlQuery, CURLOPT_MAXREDIRS, 10);
		curl_setopt($curlQuery, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlQuery, CURLOPT_TIMEOUT, 30);
		curl_setopt($curlQuery, CURLOPT_URL, $endpoint);
		$response = curl_exec($curlQuery);
		$httpCode = curl_getinfo($curlQuery, CURLINFO_RESPONSE_CODE);
		$resultArr = [
			"httpCode" => $httpCode,
			"response" => $response
		];

		curl_close($curlQuery);

		return $resultArr;
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
		curl_setopt($curlQuery, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curlQuery, CURLOPT_ENCODING, "");
		curl_setopt($curlQuery, CURLOPT_HTTPHEADER, $customHeaders);
		curl_setopt($curlQuery, CURLOPT_HTTP_VERSION, "CURL_HTTP_VERSION_1_1");
		curl_setopt($curlQuery, CURLOPT_MAXREDIRS, 10);
		curl_setopt($curlQuery, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($curlQuery, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlQuery, CURLOPT_TIMEOUT, 30);
		curl_setopt($curlQuery, CURLOPT_URL, $endpoint);
		$response = curl_exec($curlQuery);
		$httpCode = curl_getinfo($curlQuery, CURLINFO_RESPONSE_CODE);
		$resultArr = [
			"httpCode" => $httpCode,
			"response" => $response
		];

		curl_close($curlQuery);

		return $resultArr;
	}

	/**
	 * Return the file type of the provided URL
	 *
	 * @param string $url
	 * @return string
	 */
	function getFileType($url) {
		// Use a try block to catch any exceptions
		try {
			// Parse the URL and get the path component
			$path = parse_url($url, PHP_URL_PATH);
			// Check if the path has a file extension
			if (pathinfo($path, PATHINFO_EXTENSION)) {
				// Return the file extension
				return pathinfo($path, PATHINFO_EXTENSION);
			} else {
				// Perform a HEAD request to the URL and get the content type header
				$headers = get_headers($url, 1);
				// Check if the headers are valid
				if ($headers !== false) {
					$content_type = $headers['Content-Type'];
					// Check if the content type is a valid MIME type
					if (preg_match('/^[\w-]+\/[\w-]+$/', $content_type)) {
						// Return the MIME type
						return $content_type;
					} else {
						// Throw an exception for invalid MIME type
						throw new Exception("Invalid MIME type: $content_type");
					}
				} else {
					// Throw an exception for failed request
					throw new Exception("Failed to get headers for: $url");
				}
			}
		} catch (Exception $e) {
			// Catch any exceptions and return an error message
			return "Error: " . $e->getMessage();
		}
	}
	
	/**
	 * Make HTML Anchors for all HTTPS urls present in the provided string
	 *
	 * @param string $string
	 * @return string
	 */
	function linkify($string)	{
		$pattern = '/(https?:\/\/[^\s]+)/i';
		$replacement = '<a target="_blank" href="$1">$1</a>';
		return preg_replace($pattern, $replacement, $string);
	}
	
	/**
	 * Make HTML Anchors for all email addresses present in the provided string
	 *
	 * @param string $string
	 * @return string
	 */
	function linkifyEmail($string)	{
		$pattern = '/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/';
		$replacement = '<a href="mailto:$1" target="_blank">$1</a>';
		return preg_replace($pattern, $replacement, $string);
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
				<title> Custom App </title>
				<meta charset="UTF-8">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<link rel="shortcut icon" href="assets/logo.png" type="image/x-icon">
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
