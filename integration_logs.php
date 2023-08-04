<?php
// include_once("session.php");
$fileName = pathinfo(__FILE__, PATHINFO_FILENAME);
$fileExtension = pathinfo(__FILE__, PATHINFO_EXTENSION);
$file = $fileName . "." . $fileExtension;
include_once("conn.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once("head.php"); ?>
	<title> Integration Logs </title>
	<script src="<?= $jsFolder ?>/records.js"></script>
	<link rel="stylesheet" href="<?= $cssFolder ?>/records.css">
</head>

<body class="d-flex flex-column min-vh-100">
	<div class="preloader">
		<img src="<?= $assetsFolder ?>/preloader.gif" alt="Loading...">
	</div>

	<?php include_once("header.php"); ?>

	<main>
		<div class="container-fluid">

			<div class="white-container mb-3">
				<div class="d-flex align-items-center justify-content-between">
					<h4 class="fs-1 fw-bold m-0"> API Logs </h4>
					<?php
					if (isset($_GET['search']) && $_GET['search'] == "search") {
					?>
						<div class="filter-buttons d-flex align-items-center gap-2">
							<button type="button" class="btn btn-secondary filter-btn" title="Toggle Filters" onclick="toggleFilters()">
								<i class="fa-solid fa-filter fa-fw" aria-hidden="true"></i>
							</button>
							<button type="button" class="btn btn-danger filter-btn" title="Reset Filters" onclick="resetFilters()">
								<i class="fa-solid fa-filter-circle-xmark fa-fw" aria-hidden="true"></i>
							</button>
						</div>
					<?php
					} else {
					?>
						<button type="button" class="btn btn-primary filter-btn" title="Show Filters" onclick="toggleFilters()">
							<i class="fa-solid fa-filter fa-fw" aria-hidden="true"></i>
						</button>
					<?php
					}
					?>
				</div>
			</div>

			<div class="white-container mb-3" id="filter-form" style="<?php echo isset($_GET['search']) ? 'display:block;' : 'display:none;'; ?>">
				<form method="GET" id="search-form">
					<div class="row align-items-center g-3">
						<div class="col-lg-3 col-md-6 col-sm-12">
							<label for="start_date" class="form-label"> Start Date </label>
							<input type="date" class="form-control" name="start_date" id='start_date' placeholder="From Date" title="From Date" value="<?= (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '') ? $_REQUEST['start_date'] : "" ?>">
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12">
							<label for="end_date" class="form-label"> End Date </label>
							<input type="date" class="form-control" name="end_date" id='end_date' placeholder="To Date" title="To Date" value="<?= (isset($_REQUEST['end_date']) && $_REQUEST['end_date'] != '') ? $_REQUEST['end_date'] : "" ?>">
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12">
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
						<div class="col-lg-3 col-md-6 col-sm-12">
							<label for="curl_type" class="form-label"> Type </label>
							<select title="Type" name="curl_type" id="curl_type" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$groupedValues = $words = [];
								$getType = mysqli_query($conn, "SELECT DISTINCT `curl_type` FROM `api_logs` WHERE `curl_type` != '' ORDER BY `curl_type` ASC");
								if (mysqli_num_rows($getType) > 0) {
									while ($rows = mysqli_fetch_assoc($getType)) {
										$value = $rows['curl_type'];
										$words = explode(' ', $value);
										$firstWord = $words[0];
										$group = (strpos($firstWord, "_") !== false) ? "Others" : $firstWord;
										$groupedValues[$group][] = $value;
									}

									if (isset($groupedValues['Others'])) {
										$othersGroup = $groupedValues['Others'];
										unset($groupedValues['Others']);
										$groupedValues['Others'] = $othersGroup;
									}

									foreach ($groupedValues as $group => $options) {
										echo "<optgroup label='" . htmlspecialchars($group) . "'>";
										foreach ($options as $option) {
											$optionValue = htmlspecialchars($option);
											$selectedType = isset($_REQUEST['curl_type']) && $_REQUEST['curl_type'] === $optionValue;
								?>
											<option value="<?= $optionValue; ?>" <?= ($selectedType) ? "selected" : ""; ?>> <?= $optionValue; ?> </option>
								<?php
										}
										echo "</optgroup>";
									}
								}
								?>
							</select>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12">
							<label for="file_name" class="form-label"> File </label>
							<select name="file_name" id="file_name" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getFileNames = mysqli_query($conn, "SELECT DISTINCT `file_name` FROM `api_logs` WHERE `file_name` != '' ORDER BY `file_name` ASC");
								if (mysqli_num_rows($getFileNames) > 0) {
									while ($rows = mysqli_fetch_assoc($getFileNames)) {
										$selectedFileName = (isset($_REQUEST['file_name']) && $rows['file_name'] == $_REQUEST['file_name']) ? "selected" : "";
								?>
										<option value="<?= $rows['file_name'] ?>" <?= $selectedFileName ?>><?= $rows['file_name'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12">
							<label for="api_origin" class="form-label"> API Origin </label>
							<select title="API Origin" name="api_origin" id="api_origin" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getApiOrigin = mysqli_query($conn, "SELECT DISTINCT `api_origin` FROM `api_logs` WHERE `api_origin` != '' ORDER BY `api_origin` ASC");
								if (mysqli_num_rows($getApiOrigin) > 0) {
									while ($rows = mysqli_fetch_assoc($getApiOrigin)) {
										$selectedOrigin = (isset($_REQUEST['api_origin']) && $rows['api_origin'] == $_REQUEST['api_origin']) ? "selected" : "";
								?>
										<option value="<?= $rows['api_origin'] ?>" <?= $selectedOrigin ?>><?= $rows['api_origin'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12">
							<label for="curl_method" class="form-label"> Method </label>
							<select name="curl_method" id="curl_method" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getMethods = mysqli_query($conn, "SELECT DISTINCT `curl_method` FROM `api_logs` WHERE `curl_method` != '' ORDER BY `curl_method` ASC");
								if (mysqli_num_rows($getMethods) > 0) {
									while ($rows = mysqli_fetch_assoc($getMethods)) {
										$selectedMethod = (isset($_REQUEST['curl_method']) && $rows['curl_method'] == $_REQUEST['curl_method']) ? "selected" : "";
								?>
										<option value="<?= $rows['curl_method'] ?>" <?= $selectedMethod ?>><?= $rows['curl_method'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12">
							<label for="curl_http_code" class="form-label"> HTTP Response Code </label>
							<select name="curl_http_code" id="curl_http_code" class="form-select">
								<option value="" selected> --- SELECT --- </option>
								<?php
								$getResponseCodes = mysqli_query($conn, "SELECT DISTINCT `curl_http_code` FROM `api_logs` WHERE `curl_http_code` != '' ORDER BY `curl_http_code` ASC");
								if (mysqli_num_rows($getResponseCodes) > 0) {
									while ($rows = mysqli_fetch_assoc($getResponseCodes)) {
										$selectedCode = (isset($_REQUEST['curl_http_code']) && $rows['curl_http_code'] == $_REQUEST['curl_http_code']) ? "selected" : "";
								?>
										<option value="<?= $rows['curl_http_code'] ?>" <?= $selectedMethod ?>><?= $rows['curl_http_code'] ?></option>
								<?php }
								} ?>
							</select>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12">
							<label for="portal_not" class="form-label"> Not Portal </label>
							<select title="Type" name="portal_not[]" id="portal_not" class="form-control overflow-y-auto" size="5" multiple>
								<option value=""> --- SELECT --- </option>
								<?php
								$getNotPortals = mysqli_query($conn, "SELECT DISTINCT `hub_portal_id` FROM `api_logs` WHERE `hub_portal_id` != '' ORDER BY `hub_portal_id` ASC");
								if (mysqli_num_rows($getNotPortals) > 0) {
									while ($rows = mysqli_fetch_assoc($getNotPortals)) {
										$portalNot = $rows['hub_portal_id'];
										$selectedPortalNot = (isset($_REQUEST['portal_not']) && in_array($portalNot, $_REQUEST['portal_not'])) ? "selected" : "";
								?>
										<option value="<?= $portalNot ?>" <?= $selectedPortalNot ?>> <?= $portalNot ?> </option>
								<?php
									}
								}
								?>
							</select>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12">
							<label for="curl_type_not" class="form-label"> Not Type </label>
							<select title="Type" name="curl_type_not[]" id="curl_type_not" class="form-control overflow-y-auto" size="5" multiple>
								<option value=""> --- SELECT --- </option>
								<?php
								$getNotTypes = mysqli_query($conn, "SELECT DISTINCT `curl_type` FROM `api_logs` WHERE `curl_type` != '' ORDER BY `curl_type` ASC");
								if (mysqli_num_rows($getNotTypes) > 0) {
									while ($rows = mysqli_fetch_assoc($getNotTypes)) {
										$curlTypeNot = $rows['curl_type'];
										$selectedCurlTypeNot = (isset($_REQUEST['curl_type_not']) && in_array($curlTypeNot, $_REQUEST['curl_type_not'])) ? "selected" : "";
								?>
										<option value="<?= $curlTypeNot ?>" <?= $selectedCurlTypeNot ?>> <?= $curlTypeNot ?> </option>
								<?php
									}
								}
								?>
							</select>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12">
							<label for="http_code_not" class="form-label"> Not HTTP Code </label>
							<select title="Type" name="http_code_not[]" id="http_code_not" class="form-control overflow-y-auto" size="5" multiple>
								<option value=""> --- SELECT --- </option>
								<?php
								$getNotHttpCodes = mysqli_query($conn, "SELECT DISTINCT `curl_http_code` FROM `api_logs` WHERE `curl_http_code` != '' ORDER BY `curl_http_code` ASC");
								if (mysqli_num_rows($getNotHttpCodes) > 0) {
									while ($rows = mysqli_fetch_assoc($getNotHttpCodes)) {
										$httpCodeNot = $rows['curl_http_code'];
										$selectedHttpCodeNot = (isset($_REQUEST['http_code_not']) && in_array($httpCodeNot, $_REQUEST['http_code_not'])) ? "selected" : "";
								?>
										<option value="<?= $httpCodeNot ?>" <?= $selectedHttpCodeNot ?>> <?= $httpCodeNot ?> </option>
								<?php
									}
								}
								?>
							</select>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12">
							<label for="file_name_not" class="form-label"> Not File </label>
							<select title="Type" name="file_name_not[]" id="file_name_not" class="form-control overflow-y-auto" size="5" multiple>
								<option value=""> --- SELECT --- </option>
								<?php
								$getNotTypes = mysqli_query($conn, "SELECT DISTINCT `file_name` FROM `api_logs` WHERE `file_name` != '' ORDER BY `file_name` ASC");
								if (mysqli_num_rows($getNotTypes) > 0) {
									while ($rows = mysqli_fetch_assoc($getNotTypes)) {
										$filenameNot = $rows['file_name'];
										$selectedFilenameNot = (isset($_REQUEST['file_name_not']) && in_array($filenameNot, $_REQUEST['file_name_not'])) ? "selected" : "";
								?>
										<option value="<?= $filenameNot ?>" <?= $selectedFilenameNot ?>> <?= $filenameNot ?> </option>
								<?php
									}
								}
								?>
							</select>
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 text-center">
							<input type="hidden" name="search" value="search">
							<button type="submit" class="btn btn-primary"> Filter </button>
						</div>
					</div>
				</form>
			</div>

			<?php
			$whereConditions = "";

			$whereConditions .= (isset($_REQUEST['start_date']) && empty($_REQUEST['start_date']) == false) ? " AND date(al.`timestamp`) >= '" . date('Y-m-d', strtotime(trim($_REQUEST['start_date']))) . "'" : "";

			$whereConditions .= (isset($_REQUEST['end_date']) && empty($_REQUEST['end_date']) == false) ? " AND date(al.`timestamp`) <= '" . date('Y-m-d', strtotime(trim($_REQUEST['end_date']))) . "'" : "";

			$whereConditions .= (isset($_REQUEST['hub_portal_id']) && $_REQUEST['hub_portal_id'] != "") ? " AND al.hub_portal_id = '" . trim($_REQUEST['hub_portal_id']) . "'" : "";

			$whereConditions .= (isset($_REQUEST['curl_type']) && empty($_REQUEST['curl_type']) == false) ? " AND al.curl_type LIKE '%" . trim($_REQUEST['curl_type']) . "%'" : "";

			$whereConditions .= (isset($_REQUEST['file_name']) && empty($_REQUEST['file_name']) == false) ? " AND al.file_name = '" . trim($_REQUEST['file_name']) . "'" : "";

			$whereConditions .= (isset($_REQUEST['api_origin']) && empty($_REQUEST['api_origin']) == false) ? " AND al.api_origin = '" . trim($_REQUEST['api_origin']) . "'" : "";

			$whereConditions .= (isset($_REQUEST['curl_method']) && empty($_REQUEST['curl_method']) == false) ? " AND al.curl_method = '" . trim($_REQUEST['curl_method']) . "'" : "";

			$whereConditions .= (isset($_REQUEST['curl_http_code']) && $_REQUEST['curl_http_code'] != "") ? " AND al.curl_http_code = '" . trim($_REQUEST['curl_http_code']) . "'" : "";

			if (isset($_REQUEST['portal_not']) && empty($_REQUEST['portal_not']) == false) {
				$notTypes = (count($_REQUEST['portal_not']) > 1) ? "'" . implode("', '", $_REQUEST['portal_not']) . "'" : "'" . $_REQUEST['portal_not'][0] . "'";
				$whereConditions .=  " AND al.hub_portal_id NOT IN ($notTypes)";
			}

			if (isset($_REQUEST['curl_type_not']) && empty($_REQUEST['curl_type_not']) == false) {
				$notTypes = (count($_REQUEST['curl_type_not']) > 1) ? "'" . implode("', '", $_REQUEST['curl_type_not']) . "'" : "'" . $_REQUEST['curl_type_not'][0] . "'";
				$whereConditions .=  " AND al.curl_type NOT IN ($notTypes)";
			}

			if (isset($_REQUEST['file_name_not']) && empty($_REQUEST['file_name_not']) == false) {
				$notFilename = (count($_REQUEST['file_name_not']) > 1) ? "'" . implode("', '", $_REQUEST['file_name_not']) . "'" : "'" . $_REQUEST['file_name_not'][0] . "'";
				$whereConditions .=  " AND al.file_name NOT IN ($notFilename)";
			}

			if (isset($_REQUEST['http_code_not']) && empty($_REQUEST['http_code_not']) == false) {
				$notHttpCode = (count($_REQUEST['http_code_not']) > 1) ? "'" . implode("', '", $_REQUEST['http_code_not']) . "'" : "'" . $_REQUEST['http_code_not'][0] . "'";
				$whereConditions .=  " AND al.curl_http_code NOT IN ($notHttpCode)";
			}

			$limit = 25;
			$offset = (isset($_REQUEST['page'])) ? ($_REQUEST['page'] * $limit) - $limit : 0;

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
							<thead>
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
											<button type="button" class="btn btn-primary view-btn" title="View Endpoint" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showEndpoint('<?= $rows['id'] ?>')"><i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i></button>
										</td>
										<td> <?= $rows['curl_method'] ?> </td>
										<td>
											<button type="button" class="btn btn-primary view-btn" title="View Payload" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showPayload('<?= $rows['id'] ?>')"><i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i></button>
										</td>
										<td>
											<button type="button" class="btn btn-primary view-btn" title="View Payload JSON" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showPayload('<?= $rows['id'] ?>', 'json')"><i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i></button>
										</td>
										<td class="<?= ($rows['curl_http_code'] >= 200 && $rows['curl_http_code'] < 400) ? "success-code" : "error-code" ?>">
											<?= $rows['curl_http_code']; ?>
										</td>
										<td>
											<button type="button" class="btn btn-primary view-btn" title="View Response" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showResponse('<?= $rows['id'] ?>')"><i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i></button>
										</td>
										<td>
											<button type="button" class="btn btn-primary view-btn" title="View Response" data-bs-toggle="modal" data-bs-target="#data-modal" onclick="showResponse('<?= $rows['id'] ?>', 'json')"><i class="fa-solid fa-eye fa-fw" aria-hidden="true"></i></button>
										</td>
										<td> <?= formatDateTime($rows['timestamp']) ?> </td>
									</tr>
								<?php
								}
								?>
							</tbody>
						</table>
					</div>

					<?php include_once("pagination.php"); ?>
				</div>
			<?php
			} else {
			?>
				<div class="white-container mt-3">
					<div class="alert alert-warning" role="alert"> No Records Found! </div>
				</div>
			<?php
			}
			?>
		</div>
	</main>

	<?php include_once("modal.php"); ?>

	<?php include_once("footer.php"); ?>
</body>

</html>