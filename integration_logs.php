<?php //include("session.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include("head.php"); ?>
	<title> Integration Logs </title>
	<style>
		@import url("https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600;700;800&display=swap");
		.modal-dialog{margin:4rem auto!important;max-width:75%!important}
		.form-control,.form-select{padding:.5rem!important}
		.form-select{padding-right:2.25rem!important}
		thead{border-bottom: 3px solid black;}
		th{text-transform:uppercase}
		td{padding:6px!important;vertical-align:middle}
		td>button.btn{padding:2px 8px;font-size:14px;font-weight:500;border-width:2px}
		.alert{margin:0;padding:14px;border-width:2px}
		.total-records{padding:12px 24px;border-radius:.375rem;font-weight:700;font-size:1rem;display:inline-block}
		.pagination{justify-content:end}
		.page-link.active{background-color:#06c!important;border-color:#06c!important}
		.page-link.disabled{color:#fff}
		#record-count,#record-pagination{margin:.75rem 0}
		@media screen and (max-width:767px){
			#record-count{text-align:center} .page-item{margin:.25rem} .page-link{margin-left:0!important} #records-table{margin:0 0 1rem} ul.pagination{justify-content:center;flex-wrap:wrap}
		}
	</style>
</head>

<body class="d-flex flex-column min-vh-100">
	<?php include("header.php"); ?>

	<script src="js/scroll-top.js"></script>

	<?php
	date_default_timezone_set("Asia/Kolkata");
	$fileName = pathinfo(__FILE__, PATHINFO_FILENAME);
	$fileExtension = pathinfo(__FILE__, PATHINFO_EXTENSION);
	$file = $fileName . "." . $fileExtension;
	include("conn.php");
	?>

	<main>
		<div class="container-fluid">
			<div class="white-container mb-3">
				<div class="d-flex align-items-center justify-content-between">
					<h4 class="fs-1 fw-bold m-0"> Integration Logs </h4>
					<button class="btn btn-secondary" onclick="showFilters()"> Filter <i class="fa fa-filter ps-1" aria-hidden="true"></i> </button>
				</div>
			</div>
			<div class="white-container mb-3" id="filter-form" style="<?php echo isset($_GET['search']) ? 'display:block;' : 'display:none;'; ?>">
				<form method="GET" id="search-form">
					<div class="row align-items-center justify-content-between">
						<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
							<label for="start_date" class="form-label"> Start Date </label>
							<input type="date" class="form-control" name="start_date" id='start_date' placeholder="From Date" title="From Date" value="<?php if (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '') { $_REQUEST['start_date']; } ?>">
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
							<label for="end_date" class="form-label"> End Date </label>
							<input type="date" class="form-control" name="end_date" id='end_date' placeholder="To Date" title="To Date" value="<?php if (isset($_REQUEST['end_date']) && $_REQUEST['end_date'] != '') { echo $_REQUEST['end_date']; } ?>">
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
							<label for="hub_portal_id" class="form-label"> Portal </label>
							<select name="hub_portal_id" id="hub_portal_id" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getPortals = mysqli_query($conn, "SELECT DISTINCT `hub_portal_id` FROM `api_logs` WHERE `hub_portal_id` != '' ORDER BY `hub_portal_id` ASC");
								if (mysqli_num_rows($getPortals) > 0) {
									while ($rows = mysqli_fetch_assoc($getPortals)) {
										$hub_portal_sel = "";
										if ($rows['hub_portal_id'] == $_REQUEST['hub_portal_id']) {
											$hub_portal_sel = "selected='selected'";
										}
								?>
										<option value="<?= $rows['hub_portal_id'] ?>" <?= $hub_portal_sel ?>><?= $rows['hub_portal_id'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
							<label for="curl_type" class="form-label"> Type </label>
							<select name="curl_type" id="curl_type" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getType = mysqli_query($conn, "SELECT DISTINCT `curl_type` FROM `api_logs` WHERE `curl_type` != '' ORDER BY `curl_type` ASC");
								if (mysqli_num_rows($getType) > 0) {
									while ($rows = mysqli_fetch_assoc($getType)) {
										$hub_portal_sel = "";
										if ($rows['curl_type'] == $_REQUEST['curl_type']) {
											$hub_portal_sel = "selected='selected'";
										}
								?>
										<option value="<?= $rows['curl_type'] ?>" <?= $hub_portal_sel ?>><?= $rows['curl_type'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
							<label for="file_name" class="form-label"> File </label>
							<select name="file_name" id="file_name" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getFileNames = mysqli_query($conn, "SELECT DISTINCT `file_name` FROM `api_logs` WHERE `file_name` != '' ORDER BY `file_name` ASC");
								if (mysqli_num_rows($getFileNames) > 0) {
									while ($rows = mysqli_fetch_assoc($getFileNames)) {
										$hub_portal_sel = "";
										if ($rows['file_name'] == $_REQUEST['file_name']) {
											$hub_portal_sel = "selected='selected'";
										}
								?>
										<option value="<?= $rows['file_name'] ?>" <?= $hub_portal_sel ?>><?= $rows['file_name'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
							<label for="api_origin" class="form-label"> API Origin </label>
							<select name="api_origin" id="api_origin" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getApiOrigin = mysqli_query($conn, "SELECT DISTINCT `api_origin` FROM `api_logs` WHERE `api_origin` != '' ORDER BY `api_origin` ASC");
								if (mysqli_num_rows($getApiOrigin) > 0) {
									while ($rows = mysqli_fetch_assoc($getApiOrigin)) {
										$hub_portal_sel = "";
										if ($rows['api_origin'] == $_REQUEST['api_origin']) {
											$hub_portal_sel = "selected='selected'";
										}
								?>
										<option value="<?= $rows['api_origin'] ?>" <?= $hub_portal_sel ?>><?= $rows['api_origin'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
							<label for="curl_url" class="form-label"> Endpoint </label>
							<select name="curl_url" id="curl_url" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getEndpoints = mysqli_query($conn, "SELECT DISTINCT `curl_url` FROM `api_logs` WHERE `curl_url` != '' ORDER BY `curl_url` ASC");
								if (mysqli_num_rows($getEndpoints) > 0) {
									while ($rows = mysqli_fetch_assoc($getEndpoints)) {
										$hub_portal_sel = "";
										if ($rows['curl_url'] == $_REQUEST['curl_url']) {
											$hub_portal_sel = "selected='selected'";
										}
								?>
										<option value="<?= $rows['curl_url'] ?>" <?= $hub_portal_sel ?>><?= $rows['curl_url'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
							<label for="curl_method" class="form-label"> Method </label>
							<select name="curl_method" id="curl_method" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getMethods = mysqli_query($conn, "SELECT DISTINCT `curl_method` FROM `api_logs` WHERE `curl_method` != '' ORDER BY `curl_method` ASC");
								if (mysqli_num_rows($getMethods) > 0) {
									while ($rows = mysqli_fetch_assoc($getMethods)) {
										$hub_portal_sel = "";
										if ($rows['curl_method'] == $_REQUEST['curl_method']) {
											$hub_portal_sel = "selected='selected'";
										}
								?>
										<option value="<?= $rows['curl_method'] ?>" <?= $hub_portal_sel ?>><?= $rows['curl_method'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
							<label for="curl_http_code" class="form-label"> HTTP Response Code </label>
							<select name="curl_http_code" id="curl_http_code" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getResponseCodes = mysqli_query($conn, "SELECT DISTINCT `curl_http_code` FROM `api_logs` WHERE `curl_http_code` != '' ORDER BY `curl_http_code` ASC");
								if (mysqli_num_rows($getResponseCodes) > 0) {
									while ($rows = mysqli_fetch_assoc($getResponseCodes)) {
										$hub_portal_sel = "";
										if ($rows['curl_http_code'] == $_REQUEST['curl_http_code']) {
											$hub_portal_sel = "selected='selected'";
										}
								?>
										<option value="<?= $rows['curl_http_code'] ?>" <?= $hub_portal_sel ?>><?= $rows['curl_http_code'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 text-center mt-2">
							<input type="hidden" name="search" value="search">
							<button type="submit" class="btn btn-primary text-uppercase"> Filter </button>
							<button type="button" class="btn btn-secondary text-uppercase ms-2" onclick="resetFilters();">Reset</button>
						</div>
					</div>
				</form>
			</div>

			<script>
				function showFilters() {
					if (!$('#filter-form').is(':visible')) {
						$('#filter-form').show();
					} else {
						$('#filter-form').hide();
					}
				}

				function resetFilters() {
					window.location = window.location.href.split("?")[0];
				}
			</script>

			<?php
			$whereConditions = "";

			if (isset($_REQUEST['start_date']) && empty($_REQUEST['start_date']) == false) {
				$whereConditions .= " AND date(al.`timestamp`) >= '" . date('Y-m-d', strtotime(trim($_REQUEST['start_date']))) . "'";
			}

			if (isset($_REQUEST['end_date']) && empty($_REQUEST['end_date']) == false) {
				$whereConditions .= " AND date(al.`timestamp`) <= '" . date('Y-m-d', strtotime(trim($_REQUEST['end_date']))) . "'";
			}

			if (isset($_REQUEST['hub_portal_id']) && empty($_REQUEST['hub_portal_id']) == false) {
				$whereConditions .= " AND al.hub_portal_id = '" . trim($_REQUEST['hub_portal_id']) . "'";
			}

			if (isset($_REQUEST['curl_type']) && empty($_REQUEST['curl_type']) == false) {
				$whereConditions .= " AND al.curl_type = '" . trim($_REQUEST['curl_type']) . "'";
			}

			if (isset($_REQUEST['api_origin']) && empty($_REQUEST['api_origin']) == false) {
				$whereConditions .= " AND al.api_origin = '" . trim($_REQUEST['api_origin']) . "'";
			}

			if (isset($_REQUEST['curl_url']) && empty($_REQUEST['curl_url']) == false) {
				$whereConditions .= " AND al.curl_url = '" . trim($_REQUEST['curl_url']) . "'";
			}

			if (isset($_REQUEST['curl_method']) && empty($_REQUEST['curl_method']) == false) {
				$whereConditions .= " AND al.curl_method = '" . trim($_REQUEST['curl_method']) . "'";
			}

			if (isset($_REQUEST['curl_http_code']) && empty($_REQUEST['curl_http_code']) == false) {
				$whereConditions .= " AND al.curl_http_code = '" . trim($_REQUEST['curl_http_code']) . "'";
			}

			if (isset($_REQUEST['file_name']) && empty($_REQUEST['file_name']) == false) {
				$whereConditions .= " AND al.file_name = '" . trim($_REQUEST['file_name']) . "'";
			}

			$limit = 25;
			if (isset($_REQUEST['page'])) {
				$offset = ($_REQUEST['page'] * $limit) - $limit;
				$x = ($_REQUEST['page'] * $limit) - $limit + 1;
			} else {
				$offset = 0;
				$x = 1;
			}

			$totalRecordsSql = "SELECT `id` FROM `api_logs` AS al WHERE 1=1 $whereConditions";
			$getTotalRecords = mysqli_query($conn, $totalRecordsSql);
			$totalRecords = mysqli_num_rows($getTotalRecords);
			$pages = ceil($totalRecords / $limit);

			$recordsSql = "SELECT * FROM `api_logs` AS al WHERE 1=1 $whereConditions ORDER BY al.id DESC LIMIT $limit OFFSET $offset";
			$getRecords = mysqli_query($conn, $recordsSql);
			if (mysqli_num_rows($getRecords) > 0) {
			?>
				<div class="white-container mb-3">
					<div id="records-table" style="width:100%; overflow-x:auto;">
						<table class="table table-hover table-responsive table-bordered text-center">
							<thead>
								<tr>
									<th> Portal </th>
									<th> API Origin </th>
									<th> Type </th>
									<th> File </th>
									<th> Endpoint </th>
									<th> Payload </th>
									<th> Method </th>
									<th> HTTP Code </th>
									<th> Response </th>
									<th> Timestamp </th>
								</tr>
							</thead>
							<tbody>
								<?php
								while ($rows = mysqli_fetch_assoc($getRecords)) {
								?>
									<tr>
										<td> <?= $rows['hub_portal_id'] ?> </td>
										<td> <?= $rows['api_origin'] ?> </td>
										<td> <?= $rows['curl_type'] ?> </td>
										<td> <?= $rows['file_name'] ?> </td>
										<td> <button class="btn btn-outline-primary" title="View Endpoint" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showEndpoint('<?= $rows['id'] ?>')"> View </button> </td>
										<td> <button class="btn btn-outline-primary" title="View Payload" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showPayload('<?= $rows['id'] ?>')"> View </button> </td>
										<td> <?= $rows['curl_method'] ?> </td>
										<td> <?= ($rows['curl_http_code'] >= 200 && $rows['curl_http_code'] < 400) ? "<span style='font-size:1rem; font-weight:500; color:#07c007'>" . $rows['curl_http_code'] . "</span>" : "<span style='font-size:1rem; font-weight:500; color:#FF0000'>" . $rows['curl_http_code'] . "</span>"; ?> </td>
										<td> <button class="btn btn-outline-primary" title="View Response" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showResult('<?= $rows['id'] ?>')"> View </button> </td>
										<td> <?= date("d-M-Y h:i:s A T", strtotime($rows['timestamp'])) ?> </td>
									</tr>
								<?php
								}
								?>
							</tbody>
						</table>
					</div>

					<div class="row align-items-center">
						<div id="record-count" class="col-lg-4 col-md-4 col-sm-12">
							<div class="total-records bg-secondary text-white">
								<?= isset($_GET['search']) ? "Filtered Logs" : "Total Logs" ?> &xrarr; <?= $totalRecords ?>
							</div>
						</div>
						<div id="record-pagination" class="col-lg-8 col-md-8 col-sm-12">
							<ul class="pagination">
								<?php
								if (isset($_REQUEST) && empty($_REQUEST) == false) {
									$request = $_REQUEST;
									unset($request['page']);
									$query = http_build_query($request);
									$pagination = "?" . $query . "&";
								} else {
									$pagination = "?";
								}

								if ($pages > 1) {
									$_GET['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
									$pageNum = ($_GET['page'] > 5) ? ($_GET['page'] - 4) : 1;
									$nextPage = ($pages > ($_GET['page'] + 5)) ? ($_GET['page'] + 4) : $pages;
									if ($_GET['page'] > 1) {
										$previousPage = $_GET['page'] - 1;
								?>
										<li class="page-item"><a class="page-link prev-link" href="<?=$file . $pagination . 'page=' . $previousPage ?>" id="<?= $previousPage ?>">Prev</a></li>
									<?php
									}
									for ($i = $pageNum; $i < $nextPage; $i++) {
									?>
										<li class="page-item"><a class="page-link" href="<?=$file . $pagination . 'page=' . $i ?>" id="<?= $i ?>"> <?= $i ?> </a> </li>
									<?php
									}
									if ($_GET['page'] < $pages) {
										$nextPage = ($_GET['page'] + 1);
									?>
										<li class="page-item"><a class="page-link next-link" href="<?=$file . $pagination . 'page=' . $nextPage ?>" id="<?= $nextPage ?>">Next</a> </li>
								<?php
									}
								}
								?>
							</ul>
							<script>
								var queryString = window.location.search;
								if (queryString != "") {
									const searchParams = new URLSearchParams(queryString);
									var search = searchParams.get('search');
									var pageNum = searchParams.get('page');
									if (search != "" && pageNum != "") {
										if (pageNum != "") {
											$(".page-link#" + pageNum).addClass("active disabled");											
										} else {
											$(".page-link#1").addClass("active disabled");
										}
									} else {
										$(".page-link#" + pageNum).addClass("active disabled");
									}
								} else {
									$(".page-link#1").addClass("active disabled");
								}
							</script>
						</div>
					</div>
				</div>
			<?php
			} else {
			?>
				<div class="white-container mb-3">
					<div class="alert alert-warning fw-bold text-center" role="alert"> No Records Found! </div>
				</div>
			<?php
			}
			?>
			<?php // echo "<div class='white-container mt-3' id='query-div'> <?= $recordsSql </div>" 
			?>
		</div>
	</main>

	<script>
		function showEndpoint(recordId) {
			$.ajax({
				type: "POST",
				url: "get_data.php",
				data: {
					action: "get_endpoint",
					recordId: recordId,
				},
				success: function(response) {
					$("#data-modal-label").html("ENDPOINT");
					if (response == "null" || response == null) {
						$("#data-modal-content").html(
							'<div class="alert alert-info fw-bold m-0"> No endpoint sent with this request. </div>'
						);
					} else {
						$("#data-modal-content").html("<span style='font-weight:500'>" + response + "</span>");
					}
				},
				error: function(response) {
					console.log(response);
				},
			});
		}

		function showPayload(recordId) {
			$.ajax({
				type: "POST",
				url: "get_data.php",
				data: {
					action: "get_payload",
					recordId: recordId,
				},
				success: function(response) {
					$("#data-modal-label").html("PAYLOAD");
					if (response == "null" || response == null) {
						$("#data-modal-content").html(
							'<div class="alert alert-info fw-bold m-0"> No payload sent with this request. </div>'
						);
					} else {
						$("#data-modal-content").html("<pre>" + response + "</pre>");
					}
				},
				error: function(response) {
					console.log(response);
				},
			});
		}

		function showResult(recordId) {
			$.ajax({
				type: "POST",
				url: "get_data.php",
				data: {
					action: "get_result",
					recordId: recordId,
				},
				success: function(response) {
					$("#data-modal-label").html("RESULT");
					if (response == "null" || response == null) {
						$("#data-modal-content").html(
							'<div class="alert alert-info fw-bold m-0"> No response received. </div>'
						);
					} else {
						$("#data-modal-content").html("<pre>" + response + "</pre>");
					}
				},
				error: function(response) {
					console.log(response);
				},
			});
		}
	</script>

	<div class="modal fade" id="data-modal" tabindex="-1" aria-hidden="true" aria-labelledby="data-modal-label">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title fw-bold text-uppercase" id="data-modal-label"></h4>
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close" title="Close">&#x2718;</button>
				</div>
				<div class="modal-body text-start" id="data-modal-content"></div>
			</div>
		</div>
	</div>

	<button type="button" class="btn btn-primary" id="btn-back-to-top" title="Back to Top"><i class="fa fa-chevron-up"></i></button>

	<?php include("footer.php"); ?>
</body>

</html>