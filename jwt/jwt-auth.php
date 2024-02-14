<?php

require __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = 'your-secret-key'; // Change this to a secure key
$algorithm = 'HS256';

function generate_jwt_token($user_id) {
		global $key, $algorithm;
    $issued_at = time();
    $expiration_time = $issued_at + (60 * 60); // valid for 1 hour

    $payload = array(
        'iat' => $issued_at,
        'exp' => $expiration_time,
        'user_details' => $user_id
    );

    return JWT::encode($payload, $key , $algorithm);
}


function decode_jwt_token($token) {
	global $key;
	try {
			return JWT::decode($token, new Key($key, 'HS256'));
	} catch (Exception $e) {
			return false;
	}
}


	// // Function to logout
		// function logout() {
		//     setcookie('jwt', '', time() - 3600, '/');
		//     header('Location: /login.php');
		//     exit();
		// }

	// // Check if the user is logged in
		// function isLoggedIn() {
		//     if(isset($_COOKIE['jwt'])) {
		//         $token = $_COOKIE['jwt'];
		//         $decoded = validateJWT($token);
		//         if($decoded) {
		//             return true; 
		//         } else {
		//             logout(); 
		//         }
		//     }
		//     return false;
		// }

	// // Example usage


		// if(isLoggedIn()) {
		//     echo 'Welcome, User ' . $decoded->data->userId;
		// } else {
		//     echo 'Please log in';
		// }



		