<?php
session_start();
if (isset($_SESSION['login_user'])) {
	header("location: logs.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title> Login </title>
	<?php include("head.php"); ?>
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
				<div class="col-lg-4 col-md-8 col-sm-12">
					<div class="form-container">
						<div id="form-div" class="text-center">
							<img src="assets/placeholder.png" class="placeholder-img" alt="placeholder" title="placeholder">
							<form action="login.php" method="post" id="login-form">
								<div>
									<input type="text" class="form-control" id="username" name="username" placeholder="Email/Username" title="Email/Username">
								</div>
								<div class="mt-3">
									<input type="password" class="form-control" id="password" name="password" placeholder="Password" title="Password">
								</div>
								<button type="button" name="login" id="login" class="btn btn-primary mt-4" title="Login" value="login"> LOGIN </button>
								<div class="col-lg-12 col-md-12 col-sm-12 text-center" id="alert-div" style="display: none;">
									<div class="alert m-0" role="alert" id="alert">
										<div id="alert-text"></div>
									</div>
								</div>
							</form>
						</div>
						<div id="error-div"></div>
					</div>
				</div>
			</div>
		</div>
		<script src="js/login.js"></script>
	</main>

	<?php include("footer.php"); ?>
</body>

</html>