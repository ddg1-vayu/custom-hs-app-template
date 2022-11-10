<?php
ini_set("display_errors", 1);
$fileName = pathinfo(__FILE__, PATHINFO_FILENAME);
// require("conn.php");

if (isset($_POST['action']) && empty($_POST['action']) == false) {
	$action = $_POST['action'];

	switch ($action) {
		case "login":
			$user = stripslashes(strip_tags($_POST["user"]));
			$password = stripslashes(strip_tags($_POST["password"]));

			if (empty($user) == false && empty($password) == false) {
				$getUsers = mysqli_query($conn, "SELECT * FROM `users` WHERE `username` = '$user' OR `email` = '$user' LIMIT 1");
				if (mysqli_num_rows($getUsers) > 0) {
					$rows = mysqli_fetch_assoc($getUsers);
					$userId = $rows['id'];
					$userPassword = $rows['password'];

					if (password_verify($password, $userPassword)) {
						$_SESSION['id'] = $userId;
						$_SESSION['login_user'] = $user;

						$http_cookie = $_SERVER['HTTP_COOKIE'];
						$remote_address = $_SERVER['REMOTE_ADDR'];
						$remote_port = $_SERVER['REMOTE_PORT'];
						$ua_platform = trim(str_replace("\"", "", $_SERVER['HTTP_SEC_CH_UA_PLATFORM']));
						$ua_version = implode(" | ", explode(", ", str_replace("\"", "", $_SERVER['HTTP_SEC_CH_UA'])));
						$user_agent = $_SERVER['HTTP_USER_AGENT'];

						mysqli_query($conn, "INSERT INTO `user_access_logs` (`user_id`, `user`, `http_cookie`, `remote_address`, `remote_port`, `ua_platform`, `ua_version`, `user_agent`) VALUES ('$userId', '$user', '$http_cookie', '$remote_address', '$remote_port', '$ua_platform', '$ua_version', '$user_agent')");
						mysqli_query($conn, "UPDATE `registered_users` SET `last_login` = current_timestamp() WHERE `id` = '$userId' LIMIT 1");
						echo "Login Successful";
					} else {
						echo "Incorrect Password!";
					}
				} else {
					echo "Unregistered User";
				}
			} else {
				echo "empty form";
			}
			break;
	}
}
