<?php
// ini_set("display_errors", 1);
require_once("conn.php");

if (isset($_POST['action']) && empty($_POST['action']) == false) {
	$action = $_POST['action'];
	$recordId = $_POST['recordId'];

	http_response_code(200);

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
				} else if (strncmp($payload, "To=whatsapp", 11) === 0) {
					if ($action == "get_payload_json") {
						echo $payload;
						die;
					} else {
						$dataArr = [];
						$newArr = explode("&", $payload);
						if (empty($newArr) == false) {
							foreach ($newArr as $values) {
								$dataArr = explode("=", $values);
								$output[$dataArr[0]] = urldecode($dataArr[1]);
							}
						}
					}
				} elseif (strncmp($payload, "{", 1) === 0 || strncmp($payload, "[", 1) === 0) {
					$sanitize = preg_replace("/[\r\n]+/", " ", $payload);
					$output = json_decode(mb_convert_encoding($sanitize, "UTF-8", mb_list_encodings()), true);
				} else {
					echo $payload;
				}

				if ($action == "get_payload") {
					print_r($output);
				} elseif ($action == "get_payload_json") {
					echo json_encode($output, JSON_PRETTY_PRINT);
				}
			} else {
				echo "null";
			}
			break;
		case "get_response":
		case "get_response_json":
			$getResponse = mysqli_query($conn, "SELECT `curl_response` FROM `api_logs` WHERE `id` = '$recordId'");
			$rows = mysqli_fetch_assoc($getResponse);
			$response = $rows['curl_response'];

			$output = [];

			if (is_null($response) == false && empty($response) == false) {
				if (strncmp($response, "{", 1) === 0 || strncmp($response, "[", 1) === 0) {
					$sanitize = preg_replace("/[\r\n]+/", " ", $response);
					$dec_response = json_decode(mb_convert_encoding($sanitize, "UTF-8", mb_list_encodings()), true);

					if (isset($dec_response['token_type']) && isset($dec_response['refresh_token'])) {
						$token_arr = $dec_response;
						$token_arr['refresh_token'] = mask_string($token_arr['refresh_token'], 12);
						$token_arr['access_token'] = mask_string($token_arr['access_token'], 48);
						$output = $token_arr;
					} else {
						$output = $dec_response;
					}

					if ($action == "get_response") {
						print_r($output);
					} elseif ($action == "get_response_json") {
						echo json_encode($output, JSON_PRETTY_PRINT);
					}
				} else {
					echo $response;
				}
			} else {
				echo "null";
			}
			break;
		case "get_webhook":
		case "get_webhook_json":
			$getWebhook = mysqli_query($conn, "SELECT `payload` FROM `webhooks` WHERE `id` = '$recordId'");
			if (mysqli_num_rows($getWebhook) > 0) {
				$webhookData = mysqli_fetch_assoc($getWebhook);
				$response = $webhookData['payload'];
				$sanitize = preg_replace("/[\r\n]+/", " ", $response);
				$dec_response = json_decode(mb_convert_encoding($sanitize, "UTF-8", mb_list_encodings()), true);
				if ($action == "get_webhook") {
					print_r($dec_response);
				} elseif ($action == "get_webhook_json") {
					echo json_encode($dec_response, JSON_PRETTY_PRINT);
				}
			} else {
				echo "null";
			}
			break;
		case "get_file":
			$file = "null";
			$getFile = mysqli_query($conn, "SELECT `file_path`, `file_type` FROM `uploads` WHERE `id` = '$recordId'");
			if (mysqli_num_rows($getFile) > 0) {
				$rows = mysqli_fetch_assoc($getFile);
				$file_path = $rows['file_path'];
				$file_type = $rows['file_type'];

				if ($file_type == "application/pdf") {
					$file = '<object data="' . $file_path . '" type="application/pdf"> <p class="text-center lh-1 m-0">This PDF cannot be displayed here! <a href="' . $file_path . '">View/Download</a> the PDF</p> </object>';
				} elseif ($file_type == "text/plain") {
					$file = '<object type="text/plain" data="' . $file_path . '"></object>';
				} else {
					if (in_array($file_type, $documentTypesArr)) {
						$file = '<object data="https://view.officeapps.live.com/op/embed.aspx?src=' . $file_path . '"></object>';
					} elseif (in_array($file_type, $imageTypesArr)) {
						$file = '<img class="img-fluid" src="' . $file_path . '" alt="Image">';
					} elseif (in_array($file_type, $videoTypesArr)) {
						$file = '<video controls> <source src="' . $file_path . '" type="video/mp4"> <source src="' . $file_path . '" type="video/mpeg"> <source src="' . $file_path . '" type="video/quicktime"> <source src="' . $file_path . '" type="audio/webm"> </video>';
					} elseif (in_array($file_type, $audioTypesArr)) {
						$file = ' <div class="audio-container"> <audio controls> <source src="' . $file_path . '" type="audio/mp3"> <source src="' . $file_path . '" type="audio/aac"> <source src="' . $file_path . '" type="audio/ogg"> <source src="' . $file_path . '" type="audio/wav"> </audio> </div>';
					}
				}
			}
			echo $file;
			break;
		case "get_link":
			$getLink = mysqli_query($conn, "SELECT `file_path` FROM `uploads` WHERE `id` = '$recordId'");
			if (mysqli_num_rows($getLink) > 0) {
				$rows = mysqli_fetch_assoc($getLink);
				echo (empty($rows['file_path']) == false) ? $rows['file_path'] : "null";
			} else {
				echo "null";
			}
			break;
		default:
			echo "Invalid Request!";
			http_response_code(400);
			break;
	}
} else {
	echo "Invalid Request!";
	http_response_code(400);
}

/**
 * Mask a string for the specified amount of characters
 *
 * @param string $string
 * @param int $length
 * @return string $masked_string
 */
function mask_string($string, $length)
{
	$maskCharacter = "x";
	$mask_from_end = true;
	$maskLength = strlen($string) - $length;
	$maskedString = substr_replace($string, str_repeat($maskCharacter, $maskLength), $mask_from_end ? -$maskLength : 0, $maskLength);
	return $maskedString;
}
