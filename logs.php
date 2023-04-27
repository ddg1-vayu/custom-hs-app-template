<?php //include_once("session.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title> Logs </title>
	<?php include_once("head.php"); ?>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/r-2.2.9/sc-2.0.5/sb-1.3.2/sp-2.0.0/sl-1.3.4/sr-1.1.0/datatables.min.css" />
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/r-2.2.9/sc-2.0.5/sb-1.3.2/sp-2.0.0/sl-1.3.4/sr-1.1.0/datatables.min.js"></script>
	<script src="js/preload.js"></script>
	<script src="js/custom.js"></script>
	<script src="js/datatables.js"></script>
	<script src="js/ajax.js"></script>
	<link rel="stylesheet" href="css/datatables.css">
	<link rel="stylesheet" href="css/logs.css">
</head>

<body class="d-flex flex-column min-vh-100">
	<div class="preloader">
		<img src="assets/preloader.gif" alt="Loading...">
	</div>

	<?php include_once("header.php"); ?>

	<main>
		<section id="logs">
			<div class="container-fluid">
				<div class="row align-items-center">
					<div class="col-lg-12 col-md-12 col-sm-12 mb-3">
						<div class="white-container">
							<h1 class="fs-1 fw-bold m-0"> API Logs </h1>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="white-container">
							<table class="table table-responsive table-bordered align-middle" id="api_logs">
								<thead class="text-center text-uppercase">
									<tr>
										<th class="curl_type"> Action </th>
										<th class="curl_origin"> Origin </th>
										<th class="curl_portal"> Portal </th>
										<th class="curl_file"> File </th>
										<th class="curl_url"> Endpoint </th>
										<th class="curl_method"> Method </th>
										<th class="curl_payload"> Payload </th>
										<th class="curl_payload"> Payload JSON </th>
										<th class="curl_http_code"> HTTP Code </th>
										<th class="curl_response"> Response </th>
										<th class="curl_response"> Response JSON </th>
										<th class="curl_timestamp"> Timestamp </th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>

	<?php include_once("modal.php"); ?>

	<?php include_once("footer.php"); ?>
</body>

</html>