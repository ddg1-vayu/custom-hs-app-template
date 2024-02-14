<?php
session_start();

// Include JWT library and define key and algorithm
require __DIR__ . '/vendor/autoload.php';
// Function to decode JWT token and validate expiration
include 'jwt-auth.php';
// Check if JWT token cookie is set or not
include 'auth_check.php';

?>

<!DOCTYPE html>
<html lang="en">
<?php
include 'header.php';
?>
<title>Profile</title>

<body>
	<h2>This is the profile page, <?php echo $username; ?> </h2>
	<p>This is the profile page. You are logged in!</p>
	<p>This is the profile page. You are logged in!</p>
	<a href="welcome.php">welcome</a>
	<p>This is the profile page. You are logged in!</p>
	<p>This is the profile page. You are logged in!</p>
	<a href="hit-me.php">Hit The API</a>
	<p>This is the profile page. You are logged in!</p>
	<p>This is the profile page. You are logged in!</p>
	<a href="logout.php">Logout</a>
</body>

</html>