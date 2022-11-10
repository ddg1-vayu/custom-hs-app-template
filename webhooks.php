<?php //include("session.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include("head.php"); ?>
	<title> Webhooks </title>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/r-2.2.9/sc-2.0.5/sb-1.3.2/sp-2.0.0/sl-1.3.4/sr-1.1.0/datatables.min.css" />
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/r-2.2.9/sc-2.0.5/sb-1.3.2/sp-2.0.0/sl-1.3.4/sr-1.1.0/datatables.min.js"></script>
	<link rel="stylesheet" href="css/webhooks.css">
</head>

<body class="d-flex flex-column min-vh-100">
	<?php include("header.php"); ?>

	<script src="js/scroll-top.js"></script>
	<script src="js/webhooks.js"></script>

	<main>
		<div class="container-fluid">
			<div class="row align-items-center justify-content-center">
				<div class="col-lg-12 col-md-12 col-sm-12 mb-3 text-center">
					<div class="white-container">
						<h1 class="fs-1 fw-bold m-0">Webhooks</h1>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="white-container">
						<table class="table table-bordered align-middle" id="webhooks_tbl">
							<thead class="text-center">
								<th class="webhook_type"> Type </th>
								<th class="webhook_source"> Source </th>
								<th class="webhook_file"> File </th>
								<th class="webhook_payload"> Payload </th>
								<th class="webhook_status"> Status </th>
								<th class="webhook_timestamp"> Timestamp </th>
								<th class="webhook_modified"> Last Modified </th>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</main>

	<div class="modal fade" id="data-modal" tabindex="-1" aria-hidden="true" aria-labelledby="data-modal-label">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title fw-bold text-uppercase" id="data-modal-label"></h4>
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" tabindex="-1" aria-label="Close" title="Close">&#x2718;</button>
				</div>
				<div class="modal-body text-start" id="data-modal-content"></div>
			</div>
		</div>
	</div>

	<button type="button" class="btn btn-primary" id="btn-back-to-top" title="Back to Top"><i class="fa fa-chevron-up"></i></button>

	<?php include("footer.php"); ?>
</body>

</html>