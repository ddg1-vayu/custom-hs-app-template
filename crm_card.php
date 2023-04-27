<?php
include_once("globals.php");

$httpMethod = $_SERVER['REQUEST_METHOD'];
$unixTimestamp = $_SERVER['HTTP_X_HUBSPOT_REQUEST_TIMESTAMP'];
$url = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$version = $_SERVER['HTTP_X_HUBSPOT_SIGNATURE_VERSION'];
$signature = $_SERVER['HTTP_X_HUBSPOT_SIGNATURE'];

switch ($version) {
	case "v1":
		$data = $clientSecret;
		if (isset($webhook) && empty($webhook) == false) {
			$data = $data . $webhook;
		}
		$hashData = hash("sha256", $data);
		break;
	case "v2":
		$data = $clientSecret . $httpMethod . $url;
		if (isset($webhook) && empty($webhook) == false) {
			$data = $data . $webhook;
		}
		$hashData = hash("sha256", $data);
		break;
	case "v3":
		$timestamp = floor($unixTimestamp / 1000);
		$currentTimestamp = time();
		$difference = ($currentTimestamp - $timestamp);
		$httpMethod =  "POST";
		if ($difference > 300) {
			$data = $httpMethod . $url . $webhook . $unixTimestamp;
			$encData = mb_convert_encoding($data, "UTF-8", mb_list_encodings());
			$hashData = base64_encode(hash_hmac("sha256", $encData, $clientSecret, false));
		} else {
			echo "Hubspot's v3 Signature has expired! The request cannot be processed.";
		}
		break;
	default:
		die("Signature ERROR");
		break;
}

if ($signature == $hashData) {
	$objectId = (isset($_GET['associatedObjectId']) && empty($_GET['associatedObjectId'] == false)) ? strip_tags(trim($_GET['associatedObjectId'])) : NULL;
	$portalId = (isset($_GET['portalId']) && empty($_GET['portalId'] == false)) ? strip_tags(trim($_GET['portalId'])) : NULL;
	$userEmail = (isset($_GET['userEmail']) && empty($_GET['userEmail'] == false)) ? strip_tags(trim($_GET['userEmail'])) : NULL;
	$userId = (isset($_GET['userId']) && empty($_GET['userId'] == false)) ? strip_tags(trim($_GET['userId'])) : NULL;

	$link = "";
	$frameLink = "";

	$payload = [
		"results" => [
			[
				"objectId" => $objectId,
				"title" => "CRM Card",
				"link" => $link,
				"properties" => [
					[
						"label" => "Sample Card",
						"dataType" => "STRING",
						"value" => "This is a sample"
					]
				]
			]
		],
		"primaryAction" => [
			"type" => "IFRAME",
			"width" => 640,
			"height" => 480,
			"uri" => $frameLink,
			"label" => "View"
		]
	];

	echo json_encode($payload);

	header("Content-Type: application/json");
}
