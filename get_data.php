<?php
ini_set("display_errors", 1);
require("conn.php");

if (isset($_POST['action']) && empty($_POST['action']) == false) {
	$action = $_POST['action'];
	$recordId = $_POST['recordId'];

	switch ($action) {
		case "get_endpoint":
			$getEndpoint = mysqli_query($conn, "SELECT `curl_url` FROM `api_logs` WHERE `id` = '$recordId'");
			$rows = mysqli_fetch_assoc($getEndpoint);
			$endpoint = $rows['curl_url'];

			if (is_null($endpoint) == false && empty($endpoint) == false) {
				echo $endpoint;
			} else {
				echo "null";
			}
			break;
		case "get_payload":
			$output = [];

			$getPayload = mysqli_query($conn, "SELECT `curl_payload` FROM `api_logs` WHERE `id` = '$recordId'");
			$rows = mysqli_fetch_assoc($getPayload);
			$payload = $rows['curl_payload'];

			if (is_null($payload) == false && empty($payload) == false) {
				if (strncmp($payload, "grant_type=refresh_token", 24) === 0) {
					$array = explode("&", $payload);
					for ($i = 0; $i < sizeof($array); $i++) {
						list($key, $value) = explode("=", $array[$i]);
						$output[$key] = $value;
					}
					$output['client_id'] = mask_string($output['client_id'], 12);
					$output['client_secret'] = mask_string($output['client_secret'], 12);
					$output['refresh_token'] = mask_string($output['refresh_token'], 12);
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
				} else {
					$sanitize = preg_replace("/[\r\n]+/", " ", $payload);
					$output = json_decode(mb_convert_encoding($sanitize, "UTF-8", mb_list_encodings()), true);
				}
				print_r($output);
			} else {
				echo "null";
			}
			break;
		case "get_payload_json":
			$output = [];

			$getPayload = mysqli_query($conn, "SELECT `curl_payload` FROM `api_logs` WHERE `id` = '$recordId'");
			$rows = mysqli_fetch_assoc($getPayload);
			$payload = $rows['curl_payload'];

			if (is_null($payload) == false && empty($payload) == false) {
				if (strncmp($payload, "grant_type=refresh_token", 24) === 0) {
					$array = explode("&", $payload);
					for ($i = 0; $i < sizeof($array); $i++) {
						list($key, $value) = explode("=", $array[$i]);
						$output[$key] = $value;
					}
					$output['client_id'] = mask_string($output['client_id'], 12);
					$output['client_secret'] = mask_string($output['client_secret'], 12);
					$output['refresh_token'] = mask_string($output['refresh_token'], 12);
				} elseif (strncmp($payload, "grant_type=authorization_code", 29) === 0) {
					$array = explode("&", $payload);
					for ($i = 0; $i < sizeof($array); $i++) {
						list($key, $value) = explode("=", $array[$i]);
						$output[$key] = $value;
					}
					$output['client_id'] = mask_string($output['client_id'], 12);
					$output['client_secret'] = mask_string($output['client_secret'], 12);
					$output['code'] = mask_string($output['code'], 12);
				} else {
					$sanitize = preg_replace("/[\r\n]+/", " ", $payload);
					$output = json_decode(mb_convert_encoding($sanitize, "UTF-8", mb_list_encodings()), true);
				}
				echo json_encode($output, JSON_PRETTY_PRINT);
			} else {
				echo "null";
			}
			break;
		case "get_response":
			$getResponse = mysqli_query($conn, "SELECT `curl_response` FROM `api_logs` WHERE `id` = '$recordId'");
			$rows = mysqli_fetch_assoc($getResponse);
			$response = $rows['curl_response'];

			if (empty($response) == false && $response != "null" && $response != NULL) {
				if (strncmp($response, "<html>", 6) === 0 || strncmp($response, "<!DOCTYPE", 8) === 0) {
					echo $response;
				} else {
					$sanitize = preg_replace("/[\r\n]+/", " ", $response);
					$dec_response = json_decode(mb_convert_encoding($sanitize, "UTF-8", mb_list_encodings()), true);

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
			$getWebhook = mysqli_query($conn, "SELECT `payload` FROM `webhooks` WHERE `id` = '$recordId'");
			if (mysqli_num_rows($getWebhook) > 0) {
				$webhookData = mysqli_fetch_assoc($getWebhook);
				$response = $webhookData['payload'];
				$sanitize = preg_replace("/[\r\n]+/", " ", $response);
				$dec_response = json_decode(mb_convert_encoding($sanitize, "UTF-8", mb_list_encodings()), true);
				print_r($dec_response);
			} else {
				echo "null";
			}
			break;
		case "get_webhook_json":
			$getWebhook = mysqli_query($conn, "SELECT `payload` FROM `webhooks` WHERE `id` = '$recordId'");
			if (mysqli_num_rows($getWebhook) > 0) {
				$webhookData = mysqli_fetch_assoc($getWebhook);
				$response = $webhookData['payload'];
				$sanitize = preg_replace("/[\r\n]+/", " ", $response);
				$dec_response = json_decode(mb_convert_encoding($sanitize, "UTF-8", mb_list_encodings()), true);
				echo json_encode($dec_response, JSON_PRETTY_PRINT);
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
	$maskCharacter = "x";
	$mask_from_end = true;
	$maskLength = strlen($string) - $length;
	$maskedString = substr_replace($string, str_repeat($maskCharacter, $maskLength), $mask_from_end ? -$maskLength : 0, $maskLength);
	return $maskedString;
}
