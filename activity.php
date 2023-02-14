<?php
include("session.php");
date_default_timezone_set("Asia/Kolkata");
$fileName = pathinfo(__FILE__, PATHINFO_FILENAME);
$fileExtension = pathinfo(__FILE__, PATHINFO_EXTENSION);
$file = $fileName . "." . $fileExtension;
include("conn.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include("head.php"); ?>
	<title> Activity Logs </title>
	<link rel="stylesheet" href="css/integration.css">
	<style> .comment {line-height: normal;} </style>
</head>

<body class="d-flex flex-column min-vh-100">
	<?php include("header.php"); ?>

	<script src="js/custom.js"></script>
	<script src="js/scroll-top.js"></script>

	<main>
		<div class="container-fluid">
			<div class="white-container mb-3">
				<div class="d-flex align-items-center justify-content-between">
					<h4 class="fs-1 fw-bold m-0"> Activity Logs </h4>
					<button class="btn btn-primary" title="Show Filters" onclick="showFilters()"> <i class="fa fa-filter" aria-hidden="true"></i> </button>
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
							<label for="user" class="form-label"> User </label>
							<select title="User" name="user" id="user" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getUsers = mysqli_query($conn, "SELECT DISTINCT `user` FROM `admin_access_logs` ORDER BY `user` ASC");
								if (mysqli_num_rows($getUsers) > 0) {
									while ($rows = mysqli_fetch_assoc($getUsers)) {
										$selectedUser = (isset($_REQUEST['user']) && $_REQUEST['user'] == $rows['user']) ? "selected" : "";
								?>
										<option value="<?= $rows['user'] ?>" <?= $selectedUser ?>><?= $rows['user'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
							<label for="action" class="form-label"> Action </label>
							<select title="Action" name="action" id="action" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getAction = mysqli_query($conn, "SELECT DISTINCT `action` FROM `admin_access_logs` WHERE `action` != '' ORDER BY `action` ASC");
								if (mysqli_num_rows($getAction) > 0) {
									while ($rows = mysqli_fetch_assoc($getAction)) {
										$selectedAction = (isset($_REQUEST['action']) && $_REQUEST['action'] == $rows['action']) ? "selected" : "";
								?>
										<option value="<?= $rows['action'] ?>" <?= $selectedAction ?>><?= $rows['action'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 mb-3">
							<label for="remote_address" class="form-label"> IP Address </label>
							<select title="IP Address" name="remote_address" id="remote_address" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getIpAddress = mysqli_query($conn, "SELECT DISTINCT `remote_address` FROM `admin_access_logs` WHERE `remote_address` != '' ORDER BY `remote_address` ASC");
								if (mysqli_num_rows($getIpAddress) > 0) {
									while ($rows = mysqli_fetch_assoc($getIpAddress)) {
										$selectedIpAddress = (isset($_REQUEST['remote_address']) && $_REQUEST['remote_address'] == $rows['remote_address']) ? "selected" : "";
								?>
										<option value="<?= $rows['remote_address'] ?>" <?= $selectedIpAddress ?>><?= $rows['remote_address'] ?></option>
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
			<?php
			$whereConditions = "";

			if (isset($_REQUEST['start_date']) && empty($_REQUEST['start_date']) == false) {
				$whereConditions .= " AND date(aal.`timestamp`) >= '" . date('Y-m-d', strtotime(trim($_REQUEST['start_date']))) . "'";
			}

			if (isset($_REQUEST['end_date']) && empty($_REQUEST['end_date']) == false) {
				$whereConditions .= " AND date(aal.`timestamp`) <= '" . date('Y-m-d', strtotime(trim($_REQUEST['end_date']))) . "'";
			}

			if (isset($_REQUEST['user']) && $_REQUEST['user'] != "") {
				$whereConditions .= " AND aal.user = '" . trim($_REQUEST['user']) . "'";
			}

			if (isset($_REQUEST['remote_address']) && empty($_REQUEST['remote_address']) == false) {
				$whereConditions .= " AND aal.remote_address = '" . trim($_REQUEST['remote_address']) . "'";
			}

			if (isset($_REQUEST['action']) && empty($_REQUEST['action']) == false) {
				$whereConditions .= " AND aal.action = '" . trim($_REQUEST['action']) . "'";
			}

			$limit = 25;
			if (isset($_REQUEST['page'])) {
				$offset = ($_REQUEST['page'] * $limit) - $limit;
				$x = ($_REQUEST['page'] * $limit) - $limit + 1;
			} else {
				$offset = 0;
				$x = 1;
			}

			$totalRecordsSql = "SELECT `id` FROM `admin_access_logs` AS aal WHERE 1=1 $whereConditions";
			$getTotalRecords = mysqli_query($conn, $totalRecordsSql);
			$totalRecords = mysqli_num_rows($getTotalRecords);
			$pages = ceil($totalRecords / $limit);

			$recordsSql = "SELECT * FROM `admin_access_logs` AS aal WHERE 1=1 $whereConditions ORDER BY aal.id DESC LIMIT $limit OFFSET $offset";
			$getRecords = mysqli_query($conn, $recordsSql);
			if (mysqli_num_rows($getRecords) > 0) {
			?>
				<div class="white-container mb-3">
					<div id="records-table" style="width:100%; overflow-x:auto;">
						<table class="table table-hover table-responsive table-bordered text-center">
							<thead>
								<tr>
									<th> User </th>
									<th> Action </th>
									<th> Comment </th>
									<th> IP Address </th>
									<th> Timestamp </th>
								</tr>
							</thead>
							<tbody>
								<?php
								while ($rows = mysqli_fetch_assoc($getRecords)) {
								?>
									<tr>
										<td> <?= $rows['user'] ?> </td>
										<td> <?= $rows['action'] ?> </td>
										<?= (empty($rows['comment']) == false) ? "<td class='text-start comment'>" . $rows['comment'] . "</td>" : "<td class='comment'> — </td>"; ?>
										<td> <?= $rows['remote_address'] ?> </td>
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
										<li class="page-item"><a class="page-link prev-link" href="<?= $file . $pagination . 'page=' . $previousPage ?>" id="<?= $previousPage ?>">Prev</a></li>
									<?php
									}
									for ($i = $pageNum; $i < $nextPage; $i++) {
									?>
										<li class="page-item"><a class="page-link" href="<?= $file . $pagination . 'page=' . $i ?>" id="<?= $i ?>"> <?= $i ?> </a> </li>
									<?php
									}
									if ($_GET['page'] < $pages) {
										$nextPage = ($_GET['page'] + 1);
									?>
										<li class="page-item"><a class="page-link next-link" href="<?= $file . $pagination . 'page=' . $nextPage ?>" id="<?= $nextPage ?>">Next</a> </li>
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
				<div class="white-container mb-3">
					<div class="alert alert-warning fw-bold text-center" role="alert"> No Records Found! </div>
				</div>
			<?php
			}
			?>
			<?php //echo "<div class='white-container mt-3' id='query-div'> $recordsSql </div>" 
			?>
		</div>
	</main>

	<button type="button" class="btn btn-primary" id="btn-back-to-top" title="Back to Top"><i class="fa fa-chevron-up"></i></button>

	<?php include("footer.php"); ?>
</body>

</html>