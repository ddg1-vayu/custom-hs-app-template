<?php
session_start();
if (isset($_SESSION['login_user'])) {
	header("location: logs.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include("head.php"); ?>
	<title> Login </title>
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
					<div class="form-box">
						<img src="assets/placeholder.png" class="user-img" alt="placeholder" title="placeholder">
						<form action="login.php" method="post" id="login-form">
							<div class="mt-3">
								<input type="text" class="form-control" id="username" name="username" placeholder="Email/Username" title="User">
							</div>
							<div class="mt-3">
								<input type="password" class="form-control" name="password" id="password" placeholder="Password" title="Password">
							</div>
							<button type="button" name="login" id="login" class="btn btn-primary mt-3" title="Login" value="login"> LOGIN </button>
							<div class="col-lg-12 col-md-12 col-sm-12 text-center" id="alert-div" style="display: none;">
								<div class="alert m-0" role="alert" id="alert">
									<div id="alert-text"></div>
								</div>
							</div>
						</form>
						<script src="js/login.js"></script>
					</div>
				</div>
			</div>
		</div>
	</main>

	<?php include("footer.php"); ?>
</body>

</html>