<?php
// ini_set("display_errors", 1);

echo "<style> *{font-family: 'Fira Sans', Calibri, Arial, sans-serif;} </style>";
$fileName = pathinfo(__FILE__, PATHINFO_FILENAME);

require("conn.php");
require("functions.php");

$actionFileURL = "";

$feature = [
	"revisionId" => "1",
	"functions" => [],
	"actionUrl" => "$serverURL/$actionFileURL",
	"published" => true,
	"inputFields" => [
		[
			"typeDefinition" => [],
			"supportedValueTypes" => [
				"STATIC_VALUE"
			],
			"isRequired" => true
		]
	],
	"inputFieldDependencies" => [
		[]
	],
	"objectRequestOptions" => null,
	"labels" => [
		"en" => [
			"actionName" => "",
			"actionDescription" => "",
			"actionCardContent" => "",
			"inputFieldLabels" => []
		]
	],
	"objectTypes" => [
		"0-1"
	]
];

$actionID = 11111111;

$response = getActions($appId, $devApiKey, $fileName);
// $response = createAction($appId, $devApiKey, $feature, $fileName);
// $response = updateAction($appId, $devApiKey, $feature, $actionID, $fileName);
// $response = deleteAction($appId, $devApiKey, $actionID, $fileName);

$curlResponse = $response['curlResponse'];
$httpCode = $response['httpCode'];
$curlResult = json_decode($curlResponse, true);

echo "HTTP Response (<strong>" . $httpCode . "</strong>)";
echo "<hr color='black' size='2'>";
echo "<pre style='margin: 0.75rem 0;'>";
print_r($curlResult);
echo "</pre>";