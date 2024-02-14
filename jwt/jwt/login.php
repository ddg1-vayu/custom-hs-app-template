<?php
session_start();

// Function to decode JWT token and validate expiration
include 'jwt-auth.php';

// Check if user is already logged in
if(isset($_SESSION['user_id'])) {
		header("Location: welcome.php");
		exit;
}

// Check if the form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Perform your authentication here (e.g., check username and password against database)
    $username = "123"; // Replace with actual username
    $password = "abc@123"; // Replace with actual password

    if($_POST['username'] == $username && $_POST['password'] == $password) {
        // Authentication successful, set session variables
        $_SESSION['user_id'] = 1; // You can set any user identifier here
        $_SESSION['username'] = $username; // Save username in session
        // Generate JWT token with user ID and username
        $user_details = array(
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
						'time' => time()
        );
        $jwt_token = generate_jwt_token($user_details);
        $_SESSION['jwt_token'] = $jwt_token; // Save JWT token in session
				$timeToLogout = time() + 60;
        // Set JWT token in a cookie
        // setcookie('jwt_token', $jwt_token, time() + (60 * 60), '/'); // Cookie valid for 1 hour
        setcookie('jwt_token', $jwt_token, $timeToLogout , '/'); // Cookie valid for 1 hour
        header("Location: welcome.php");
        exit;
    } else {
        $error_message = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if(isset($error_message)) { ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php } ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="123"><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" value="abc@123"><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
