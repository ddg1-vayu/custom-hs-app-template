<?php
require_once("conn.php");

$ajaxResponseArr = [];

if (isset($_POST['action']) && empty($_POST['action']) == false) {
	$action = (isset($_POST['action']) && empty($_POST['action']) == false) ? addslashes(strip_tags($_POST['action'])) : "";
	$fileName = (isset($_POST['fileName']) && empty($_POST['fileName']) == false) ? addslashes(strip_tags($_POST['fileName'])) : "";

	switch ($action) {
		case "upload_file":
			$filePath = "";

			$allowedExtensions = ["bmp", "gif", "jpeg", "jpg", "png", "tiff", "webp", "pdf", "doc", "docx", "xls", "xlsx", "csv", "ppt", "pptx", "txt", "mp4", "quicktime", "webm", "avi", "mov", "mkv", "3gp", "mp4", "mp3", "mpeg", "ogg"];

			$uploadsFolder = "uploads/";

			$fileType = (isset($_FILES['upload_file']['type']) && empty($_FILES['upload_file']['type']) == false) ? $_FILES["upload_file"]['type'] : "";
			$fileSize = (isset($_FILES['upload_file']['size']) && empty($_FILES['upload_file']['size']) == false) ? $_FILES["upload_file"]['size'] : "";

			$fileTmpPath = (isset($_FILES['upload_file']['tmp_name']) && empty($_FILES['upload_file']['tmp_name']) == false) ? $_FILES["upload_file"]['tmp_name'] : "";
			$selectedFileHash = hash_file("sha256", $fileTmpPath);

			if (empty($selectedFileHash) == false) {
				$checkExistingFiles = mysqli_query($conn, "SELECT * FROM `uploads` WHERE `file_checksum` = '$selectedFileHash'");
				if (mysqli_num_rows($checkExistingFiles) < 1) {
					$fileName = (isset($_FILES['upload_file']['name']) && empty($_FILES['upload_file']['name']) == false) ? strip_tags(trim($_FILES["upload_file"]['name'])) : "";

					if ($fileSize < 16000000) {
						if ($fileName != "") {
							$fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

							if (in_array($fileExtension, $allowedExtensions)) {
								$uploadDestination = $uploadsFolder . $fileName;
								if (move_uploaded_file($fileTmpPath, $uploadDestination)) {
									$filePath = "$subdomain/$uploadDestination";
									// $filePath = $uploadDestination;
									$uploadedFileHash = hash_file("sha256", $filePath);

									mysqli_query($conn, "INSERT INTO `uploads` (`file_name`, `file_path`, `file_type`, `file_size`, `file_checksum`) VALUES ('$fileName', '$filePath', '$fileType', '$fileSize', '$uploadedFileHash')");

									http_response_code(201);
									$ajaxResponseArr['message'] = "File Uploaded!";
								} else {
									http_response_code(400);
									$ajaxResponseArr['message'] = "Unable to upload File! Try again later!!";
								}
							} else {
								http_response_code(415);
								$ajaxResponseArr['message'] = "Unsupported File Type!";
							}
						} else {
							http_response_code(400);
							$ajaxResponseArr['message'] = "File cannot be empty!";
						}
					} else {
						http_response_code(413);
						$ajaxResponseArr['message'] = "File size more than 16 MB";
					}
				} else {
					http_response_code(409);
					$ajaxResponseArr['message'] = "File Already Uploaded!";
				}
			} else {
				http_response_code(400);
				$ajaxResponseArr['message'] = "Undefined Error! Try again later!";
			}

			

			echo json_encode($ajaxResponseArr);
			break;
	}
}
