<?php //require_once("session.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once("head.php"); ?>
	<title> Uploads </title>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/r-2.2.9/sc-2.0.5/sb-1.3.2/sp-2.0.0/sl-1.3.4/sr-1.1.0/datatables.min.css" />
	<script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/r-2.2.9/sc-2.0.5/sb-1.3.2/sp-2.0.0/sl-1.3.4/sr-1.1.0/datatables.min.js"></script>
	<script src="js/preload.js"></script>	
	<script src="js/custom.js"></script>
	<script src="js/datatables.js"></script>
	<script src="js/ajax.js"></script>
	<link rel="stylesheet" href="css/datatables.css">
	<link rel="stylesheet" href="css/uploads.css">
</head>

<body class="d-flex flex-column min-vh-100">
	<div class="preloader">
		<img src="assets/preloader.gif" alt="Loading...">
	</div>

	<?php include_once("header.php"); ?>

	<main>
		<div class="container-fluid">
			<?php include_once("toast.php"); ?>

			<div class="row align-items-center">
				<div class="col-lg-12 col-md-12 col-sm-12 mb-3">
					<div class="white-container">
						<div class="d-flex align-items-center justify-content-between">
							<h1 class="fs-1 fw-bold m-0"> Uploads </h1>
							<button type="button" class="btn btn-primary" title="Upload File" data-bs-toggle="modal" data-bs-target=" #upload-modal">
								<i class="fa-solid fa-upload" aria-hidden="true"></i>
							</button>
						</div>
					</div>
				</div>

				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="white-container">
						<table class="table table-responsive table-bordered align-middle" id="uploads_tbl">
							<thead class="text-center text-uppercase">
								<tr>
									<th class="file_name"> Name </th>
									<th class="file_type"> Type </th>
									<th class="file_size"> Size </th>
									<th class="file_timestamp"> Uploaded </th>
									<th class="file_timestamp"> Last Modified </th>
									<th class="file_actions"> Actions </th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</main>

	<div class="modal fade" id="upload-modal" tabindex="-1" aria-hidden="true" aria-labelledby="upload-modal-label">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title fw-bold text-uppercase" id="upload-modal-label"> Upload </h4>
					<i type="button" title="Close" class="fa-regular fa-circle-xmark text-danger fs-3" data-bs-dismiss="modal" aria-label="Close"></i>
				</div>
				<div class="modal-body text-start" id="upload-modal-content">
					<div class="alert alert-success text-center" id="upload-alert" style="display: none;"> Alert </div>
					<form method="POST" id="upload-form" name="upload-form" enctype="multipart/form-data">
						<div class="row align-items-center g-3">
							<div class="col-lg-12 col-md-12 col-sm-12 hidden-div">
								<input type="hidden" name="action" id="action" class="form-control hidden-div" value="upload_file" readonly>
							</div>
							<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
								<input type="file" class="form-control form-control-lg" id="upload_file" name="upload_file" title="Select File">
							</div>
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
								<button type="button" class="btn btn-primary w-100" id="submit" name="submit" value="upload" title="Upload" onclick="upload()"> Upload </button>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="progress-bar" style="display: none;">
								<div class="progress">
									<div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" aria-label="Upload Progress" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php include_once("modal.php"); ?>

	<?php include_once("footer.php"); ?>
</body>

</html>