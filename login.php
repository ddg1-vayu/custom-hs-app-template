<?php
ini_set("display_errors", 1);
session_start();
if (isset($_SESSION['login_username'])) {
	header("location: logs.php");
}

if (isset($_POST['submit']) && $_POST['submit'] == "login") {
	include("conn.php");
	
	$user = stripslashes($_POST["user"]);
	$password = stripslashes($_POST["password"]);

	$getUsers = mysqli_query($conn, "SELECT * FROM `users` WHERE `username` = BINARY '$user' OR `email` = BINARY '$user' LIMIT 1");
	if (mysqli_num_rows($getUsers) > 0) {
		$rows = mysqli_fetch_assoc($getUsers);
		$userId = $rows['id'];
		$first_name = $rows['first_name'];
		$userPassword = $rows['password'];

		if (password_verify($password, $userPassword)) {
			$_SESSION['id'] = $userId;
			$_SESSION['login_username'] = $user;
			$_SESSION['first_name'] = $first_name;

			$http_cookie = (isset($_SERVER['HTTP_COOKIE']) && empty($_SERVER['HTTP_COOKIE']) == false) ? addslashes($_SERVER['HTTP_COOKIE']) : "";
			$remote_address = (isset($_SERVER['REMOTE_ADDR']) && empty($_SERVER['REMOTE_ADDR']) == false) ? addslashes($_SERVER['REMOTE_ADDR']) : "";
			$remote_port = (isset($_SERVER['REMOTE_PORT']) && empty($_SERVER['REMOTE_PORT']) == false) ? addslashes($_SERVER['REMOTE_PORT']) : "";
			$ua_platform = (isset($_SERVER['HTTP_SEC_CH_UA_PLATFORM']) && empty($_SERVER['HTTP_SEC_CH_UA_PLATFORM']) == false) ? addslashes(trim(str_replace("\"", "", $_SERVER['HTTP_SEC_CH_UA_PLATFORM']))) : "";
			$ua_version = (isset($_SERVER['HTTP_SEC_CH_UA']) && empty($_SERVER['HTTP_SEC_CH_UA']) == false) ? addslashes(implode(" | ", explode(", ", str_replace("\"", "", $_SERVER['HTTP_SEC_CH_UA'])))) : "";
			$user_agent = (isset($_SERVER['HTTP_USER_AGENT']) && empty($_SERVER['HTTP_USER_AGENT']) == false) ? addslashes($_SERVER['HTTP_USER_AGENT']) : "";

			mysqli_query($conn, "INSERT INTO `admin_access_logs` (`user_id`, `user`, `http_cookie`, `remote_address`, `remote_port`, `ua_platform`, `ua_version`, `user_agent`, `action`) VALUES ('$userId', '$user', '$http_cookie', '$remote_address', '$remote_port', '$ua_platform', '$ua_version', '$user_agent', 'login')");

			echo "<script> alert('Login Successful! Welcome " . $first_name . "'); window.location='logs.php'; </script>";
		} else {
			echo "<script> alert('Incorrect Password!'); window.location='login.php'; </script>";
		}
	} else {
		echo "<script> alert('" . $user . " is not an Authorized User! Access Denied!'); window.location='login.php'; </script>";
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include("head.php"); ?>
	<title> Login </title>
</head>

<body class="d-flex flex-column min-vh-100">
	<?php include("header.php"); ?>

	<main>
		<div class="container-fluid">
			<div class="row align-items-center justify-content-center">
				<div class="col-lg-4 col-md-8 col-sm-12">
					<div class="white-container">
						<div class="text-center mb-4">
							<img src="assets/placeholder.png" class="img-thumbnail rounded-circle w-50" alt="Placeholder" title="Placeholder">
						</div>
						<form action="login.php" method="post">
							<div>
								<input type="text" class="form-control" id="user" name="user" placeholder="Email/Username" title="Email/Username" autocomplete="username" required>
							</div>
							<div class="mt-3">
								<input type="password" class="form-control" name="password" id="password" placeholder="Password" title="Password" autocomplete="current-password" required>
							</div>
							<div class="mt-3 text-center">
								<button type="submit" name="submit" id="submit" class="btn btn-primary" title="Login" value="login"> LOGIN </button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>
	<?php include("footer.php"); ?>
</body>

</html>