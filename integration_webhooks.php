<?php
// include_once("session.php");
date_default_timezone_set("Asia/Kolkata");
$fileName = pathinfo(__FILE__, PATHINFO_FILENAME);
$fileExtension = pathinfo(__FILE__, PATHINFO_EXTENSION);
$file = $fileName . "." . $fileExtension;
include_once("conn.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once("head.php"); ?>
	<title> Integration Webhooks </title>
	<script src="js/preload.js"></script>
	<script src="js/custom.js"></script>
	<script src="js/ajax.js"></script>
	<link rel="stylesheet" href="css/integration.css">
</head>

<body class="d-flex flex-column min-vh-100">
	<div class="preloader">
		<img src="assets/preloader.gif" alt="Loading...">
	</div>

	<?php include_once("header.php"); ?>

	<main>
		<div class="container-fluid">
			<div class="white-container mb-3">
				<div class="d-flex align-items-center justify-content-between">
					<h4 class="fs-1 fw-bold m-0"> Webhooks </h4>
					<?php
					if (isset($_GET['search']) && $_GET['search'] == "search") {
					?>
						<button type="button" class="btn btn-danger filter-btn" title="Reset Filters" onclick="resetFilters()">
							<i class="fa-solid fa-filter-circle-xmark" aria-hidden="true"></i>
						</button>
					<?php
					} else {
					?>
						<button type="button" class="btn btn-primary filter-btn" title="Show Filters" onclick="showFilters()">
							<i class="fa-solid fa-filter" aria-hidden="true"></i>
						</button>
					<?php
					}
					?>
				</div>
			</div>

			<div class="white-container mb-3" id="filter-form" style="<?= isset($_GET['search']) ? 'display:block;' : 'display:none;'; ?>">
				<form method="GET" id="search-form">
					<div class="row align-items-center">
						<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
							<label for="start_date" class="form-label"> Start Date </label>
							<input type="date" class="form-control" name="start_date" id='start_date' placeholder="From Date" title="From Date" value="<?= (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '') ? $_REQUEST['start_date'] : "" ?>">
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
							<label for="end_date" class="form-label"> End Date </label>
							<input type="date" class="form-control" name="end_date" id='end_date' placeholder="To Date" title="To Date" value="<?= (isset($_REQUEST['end_date']) && $_REQUEST['end_date'] != '') ? $_REQUEST['end_date'] : "" ?>">
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
							<label for="hub_portal_id" class="form-label"> Portal </label>
							<select title="Portal" name="hub_portal_id" id="hub_portal_id" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getPortals = mysqli_query($conn, "SELECT DISTINCT `hub_portal_id` FROM `webhooks` ORDER BY `hub_portal_id` ASC");
								if (mysqli_num_rows($getPortals) > 0) {
									while ($rows = mysqli_fetch_assoc($getPortals)) {
										$selectedPortal = (isset($_REQUEST['hub_portal_id']) && $_REQUEST['hub_portal_id'] == $rows['hub_portal_id']) ? "selected" : "";
								?>
										<option value="<?= $rows['hub_portal_id'] ?>" <?= $selectedPortal ?>><?= $rows['hub_portal_id'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
							<label for="type" class="form-label"> Type </label>
							<select title="Type" name="type" id="type" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getType = mysqli_query($conn, "SELECT DISTINCT `type` FROM `webhooks` WHERE `type` != '' ORDER BY `type` ASC");
								if (mysqli_num_rows($getType) > 0) {
									while ($rows = mysqli_fetch_assoc($getType)) {
										$selectedType = (isset($_REQUEST['type']) && $_REQUEST['type'] == $rows['type'])  ? "selected" : "";
								?>
										<option value="<?= $rows['type'] ?>" <?= $selectedType ?>><?= $rows['type'] ?></option>
								<?php
									}
								}
								?>
							</select>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
							<label for="source" class="form-label"> Source </label>
							<select title="Source" name="source" id="source" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getSource = mysqli_query($conn, "SELECT DISTINCT `source` FROM `webhooks` WHERE `source` != '' ORDER BY `source` ASC");
								if (mysqli_num_rows($getSource) > 0) {
									while ($rows = mysqli_fetch_assoc($getSource)) {
										$selectedSource = (isset($_REQUEST['source']) && $_REQUEST['source'] == $rows['source'])  ? "selected" : "";
								?>
										<option value="<?= $rows['source'] ?>" <?= $selectedSource ?>><?= $rows['source'] ?></option>
								<?php
									}
								}
								?>
							</select>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
							<label for="file_name" class="form-label"> File </label>
							<select title="File" name="file_name" id="file_name" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getFileNames = mysqli_query($conn, "SELECT DISTINCT `file_name` FROM `webhooks` WHERE `file_name` != '' ORDER BY `file_name` ASC");
								if (mysqli_num_rows($getFileNames) > 0) {
									while ($rows = mysqli_fetch_assoc($getFileNames)) {
										$selectedFile = (isset($_REQUEST['file_name']) && $_REQUEST['file_name'] == $rows['file_name']) ? "selected" : "";
								?>
										<option value="<?= $rows['file_name'] ?>" <?= $selectedFile ?>><?= $rows['file_name'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12 mb-3">
							<label for="status" class="form-label"> Status </label>
							<select title="Status" name="status" id="status" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getStatus = mysqli_query($conn, "SELECT DISTINCT `status` FROM `webhooks` WHERE `status` != '' ORDER BY `status` DESC");
								if (mysqli_num_rows($getStatus) > 0) {
									while ($rows = mysqli_fetch_assoc($getStatus)) {
										$selectedStatus = (isset($_REQUEST['status']) && $_REQUEST['status'] == $rows['status']) ? "selected" : "";
								?>
										<option value="<?= $rows['status'] ?>" <?= $selectedStatus ?>> <?= ($rows['status'] == 1 || $rows['status'] == "processed") ? "Processed" : "Not Processed" ?> </option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 text-center mt-2">
							<input type="hidden" name="search" value="search">
							<button type="submit" class="btn btn-primary text-uppercase"> Filter </button>
						</div>
					</div>
				</form>
			</div>

			<?php
			$whereConditions = "";

			$whereConditions .= (isset($_REQUEST['start_date']) && empty($_REQUEST['start_date']) == false) ? " AND date(w.`timestamp`) >= '" . date('Y-m-d', strtotime(trim($_REQUEST['start_date']))) . "'" : "";

			$whereConditions .= (isset($_REQUEST['end_date']) && empty($_REQUEST['end_date']) == false) ? " AND date(w.`timestamp`) <= '" . date('Y-m-d', strtotime(trim($_REQUEST['end_date']))) . "'" : "";

			$whereConditions .= (isset($_REQUEST['hub_portal_id']) && $_REQUEST['hub_portal_id'] != "") ? " AND w.hub_portal_id = '" . trim($_REQUEST['hub_portal_id']) . "'" : "";

			$whereConditions .= (isset($_REQUEST['type']) && empty($_REQUEST['type']) == false) ? " AND w.type = '" . trim($_REQUEST['type']) . "'" : "";

			$whereConditions .= (isset($_REQUEST['source']) && empty($_REQUEST['source']) == false) ? " AND w.source = '" . trim($_REQUEST['source']) . "'" : "";

			$whereConditions .= (isset($_REQUEST['file_name']) && empty($_REQUEST['file_name']) == false) ? " AND w.file_name = '" . trim($_REQUEST['file_name']) . "'" : "";

			$whereConditions .= (isset($_REQUEST['status']) && $_REQUEST['status'] != "") ? " AND w.status = '" . trim($_REQUEST['status']) . "'" : "";

			$limit = 15;
			if (isset($_REQUEST['page'])) {
				$offset = ($_REQUEST['page'] * $limit) - $limit;
				$x = ($_REQUEST['page'] * $limit) - $limit + 1;
			} else {
				$offset = 0;
				$x = 1;
			}

			$totalRecordsSql = "SELECT `id` FROM `webhooks` AS w WHERE 1=1 $whereConditions";
			$getTotalRecords = mysqli_query($conn, $totalRecordsSql);
			$totalRecords = mysqli_num_rows($getTotalRecords);
			$pages = ceil($totalRecords / $limit);

			$recordsSql = "SELECT * FROM `webhooks` AS w WHERE 1=1 $whereConditions ORDER BY w.id DESC LIMIT $limit OFFSET $offset";
			$getRecords = mysqli_query($conn, $recordsSql);
			if (mysqli_num_rows($getRecords) > 0) {
			?>
				<div class="white-container">
					<div id="records-table" style="width:100%; overflow-x:auto;">
						<table class="table table-hover table-responsive table-bordered text-center">
							<thead class="text-uppercase">
								<tr>
									<th> Portal </th>
									<th> Type </th>
									<th> Source </th>
									<th> File </th>
									<th> Payload </th>
									<th> Payload JSON </th>
									<th> Status </th>
									<th> Timestamp </th>
									<th> Last Modified </th>
								</tr>
							</thead>
							<tbody class="align-middle table-group-divider">
								<?php
								while ($rows = mysqli_fetch_assoc($getRecords)) {
								?>
									<tr>
										<td> <?= empty($rows['hub_portal_id'] == false) ? $rows['hub_portal_id'] : "&mdash;" ?> </td>
										<td> <?= empty($rows['type'] == false) ? $rows['type'] : "&mdash;" ?> </td>
										<td> <?= empty($rows['source'] == false) ? $rows['source'] : "&mdash;" ?> </td>
										<td> <?= empty($rows['file_name'] == false) ? $rows['file_name'] : "&mdash;" ?> </td>
										<td>
											<button type="button" class="btn btn-primary" title="Payload" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showWebhook('<?= $rows['id'] ?>')"> View </button>
										</td>
										<td>
											<button type="button" class="btn btn-primary" title="Payload" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showWebhook('<?= $rows['id'] ?>', 'json')"> View </button>
										</td>
										<td class="lh-1">
											<?php if ($rows['status'] == "processed" || $rows['status'] == 1) { ?>
												<i class="fa-regular fa-circle-check fa-fw success-code" aria-hidden="true" title="Processed"></i>
											<?php } else { ?>
												<i class="fa-regular fa-circle-xmark fa-fw error-code" aria-hidden="true" title="Not Processed"></i>
											<?php } ?>
										</td>
										<td> <?= date("d-M-Y h:i:s A T", strtotime($rows['timestamp'])) ?> </td>
										<td> <?= date("d-M-Y h:i:s A T", strtotime($rows['last_modified'])) ?> </td>
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
								<?= isset($_GET['search']) ? "Filtered Records" : "Total Records" ?><i class="fa-solid fa-arrow-right mx-2"></i><?= $totalRecords ?>
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
											<a class="page-link" href="<?= $file . $pagination . 'page=' . $i ?>" id="<?= $i ?>"><?= $i ?></a>
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

	<?php include_once("modal.php"); ?>

	<?php include_once("footer.php"); ?>
</body>

</html>