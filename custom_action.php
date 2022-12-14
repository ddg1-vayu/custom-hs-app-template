<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title> Custom Actions </title>
	<style>
		*{scroll-behavior:smooth;scrollbar-width:thin;scrollbar-color:#605c5c #ececec;box-sizing:border-box}::-webkit-scrollbar{width:.675rem;height:.675rem}::-webkit-scrollbar-thumb{background:#605c5c}::-webkit-scrollbar-track{background:#ececec}body,html{font-family:'Open Sans','Fira Sans','Roboto','Ubuntu','Calibri',Arial,sans-serif;background-color:#010101;color:#f5f5f5}body{margin:0;padding:1rem}pre{margin: 0.75rem 0;}
	</style>
</head>

<body>
	<?php
	// ini_set("display_errors", 1);
	$fileName = pathinfo(__FILE__, PATHINFO_FILENAME);

	require("conn.php");
	require("functions.php");

	$actionURL = "";

	$feature = [
		"revisionId" => "1",
		"functions" => [],
		"actionUrl" => "$serverURL/$actionURL",
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

	$actionId = 111222;

	$response = getActions($appId, $devApiKey, $fileName);
	// $response = createAction($appId, $devApiKey, $feature, $fileName);
	// $response = updateAction($appId, $devApiKey, $feature, $actionId, $fileName);
	// $response = deleteAction($appId, $devApiKey, $actionId, $fileName);

	$curlResponse = $response['curlResponse'];
	$httpCode = $response['httpCode'];
	$curlResult = json_decode($curlResponse, true);

	echo "HTTP Response (<strong>" . $httpCode . "</strong>)";
	echo "<hr color='navy' size='2'>";
	echo "<pre>";
	print_r($curlResult);
	echo "</pre>";
	?>
</body>

</html>