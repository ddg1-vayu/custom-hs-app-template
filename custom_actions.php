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
	ini_set("display_errors", 1);
	$fileName = pathinfo(__FILE__, PATHINFO_FILENAME);

	require_once("conn.php");
	require_once("functions.php");

	$actionURL = "";

	$feature = [
		"revisionId" => "1",
		"functions" => [],
		"actionUrl" => "$subdomain/$actionURL",
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

	$result = getActions($appId, $devApiKey, $fileName);
	// $result = createAction($appId, $devApiKey, $feature, $fileName);
	// $result = updateAction($appId, $devApiKey, $feature, $actionId, $fileName);
	// $result = deleteAction($appId, $devApiKey, $actionId, $fileName);

	$response = $result['response'];
	$httpCode = $result['httpCode'];
	$curlResult = json_decode($response, true);

	echo "HTTP Response (<strong>" . $httpCode . "</strong>)";
	echo "<hr color='navy' size='2'>";
	echo "<pre>";
	print_r($curlResult);
	echo "</pre>";
	?>
</body>

</html>