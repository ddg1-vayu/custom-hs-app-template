<?php
// ini_set("display_errors", 1);
require("conn.php");

if (isset($_POST['action']) && empty($_POST['action']) == false) {
	$action = $_POST['action'];
	$recordId = $_POST['recordId'];

	switch ($action) {
		case "get_payload":
			$getPayload = mysqli_query($conn, "SELECT `curl_payload` FROM `api_logs` WHERE `id` = '$recordId'");
			$rows = mysqli_fetch_assoc($getPayload);
			$payload = $rows['curl_payload'];
			if (is_null($payload) == false && empty($payload) == false) {
				if (strncmp($payload, "grant_type=refresh_token", 24) === 0) {
					$output = [];
					$array = explode("&", $payload);
					for ($i = 0; $i < sizeof($array); $i++) {
						list($key, $value) = explode("=", $array[$i]);
						$output[$key] = $value;
					}
					$output['client_id'] = mask_string($output['client_id'], 12);
					$output['client_secret'] = mask_string($output['client_secret'], 12);
					$output['refresh_token'] = mask_string($output['refresh_token'], 12);
					print_r($output);
				} elseif (strncmp($payload, "grant_type=authorization_code", 29) === 0) {
					$output = [];
					$array = explode("&", $payload);
					for ($i = 0; $i < sizeof($array); $i++) {
						list($key, $value) = explode("=", $array[$i]);
						$output[$key] = $value;
					}
					$output['client_id'] = mask_string($output['client_id'], 12);
					$output['client_secret'] = mask_string($output['client_secret'], 12);
					$output['code'] = mask_string($output['code'], 12);
					print_r($output);
				} else if (strncmp($payload, "To=", 3) === 0) {
					$newArr = explode("&", $payload);
					print_r($newArr);
				} else {
					$sanitize = preg_replace("/[\r\n]+/", " ", $payload);
					$dec_payload = json_decode(utf8_encode($sanitize), true);
					print_r($dec_payload);
				}
			} else {
				echo "null";
			}
			break;
		case "get_result":
			$getResponse = mysqli_query($conn, "SELECT `curl_response` FROM `api_logs` WHERE `id` = '$recordId'");
			$rows = mysqli_fetch_assoc($getResponse);
			$response = $rows['curl_response'];
			if (is_null($response) == false && empty($response) == false) {
				if (strncmp($response, "<html>", 6) === 0) {
					echo $response;
				} else {
					$sanitize = preg_replace("/[\r\n]+/", " ", $response);
					$dec_response = json_decode(utf8_encode($sanitize), true);
					if (isset($dec_response['token_type']) && isset($dec_response['refresh_token'])) {
						$token_arr = $dec_response;
						$token_arr['refresh_token'] = mask_string($token_arr['refresh_token'], 12);
						$token_arr['access_token'] = mask_string($token_arr['access_token'], 60);
						print_r($token_arr);
					} else {
						print_r($dec_response);
					}
				}
			} else {
				echo "null";
			}
			break;
		case "get_webhook":
			$getWebhook = mysqli_query($conn, "SELECT * FROM `webhooks` WHERE `id` = '$recordId'");
			if (mysqli_num_rows($getWebhook) > 0) {
				$webhookData = mysqli_fetch_assoc($getWebhook);
				$response = $webhookData['payload'];
				$sanitize = preg_replace("/[\r\n]+/", " ", $response);
				$dec_response = json_decode(utf8_encode($sanitize), true);
				print_r($dec_response);
			} else {
				echo "null";
			}
			break;
		default:
			echo "Invalid Request!";
			break;
	}
} else {
	echo "Invalid Request!";
}

/**
 * Mask a string for the specified amount of characters
 *
 * @param string $string
 * @param int $length
 * @return string $masked_string
 */
function mask_string($string, $length) {
	$maskCharacter = "*";
	$mask_from_end = true;
	$maskLength = strlen($string) - $length;
	$maskedString = substr_replace($string, str_repeat($maskCharacter, $maskLength), $mask_from_end ? -$maskLength : 0, $maskLength);
	return $maskedString;
}
