<?php

if (isset($_COOKIE['jwt_token'])) {
	$jwt_token = $_COOKIE['jwt_token'];
	// Validate JWT token
	$decoded_token = decode_jwt_token($jwt_token);
	if ($decoded_token) {
		// Token is valid, get user details from token
		$user_id = $decoded_token->user_details->id;
		$username = $decoded_token->user_details->username;
		// Optionally, you can set user ID and username in session for further use
		$_SESSION['user_id'] = $user_id;
		$_SESSION['username'] = $username;
		// Now you can proceed with displaying the welcome page content
	} else {
		// Token is expired or invalid, redirect to login page
		header("Location: login.php");
		exit;
	}
} else {
	// Token cookie not available, redirect to login page
	header("Location: logout.php");
	exit;
}
