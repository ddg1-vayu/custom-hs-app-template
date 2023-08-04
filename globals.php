<?php
define("db_server", "");
define("db_user", "");
define("db", "");
define("db_password", "");

$subdomain = "";

$assetsFolder = "$subdomain/assets";
$cssFolder = "$subdomain/css";
$jsFolder = "$subdomain/js";

$appId = 123;

$devApiKey = "";

$clientId = "";
$clientSecret = "";

function formatDateTime($value) {
	return date("d-M-Y h:i:s A T", strtotime($value));
}

function formatDate($value) {
	return date("d-M-Y", strtotime($value));
}

function formatTime($value) {
	return date("h:i:s A T", strtotime($value));
}