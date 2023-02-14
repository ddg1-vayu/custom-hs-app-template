<?php
$adminPages = ["activity.php", "integration_logs.php", "integration_webhooks.php", "logs.php", "webhooks.php"];
$page = $_SERVER['PHP_SELF'];
$pageArr = explode("/", $page);
$currentPage = $pageArr[count($pageArr) - 1];
?>
<header>
	<nav class="navbar navbar-expand-lg">
		<div class="container-fluid">
			<div class="navbar-brand m-0 p-0 fw-bold d-flex align-items-center justify-content-center">
				<img src="assets/logo.png" class="logo" alt="Custom Integration" title="Custom Integration">
				<?php // <span class="app-title">Custom Integration</span> ?>
			</div>
			<?php
			if (in_array($currentPage, $adminPages)) {
			?>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-bar" aria-controls="nav-bar" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
				<div class="collapse navbar-collapse" id="nav-bar">
					<ul class="navbar-nav ms-auto">
						<li class="nav-item">
							<a class="nav-link fw-bold text-uppercase<?= ($currentPage == "activity.php") ? " active" : "" ?>" href="activity.php" title="Activity"> Activity </a>
						</li>
						<li class="nav-item">
							<a class="nav-link fw-bold text-uppercase<?= ($currentPage == "integration_logs.php") ? " active" : "" ?>" href="integration_logs.php" title="Logs"> Logs </a>
						</li>
						<li class="nav-item">
							<a class="nav-link fw-bold text-uppercase<?= ($currentPage == "logs.php") ? " active" : "" ?>" href="logs.php" title="Logs"> Logs DT </a>
						</li>
						<li class="nav-item">
							<a class="nav-link fw-bold text-uppercase<?= ($currentPage == "integration_webhooks.php") ? " active" : "" ?>" href="integration_webhooks.php" title="Webhooks"> Webhooks </a>
						</li>
						<li class="nav-item">
							<a class="nav-link fw-bold text-uppercase<?= ($currentPage == "webhooks.php") ? " active" : "" ?>" href="webhooks.php" title="Webhooks"> Webhooks DT </a>
						</li>
						<li class="nav-item">
							<a class="nav-link fw-bold text-uppercase" href="logout.php" onclick="return confirm('Logout')" title="Logout"> <i class="fa fa-sign-out fs-6"></i> </a>
						</li>
					</ul>
				</div>
			<?php
			}
			?>
		</div>
	</nav>
</header>