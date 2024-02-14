<?php

require __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'your-secret-key'; // Change this to a secure key
$algorithm = 'HS256';

function generate_jwt_token($user_id)
{
	global $key, $algorithm;
	$issued_at = time();
	$expiration_time = $issued_at + (60 * 60); // valid for 1 hour

	$payload = array(
		'iat' => $issued_at,
		'exp' => $expiration_time,
		'user_details' => $user_id
	);

	return JWT::encode($payload, $key, $algorithm);
}


function decode_jwt_token($token)
{
	global $key;
	try {
		return JWT::decode($token, new Key($key, 'HS256'));
	} catch (Exception $e) {
		return false;
	}
}
