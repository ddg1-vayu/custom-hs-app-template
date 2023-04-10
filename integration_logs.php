<?php
// include("session.php");
date_default_timezone_set("Asia/Kolkata");
$fileName = pathinfo(__FILE__, PATHINFO_FILENAME);
$fileExtension = pathinfo(__FILE__, PATHINFO_EXTENSION);
$file = $fileName . "." . $fileExtension;
include("conn.php");

function startsWith($haystack, $needle) {
	return !strncmp($haystack, $needle, strlen($needle));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include("head.php"); ?>
	<title> Integration Logs </title>
	<link rel="stylesheet" href="css/integration.css">
</head>

<body class="d-flex flex-column min-vh-100">
	<script src="js/preloader.js"></script>
	<div class="preloader">
		<img src="assets/preloader.gif" alt="Loading...">
	</div>

	<?php include("header.php"); ?>

	<script src="js/scroll-top.js"></script>
	<script src="js/custom.js"></script>
	<script src="js/ajax.js"></script>

	<main>
		<div class="container-fluid">

			<div class="white-container mb-3">
				<div class="d-flex align-items-center justify-content-between">
					<h4 class="fs-1 fw-bold m-0"> API Logs </h4>
					<button type="button" class="btn btn-primary filter-btn" title="Show Filters" onclick="showFilters()">
						<i class="fa-solid fa-filter" aria-hidden="true"></i>
					</button>
				</div>
			</div>

			<div class="white-container mb-3" id="filter-form" style="<?php echo isset($_GET['search']) ? 'display:block;' : 'display:none;'; ?>">
				<form method="GET" id="search-form">
					<div class="row align-items-center">
						<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
							<label for="start_date" class="form-label"> Start Date </label>
							<input type="date" class="form-control" name="start_date" id='start_date' placeholder="From Date" title="From Date" value="<?= (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '') ? $_REQUEST['start_date'] : "" ?>">
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
							<label for="end_date" class="form-label"> End Date </label>
							<input type="date" class="form-control" name="end_date" id='end_date' placeholder="To Date" title="To Date" value="<?= (isset($_REQUEST['end_date']) && $_REQUEST['end_date'] != '') ? $_REQUEST['end_date'] : "" ?>">
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
							<label for="hub_portal_id" class="form-label"> Portal </label>
							<select title="Portal" name="hub_portal_id" id="hub_portal_id" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getPortals = mysqli_query($conn, "SELECT DISTINCT `hub_portal_id` FROM `api_logs` ORDER BY `hub_portal_id` ASC");
								if (mysqli_num_rows($getPortals) > 0) {
									while ($rows = mysqli_fetch_assoc($getPortals)) {
										$selectedPortal = (isset($_REQUEST['hub_portal_id']) && $_REQUEST['hub_portal_id'] == $rows['hub_portal_id']) ? "selected" : "";
								?>
										<option value="<?= $rows['hub_portal_id'] ?>" <?= $selectedPortal ?>><?= $rows['hub_portal_id'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
							<label for="curl_type" class="form-label"> Type </label>
							<select title="Type" name="curl_type" id="curl_type" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$allValues = $filteredValues = $allLabels = $labels = $otherValues = [];
								$getType = mysqli_query($conn, "SELECT DISTINCT `curl_type` FROM `api_logs` WHERE `curl_type` != '' ORDER BY `curl_type` ASC");
								if (mysqli_num_rows($getType) > 0) {
									while ($rows = mysqli_fetch_assoc($getType)) {
										$curlType = $rows['curl_type'];
										$labelArr = explode(" ", $curlType);
										array_push($allValues, $curlType);
										array_push($allLabels, $labelArr[0]);
									}

									$uniqueLabels = array_unique($allLabels);
									foreach ($uniqueLabels as $value) {
										if (strpos($value, '_') !== false) {
											array_push($otherValues, $value);
										} else {
											array_push($labels, $value);
										}
									}

									$array = array_diff($allValues, $otherValues);
									foreach ($array as $value) {
										array_push($filteredValues, $value);
									}

									$selectedType = "";
									for ($x = 0; $x < count($labels); $x++) { ?>
										<optgroup label="<?= $labels[$x] ?>">
											<?php
											foreach ($filteredValues as $values) {
												if (startsWith($values, $labels[$x])) {
													$selectedType = (isset($_REQUEST['curl_type']) && $_REQUEST['curl_type'] == $values)  ? "selected" : "";
											?>
													<option value="<?= $values ?>" <?= $selectedType ?>><?= $values ?></option>
											<?php
												}
											}
											?>
										</optgroup>
									<?php
									}
									if (empty($otherValues) == false) {
									?>
										<optgroup label="Others">
											<?php
											foreach ($otherValues as $values) {
												$selectedType = (isset($_REQUEST['curl_type']) && $_REQUEST['curl_type'] == $values)  ? "selected" : "";
											?>
												<option value="<?= $values ?>" <?= $selectedType ?>><?= $values ?></option>
											<?php
											} ?>
										</optgroup>
								<?php
									}
								}
								?>
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
							<select title="API Origin" name="api_origin" id="api_origin" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getApiOrigin = mysqli_query($conn, "SELECT DISTINCT `api_origin` FROM `api_logs` WHERE `api_origin` != '' ORDER BY `api_origin` ASC");
								if (mysqli_num_rows($getApiOrigin) > 0) {
									while ($rows = mysqli_fetch_assoc($getApiOrigin)) {
										$selectedOrigin = ($rows['api_origin'] == $_REQUEST['api_origin']) ? "selected='selected'" : "";
								?>
										<option value="<?= $rows['api_origin'] ?>" <?= $selectedOrigin ?>><?= $rows['api_origin'] ?></option>
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
							<button type="button" class="btn btn-secondary text-uppercase" onclick="resetFilters();">Reset</button>
							<button type="submit" class="btn btn-primary text-uppercase ms-2"> Filter </button>
						</div>
					</div>
				</form>
			</div>

			<?php
			$whereConditions = "";

			$whereConditions .= (isset($_REQUEST['start_date']) && empty($_REQUEST['start_date']) == false) ? " AND date(al.`timestamp`) >= '" . date('Y-m-d', strtotime(trim($_REQUEST['start_date']))) . "'" : "";

			$whereConditions .= (isset($_REQUEST['end_date']) && empty($_REQUEST['end_date']) == false) ? " AND date(al.`timestamp`) <= '" . date('Y-m-d', strtotime(trim($_REQUEST['end_date']))) . "'" : "";

			$whereConditions .= (isset($_REQUEST['hub_portal_id']) && $_REQUEST['hub_portal_id'] != "") ? " AND al.hub_portal_id = '" . trim($_REQUEST['hub_portal_id']) . "'" : "";

			$whereConditions .= (isset($_REQUEST['api_origin']) && empty($_REQUEST['api_origin']) == false) ? " AND al.api_origin = '" . trim($_REQUEST['api_origin']) . "'" : "";

			$whereConditions .= (isset($_REQUEST['curl_method']) && empty($_REQUEST['curl_method']) == false) ? " AND al.curl_method = '" . trim($_REQUEST['curl_method']) . "'" : "";

			$whereConditions .= (isset($_REQUEST['curl_http_code']) && $_REQUEST['curl_http_code'] != "") ? " AND al.curl_http_code = '" . trim($_REQUEST['curl_http_code']) . "'" : "";

			$whereConditions .= (isset($_REQUEST['curl_type']) && empty($_REQUEST['curl_type']) == false) ? " AND al.curl_type LIKE '%" . trim($_REQUEST['curl_type']) . "%'" : "";

			$whereConditions .= (isset($_REQUEST['file_name']) && empty($_REQUEST['file_name']) == false) ? " AND al.file_name = '" . trim($_REQUEST['file_name']) . "'" : "";

			$limit = 15;
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
				<div class="white-container">
					<div id="records-table" style="width:100%; overflow-x:auto;">
						<table class="table table-hover table-responsive table-bordered text-center">
							<thead class="text-uppercase">
								<tr>
									<th> Action </th>
									<th> Origin </th>
									<th> Portal </th>
									<th> File </th>
									<th> Endpoint </th>
									<th> Method </th>
									<th> Payload </th>
									<th> Payload JSON </th>
									<th> HTTP Code </th>
									<th> Response </th>
									<th> Response JSON </th>
									<th> Timestamp </th>
								</tr>
							</thead>
							<tbody class="align-middle table-group-divider">
								<?php
								while ($rows = mysqli_fetch_assoc($getRecords)) {
								?>
									<tr>
										<td> <?= $rows['curl_type'] ?> </td>
										<td> <?= $rows['api_origin'] ?> </td>
										<td> <?= $rows['hub_portal_id'] ?> </td>
										<td> <?= $rows['file_name'] ?> </td>
										<td>
											<button type="button" class="btn btn-primary" title="View Endpoint" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showEndpoint('<?= $rows['id'] ?>')"> View </button>
										</td>
										<td> <?= $rows['curl_method'] ?> </td>
										<td>
											<button type="button" class="btn btn-primary" title="View Payload" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showPayload('<?= $rows['id'] ?>')"> View </button>
										</td>
										<td>
											<button type="button" class="btn btn-primary" title="View Payload JSON" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showPayload('<?= $rows['id'] ?>', 'json')"> View </button>
										</td>
										<td class="<?= ($rows['curl_http_code'] >= 200 && $rows['curl_http_code'] < 400) ? "success-code" : "error-code" ?>">
											<?= $rows['curl_http_code']; ?>
										</td>
										<td>
											<button type="button" class="btn btn-primary" title="View Response" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showResponse('<?= $rows['id'] ?>')"> View </button>
										</td>
										<td>
											<button type="button" class="btn btn-primary" title="View Response" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showResponse('<?= $rows['id'] ?>', 'json')"> View </button>
										</td>
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
							<div class="total-records">
								<?= (isset($_GET['search']) && $_GET['search'] == "search") ? "Filtered Records" : "Total Records" ?> &xrarr; <?= $totalRecords ?>
							</div>
						</div>
						<div id="record-pagination" class="col-lg-8 col-md-8 col-sm-12">
							<ul class="pagination">
								<?php
								if (isset($_REQUEST['search']) && empty($_REQUEST['search']) == false) {
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
										<li class="page-item">
											<a class="page-link prev-link" href="<?= $file . $pagination . 'page=' . $previousPage ?>" id="<?= $previousPage ?>">Prev</a>
										</li>
									<?php
									}
									for ($i = $pageNum; $i < $nextPage; $i++) {
									?>
										<li class="page-item">
											<a class="page-link" href="<?= $file . $pagination . 'page=' . $i ?>" id="<?= $i ?>"> <?= $i ?> </a>
										</li>
									<?php
									}
									if ($_GET['page'] < $pages) {
										$nextPage = ($_GET['page'] + 1);
									?>
										<li class="page-item">
											<a class="page-link next-link" href="<?= $file . $pagination . 'page=' . $nextPage ?>" id="<?= $nextPage ?>">Next</a>
										</li>
								<?php
									}
								}
								?>
							</ul>
						</div>
					</div>
				</div>
			<?php
			} else {
			?>
				<div class="white-container mt-3">
					<div class="alert alert-warning fw-bold text-center" role="alert"> No Records Found! </div>
				</div>
			<?php
			}
			echo "<div class='white-container mt-3' id='query-div'> $recordsSql </div>";
			?>
		</div>
	</main>

	<div class="modal fade" id="data-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" aria-labelledby="data-modal-label">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title fw-bold text-uppercase" id="data-modal-label"></h4>
					<i type="button" title="Close" class="fa-regular fa-circle-xmark text-danger fs-3" data-bs-dismiss="modal" aria-label="Close"></i>
				</div>
				<div class="modal-body text-start" id="data-modal-content"></div>
			</div>
		</div>
	</div>

	<?php include("footer.php"); ?>
</body>

</html>
