<?php
session_start();
if (isset($_SESSION['login_user'])) {
	header("location: logs.php");
}

if (isset($_POST['submit']) && $_POST['submit'] == "login") {
	$user = stripslashes($_POST["user"]);
	$password = stripslashes($_POST["password"]);

	include("conn.php");

	$getUsers = mysqli_query($conn, "SELECT * FROM `users` WHERE `username` = '$user' OR `email` = '$user' LIMIT 1");

	if (mysqli_num_rows($getUsers) > 0) {
		$rows = mysqli_fetch_assoc($getUsers);
		$firstName = $rows['first_name'];
		$userId = $rows['id'];
		$userPassword = $rows['password'];

		if (password_verify($password, $userPassword)) {
			$_SESSION['id'] = $userId;
			$_SESSION['login_user'] = $user;
			$ip_address = $_SERVER['REMOTE_ADDR'];
			$platform = trim(str_replace("\"", "", $_SERVER['HTTP_SEC_CH_UA_PLATFORM']));

			mysqli_query($conn, "INSERT INTO `access_logs` (`user_id`, `user`, `login`, `platform`, `ip_address`) VALUES ('$userId', '$user', current_timestamp(), '$platform', '$ip_address')");
			mysqli_query($conn, "UPDATE `users` SET `last_login` = current_timestamp() WHERE `id` = '$userId' LIMIT 1");

			echo "<script> alert('Login Successful! Welcome " . $firstName . "'); window.location='logs.php'; </script>";
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
	<link rel="stylesheet" href="css/stylesheet.css">
</head>

<body class="d-flex flex-column min-vh-100">
	<header>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<div class="container-fluid justify-content-center">
				<div class="navbar-brand m-0 p-0 fw-bold text-white d-flex align-items-center justify-content-center">
					<img src="assets/logo.png" class="logo" alt="Custom App" title="Custom App">
					<span class="app-title">Custom HubSpot App<br> <span class="org-info">By Developer</span></span>
				</div>
			</div>
		</nav>
	</header>
	<main>
		<div class="container-fluid">
			<div class="row align-items-center justify-content-center">
				<div class="col-lg-5 col-md-10 col-sm-12">
					<div class="white-container text-center">
						<form action="login.php" method="post">
							<input type="text" class="form-control" id="user" name="user" placeholder="Email/Username" title="User" required>
							<input type="password" class="form-control my-3" name="password" id="password" placeholder="Password" title="Password" required>
							<button type="submit" name="submit" id="submit" class="btn btn-primary" title="Login" value="login"> LOGIN </button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>
	<?php include("footer.php"); ?>
</body>

</html>