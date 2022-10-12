<?php
$portalId = empty($_GET['portalId']) ? NULL : $_GET['portalId'];
$objectId = empty($_GET['associatedObjectId']) ? NULL : $_GET['associatedObjectId'];
$userId = empty($_GET['userId']) ? NULL : $_GET['userId'];
$userEmail = empty($_GET['userEmail']) ? NULL : $_GET['userEmail'];

$targetFile = "";

$link = "";
$frameLink = "";

$payload = [
	"results" => [
		[
			"objectId" => $objectId,
			"title" => "Custom App",
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
		"label" => "Open"
	]
];

echo json_encode($payload);
