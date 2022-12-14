<?php
$page = $_SERVER['PHP_SELF'];
$pageArr = explode("/", $page);
$currentPage = $pageArr[count($pageArr) - 1];
?>
<header>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">
			<div class="navbar-brand m-0 p-0 fw-bold text-white d-flex align-items-center justify-content-center">
				<img src="assets/logo.png" class="logo" alt="App" title="App">
				<span class="app-title">Custom HubSpot App<br> <span class="org-info">By Developer</span></span>
			</div>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-bar" aria-controls="nav-bar" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
			<div class="collapse navbar-collapse" id="nav-bar">
				<ul class="navbar-nav ms-auto">
					<?php
					// session_start();
					// if (isset($_SESSION['login_user']) && empty($_SESSION['login_user']) == false) {
					if (empty($_SESSION['login_user'])) {
					?>
						<li class="nav-item">
							<a class="nav-link fw-bold text-uppercase<?= ($currentPage == "integration_logs.php") ? " active" : "" ?>" id="int-logs-link" href="integration_logs.php" title="Logs"> Logs </a>
						</li>
						<li class="nav-item">
							<a class="nav-link fw-bold text-uppercase<?= ($currentPage == "logs.php") ? " active" : "" ?>" id="logs-link" href="logs.php" title="Logs"> Logs DT </a>
						</li>
						<li class="nav-item">
							<a class="nav-link fw-bold text-uppercase<?= ($currentPage == "webhooks.php") ? " active" : "" ?>" id="webhooks-link" href="webhooks.php" title="Webhooks"> Webhooks </a>
						</li>
					<?php }
					?>
					<li class="nav-item">
						<a class="nav-link fw-bold text-uppercase" href="logout.php" onclick="return confirm('Logout')" title="Logout"> <i class="fa fa-sign-out fs-6"></i> </a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
</header>